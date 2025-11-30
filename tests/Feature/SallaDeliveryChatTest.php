<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Driver;
use App\Models\OrderMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SallaDeliveryChatTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $driver;
    protected $order;

    protected function setUp(): void
    {
        parent::setUp();

        // إنشاء مستخدم تجريبي
        $this->user = User::factory()->create([
            'fcm_token' => 'test_user_fcm_token_123',
        ]);

        // إنشاء سائق تجريبي
        $this->driver = Driver::create([
            'name' => 'أحمد السائق',
            'phone' => '0771234567',
            'email' => 'driver@test.com',
            'license_number' => 'LIC-12345',
            'vehicle_type' => 'سيارة',
            'vehicle_number' => 'ABC-123',
            'status' => 'active',
            'fcm_token' => 'test_driver_fcm_token_456',
        ]);

        // إنشاء طلب تجريبي
        $this->order = Order::factory()->create([
            'user_id' => $this->user->id,
            'driver_id' => $this->driver->id,
        ]);
    }

    /** @test */
    public function user_can_send_message_to_driver()
    {
        $response = $this->postJson("/api/v1/salla-delivery/chat/send/{$this->order->id}", [
            'message' => 'مرحباً، متى ستصل؟',
            'sender_type' => 'user',
            'sender_id' => $this->user->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'message_id',
                    'order_id',
                    'order_number',
                    'sender_type',
                    'message',
                    'created_at',
                ],
            ]);

        $this->assertDatabaseHas('order_messages', [
            'order_id' => $this->order->id,
            'sender_type' => 'user',
            'sender_id' => $this->user->id,
            'message' => 'مرحباً، متى ستصل؟',
        ]);
    }

    /** @test */
    public function driver_can_send_message_to_user()
    {
        $response = $this->postJson("/api/v1/salla-delivery/chat/send/{$this->order->id}", [
            'message' => 'سأصل خلال 10 دقائق',
            'sender_type' => 'driver',
            'sender_id' => $this->driver->id,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('order_messages', [
            'order_id' => $this->order->id,
            'sender_type' => 'driver',
            'message' => 'سأصل خلال 10 دقائق',
        ]);
    }

    /** @test */
    public function can_send_message_with_attachment()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('location.jpg');

        $response = $this->postJson("/api/v1/salla-delivery/chat/send/{$this->order->id}", [
            'message' => 'هذا موقعي الحالي',
            'sender_type' => 'driver',
            'sender_id' => $this->driver->id,
            'attachment' => $file,
        ]);

        $response->assertStatus(201);

        // التحقق من رفع الملف
        Storage::disk('public')->assertExists('order_messages/' . $file->hashName());
    }

    /** @test */
    public function cannot_send_message_with_invalid_attachment_type()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('virus.exe', 1000);

        $response = $this->postJson("/api/v1/salla-delivery/chat/send/{$this->order->id}", [
            'message' => 'رسالة مع ملف غير مسموح',
            'sender_type' => 'user',
            'sender_id' => $this->user->id,
            'attachment' => $file,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['attachment']);
    }

    /** @test */
    public function cannot_send_message_to_non_existent_order()
    {
        $response = $this->postJson('/api/v1/salla-delivery/chat/send/99999', [
            'message' => 'رسالة لطلب غير موجود',
            'sender_type' => 'user',
            'sender_id' => $this->user->id,
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'الطلب غير موجود',
            ]);
    }

    /** @test */
    public function can_get_all_messages_for_order()
    {
        // إنشاء عدة رسائل
        OrderMessage::create([
            'order_id' => $this->order->id,
            'sender_type' => 'user',
            'sender_id' => $this->user->id,
            'message' => 'رسالة 1',
        ]);

        OrderMessage::create([
            'order_id' => $this->order->id,
            'sender_type' => 'driver',
            'sender_id' => $this->driver->id,
            'message' => 'رسالة 2',
        ]);

        $response = $this->getJson("/api/v1/salla-delivery/chat/messages/{$this->order->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'order_id',
                    'order_number',
                    'total_messages',
                    'messages',
                ],
            ])
            ->assertJsonPath('data.total_messages', 2);
    }

    /** @test */
    public function can_mark_message_as_read()
    {
        $message = OrderMessage::create([
            'order_id' => $this->order->id,
            'sender_type' => 'driver',
            'sender_id' => $this->driver->id,
            'message' => 'رسالة للقراءة',
            'is_read' => false,
        ]);

        $response = $this->postJson("/api/v1/salla-delivery/chat/mark-read/{$message->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('order_messages', [
            'id' => $message->id,
            'is_read' => true,
        ]);
    }

    /** @test */
    public function can_get_unread_count_for_driver()
    {
        // إنشاء رسائل غير مقروءة من المستخدم
        OrderMessage::create([
            'order_id' => $this->order->id,
            'sender_type' => 'user',
            'sender_id' => $this->user->id,
            'message' => 'رسالة 1 غير مقروءة',
            'is_read' => false,
        ]);

        OrderMessage::create([
            'order_id' => $this->order->id,
            'sender_type' => 'user',
            'sender_id' => $this->user->id,
            'message' => 'رسالة 2 غير مقروءة',
            'is_read' => false,
        ]);

        $response = $this->getJson("/api/v1/salla-delivery/chat/unread-count/{$this->order->id}/driver");

        $response->assertStatus(200)
            ->assertJsonPath('data.unread_count', 2);
    }

    /** @test */
    public function can_get_unread_count_for_user()
    {
        // إنشاء رسالة غير مقروءة من السائق
        OrderMessage::create([
            'order_id' => $this->order->id,
            'sender_type' => 'driver',
            'sender_id' => $this->driver->id,
            'message' => 'رسالة من السائق',
            'is_read' => false,
        ]);

        $response = $this->getJson("/api/v1/salla-delivery/chat/unread-count/{$this->order->id}/user");

        $response->assertStatus(200)
            ->assertJsonPath('data.unread_count', 1);
    }

    /** @test */
    public function can_update_fcm_token_for_user()
    {
        $newToken = 'new_fcm_token_789';

        $response = $this->postJson('/api/v1/salla-delivery/chat/update-fcm-token', [
            'user_type' => 'user',
            'user_id' => $this->user->id,
            'fcm_token' => $newToken,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'fcm_token' => $newToken,
        ]);
    }

    /** @test */
    public function can_update_fcm_token_for_driver()
    {
        $newToken = 'new_driver_fcm_token_999';

        $response = $this->postJson('/api/v1/salla-delivery/chat/update-fcm-token', [
            'user_type' => 'driver',
            'user_id' => $this->driver->id,
            'fcm_token' => $newToken,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('drivers', [
            'id' => $this->driver->id,
            'fcm_token' => $newToken,
        ]);
    }

    /** @test */
    public function message_must_have_required_fields()
    {
        $response = $this->postJson("/api/v1/salla-delivery/chat/send/{$this->order->id}", [
            // بدون message
            'sender_type' => 'user',
            'sender_id' => $this->user->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['message']);
    }

    /** @test */
    public function sender_type_must_be_valid()
    {
        $response = $this->postJson("/api/v1/salla-delivery/chat/send/{$this->order->id}", [
            'message' => 'رسالة تجريبية',
            'sender_type' => 'invalid_type',
            'sender_id' => $this->user->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['sender_type']);
    }

    /** @test */
    public function message_can_determine_correct_recipient()
    {
        $message = OrderMessage::create([
            'order_id' => $this->order->id,
            'sender_type' => 'user',
            'sender_id' => $this->user->id,
            'message' => 'رسالة من المستخدم',
        ]);

        $recipient = $message->getRecipient();

        $this->assertEquals('driver', $recipient['type']);
        $this->assertEquals($this->driver->id, $recipient['id']);
    }
}
