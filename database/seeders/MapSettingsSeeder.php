<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class MapSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Google Maps settings
        Setting::updateOrCreate(
            ['key' => 'google_map_embed'],
            [
                'value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3477.5!2d47.9784!3d29.3759!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3fcf9c83cf8d5361%3A0x1b8b8b8b8b8b8b8b!2sKuwait%20City%2C%20Kuwait!5e0!3m2!1sen!2skw!4v1234567890123!5m2!1sen!2skw" width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Google Maps Embed',
                'description' => 'Google Maps iframe embed code for contact page',
                'is_public' => true
            ]
        );

        // Map center coordinates
        Setting::updateOrCreate(
            ['key' => 'map_latitude'],
            [
                'value' => '29.3759',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Map Latitude',
                'description' => 'Latitude coordinate for map center',
                'is_public' => true
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'map_longitude'],
            [
                'value' => '47.9784',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Map Longitude',
                'description' => 'Longitude coordinate for map center',
                'is_public' => true
            ]
        );

        // Map zoom level
        Setting::updateOrCreate(
            ['key' => 'map_zoom_level'],
            [
                'value' => '13',
                'type' => 'number',
                'group' => 'general',
                'label' => 'Map Zoom Level',
                'description' => 'Default zoom level for the map (1-20)',
                'is_public' => true
            ]
        );

        // Map marker title
        Setting::updateOrCreate(
            ['key' => 'map_marker_title'],
            [
                'value' => 'إنجب - منصة التسوق الإلكتروني',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Map Marker Title',
                'description' => 'Title displayed on map marker',
                'is_public' => true
            ]
        );

        // Map marker description
        Setting::updateOrCreate(
            ['key' => 'map_marker_description'],
            [
                'value' => 'منصة التسوق الإلكتروني الرائدة في الكويت',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Map Marker Description',
                'description' => 'Description displayed on map marker',
                'is_public' => true
            ]
        );

        // Map height
        Setting::updateOrCreate(
            ['key' => 'map_height'],
            [
                'value' => '500',
                'type' => 'number',
                'group' => 'general',
                'label' => 'Map Height',
                'description' => 'Height of the map in pixels',
                'is_public' => true
            ]
        );

        // Map style
        Setting::updateOrCreate(
            ['key' => 'map_style'],
            [
                'value' => 'default',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Map Style',
                'description' => 'Style of the map (default, satellite, hybrid, terrain)',
                'is_public' => true
            ]
        );

        // Map enabled
        Setting::updateOrCreate(
            ['key' => 'map_enabled'],
            [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'general',
                'label' => 'Map Enabled',
                'description' => 'Enable or disable map display on contact page',
                'is_public' => true
            ]
        );

        $this->command->info('Map settings seeded successfully!');
    }
}
