<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StaticPage;

class StaticPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'terms',
                'title' => 'شروط الخدمة',
                'content' => '<div class="prose max-w-none">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">شروط الخدمة</h1>
                    
                    <div class="space-y-6">
                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">1. قبول الشروط</h2>
                            <p class="text-gray-600 leading-relaxed">
                                بوصولك واستخدامك لموقعنا الإلكتروني، فإنك توافق على الالتزام بشروط الخدمة هذه. 
                                إذا كنت لا توافق على أي جزء من هذه الشروط، فيرجى عدم استخدام موقعنا.
                            </p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">2. وصف الخدمة</h2>
                            <p class="text-gray-600 leading-relaxed">
                                موقعنا يوفر منصة للتسوق الإلكتروني تسمح للمستخدمين بشراء المنتجات من مختلف البائعين. 
                                نحن نعمل كوسيط بين المشترين والبائعين لتسهيل عملية التسوق.
                            </p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">3. حسابات المستخدمين</h2>
                            <p class="text-gray-600 leading-relaxed">
                                عند إنشاء حساب على موقعنا، يجب عليك تقديم معلومات دقيقة وحديثة. 
                                أنت مسؤول عن الحفاظ على سرية كلمة المرور الخاصة بك ومسؤول عن جميع الأنشطة 
                                التي تحدث تحت حسابك.
                            </p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">4. المنتجات والخدمات</h2>
                            <p class="text-gray-600 leading-relaxed">
                                نحن لا نضمن دقة أو اكتمال أو موثوقية أي محتوى أو منتجات أو خدمات مقدمة من البائعين. 
                                البائعون مسؤولون عن وصف منتجاتهم بدقة والتأكد من جودتها.
                            </p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">5. المدفوعات والاسترداد</h2>
                            <p class="text-gray-600 leading-relaxed">
                                جميع المدفوعات تتم عبر قنوات آمنة. سياسة الاسترداد تختلف حسب البائع. 
                                يرجى مراجعة سياسة الاسترداد الخاصة بكل بائع قبل إتمام عملية الشراء.
                            </p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">6. المسؤولية</h2>
                            <p class="text-gray-600 leading-relaxed">
                                نحن لا نتحمل المسؤولية عن أي أضرار مباشرة أو غير مباشرة قد تنتج عن استخدام موقعنا 
                                أو المنتجات المباعة من خلاله. استخدامك للموقع على مسؤوليتك الخاصة.
                            </p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">7. التعديلات</h2>
                            <p class="text-gray-600 leading-relaxed">
                                نحتفظ بالحق في تعديل هذه الشروط في أي وقت. التعديلات ستصبح فعالة فور نشرها على الموقع. 
                                استمرارك في استخدام الموقع بعد التعديلات يعني موافقتك على الشروط الجديدة.
                            </p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">8. الاتصال بنا</h2>
                            <p class="text-gray-600 leading-relaxed">
                                إذا كان لديك أي أسئلة حول شروط الخدمة هذه، يرجى الاتصال بنا عبر:
                                <br>البريد الإلكتروني: support@engeb.com
                                <br>الهاتف: +966 50 123 4567
                            </p>
                        </section>
                    </div>
                </div>',
                'meta_description' => 'شروط الخدمة لموقع إنجب - تعرف على القوانين والشروط التي تحكم استخدامك لموقعنا الإلكتروني',
                'meta_keywords' => 'شروط الخدمة, إنجب, موقع إلكتروني, تسوق, شروط الاستخدام',
                'is_active' => true,
                'is_fixed' => true,
                'sort_order' => 1
            ],
            [
                'slug' => 'privacy',
                'title' => 'سياسة الخصوصية',
                'content' => '<div class="prose max-w-none">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">سياسة الخصوصية</h1>
                    
                    <div class="space-y-6">
                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">1. مقدمة</h2>
                            <p class="text-gray-600 leading-relaxed">
                                نحن في إنجب نحترم خصوصيتك ونلتزم بحماية معلوماتك الشخصية. 
                                تشرح هذه السياسة كيفية جمع واستخدام وحماية معلوماتك عند استخدام موقعنا.
                            </p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">2. المعلومات التي نجمعها</h2>
                            <div class="text-gray-600 leading-relaxed space-y-3">
                                <p>نجمع المعلومات التالية:</p>
                                <ul class="list-disc list-inside space-y-2">
                                    <li>المعلومات الشخصية (الاسم، البريد الإلكتروني، رقم الهاتف)</li>
                                    <li>معلومات العنوان للتسليم</li>
                                    <li>معلومات الدفع (محفوظة بشكل آمن ومشفر)</li>
                                    <li>معلومات الاستخدام والتفضيلات</li>
                                    <li>ملفات تعريف الارتباط (Cookies)</li>
                                </ul>
                            </div>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">3. كيفية استخدام المعلومات</h2>
                            <div class="text-gray-600 leading-relaxed space-y-3">
                                <p>نستخدم معلوماتك لـ:</p>
                                <ul class="list-disc list-inside space-y-2">
                                    <li>معالجة الطلبات وتقديم الخدمات</li>
                                    <li>تحسين تجربة المستخدم</li>
                                    <li>إرسال التحديثات والعروض الخاصة</li>
                                    <li>تحليل الاستخدام وتحسين الموقع</li>
                                    <li>الامتثال للقوانين واللوائح</li>
                                </ul>
                            </div>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">4. مشاركة المعلومات</h2>
                            <p class="text-gray-600 leading-relaxed">
                                نحن لا نبيع أو نؤجر معلوماتك الشخصية لأطراف ثالثة. قد نشارك معلوماتك فقط مع:
                                <br>• مقدمي الخدمات الموثوقين الذين يساعدوننا في تشغيل الموقع
                                <br>• البائعين لمعالجة طلباتك
                                <br>• السلطات المختصة عند الحاجة القانونية
                            </p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">5. حماية البيانات</h2>
                            <p class="text-gray-600 leading-relaxed">
                                نستخدم تقنيات أمان متقدمة لحماية معلوماتك الشخصية من الوصول غير المصرح به 
                                أو التعديل أو الكشف أو التدمير. جميع المعاملات المالية محمية بتشفير SSL.
                            </p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">6. ملفات تعريف الارتباط</h2>
                            <p class="text-gray-600 leading-relaxed">
                                نستخدم ملفات تعريف الارتباط لتحسين تجربتك على موقعنا. يمكنك التحكم في ملفات 
                                تعريف الارتباط من خلال إعدادات المتصفح، لكن تعطيلها قد يؤثر على وظائف الموقع.
                            </p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">7. حقوقك</h2>
                            <div class="text-gray-600 leading-relaxed space-y-3">
                                <p>لديك الحق في:</p>
                                <ul class="list-disc list-inside space-y-2">
                                    <li>الوصول إلى معلوماتك الشخصية</li>
                                    <li>تصحيح المعلومات غير الدقيقة</li>
                                    <li>حذف حسابك ومعلوماتك</li>
                                    <li>سحب الموافقة على معالجة البيانات</li>
                                    <li>الاعتراض على معالجة معينة للبيانات</li>
                                </ul>
                            </div>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">8. التحديثات</h2>
                            <p class="text-gray-600 leading-relaxed">
                                قد نقوم بتحديث سياسة الخصوصية هذه من وقت لآخر. سنقوم بإشعارك بأي تغييرات مهمة 
                                عبر البريد الإلكتروني أو إشعار على الموقع.
                            </p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">9. الاتصال بنا</h2>
                            <p class="text-gray-600 leading-relaxed">
                                إذا كان لديك أي أسئلة حول سياسة الخصوصية هذه، يرجى الاتصال بنا:
                                <br>البريد الإلكتروني: privacy@engeb.com
                                <br>الهاتف: +966 50 123 4567
                                <br>العنوان: المملكة العربية السعودية
                            </p>
                        </section>
                    </div>
                </div>',
                'meta_description' => 'سياسة الخصوصية لموقع إنجب - تعرف على كيفية حماية معلوماتك الشخصية وخصوصيتك',
                'meta_keywords' => 'سياسة الخصوصية, إنجب, حماية البيانات, الخصوصية, معلومات شخصية',
                'is_active' => true,
                'is_fixed' => true,
                'sort_order' => 2
            ],
            [
                'slug' => 'refund',
                'title' => 'سياسة الاسترداد',
                'content' => '<div class="prose max-w-none">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">سياسة الاسترداد</h1>
                    
                    <div class="space-y-6">
                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">1. مقدمة</h2>
                            <p class="text-gray-600 leading-relaxed">
                                نحن في إنجب نلتزم بتقديم تجربة تسوق متميزة لجميع عملائنا. 
                                تشرح سياسة الاسترداد هذه الشروط والأحكام الخاصة بإرجاع واسترداد المنتجات.
                            </p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">2. شروط الاسترداد</h2>
                            <div class="text-gray-600 leading-relaxed space-y-3">
                                <p>يمكنك طلب استرداد المبلغ في الحالات التالية:</p>
                                <ul class="list-disc list-inside space-y-2">
                                    <li>المنتج تالف أو معيب عند الاستلام</li>
                                    <li>المنتج لا يطابق الوصف المذكور</li>
                                    <li>تم تسليم منتج مختلف عن المطلوب</li>
                                    <li>المنتج لم يصل خلال الفترة المحددة</li>
                                    <li>طلب الإلغاء خلال 24 ساعة من الطلب</li>
                                </ul>
                            </div>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">3. فترة الاسترداد</h2>
                            <div class="text-gray-600 leading-relaxed space-y-3">
                                <p>فترات الاسترداد حسب نوع المنتج:</p>
                                <ul class="list-disc list-inside space-y-2">
                                    <li><strong>المنتجات الطازجة:</strong> خلال 24 ساعة من الاستلام</li>
                                    <li><strong>المنتجات المعلبة:</strong> خلال 7 أيام من الاستلام</li>
                                    <li><strong>المنتجات المجمدة:</strong> خلال 48 ساعة من الاستلام</li>
                                    <li><strong>المنتجات الجافة:</strong> خلال 14 يوم من الاستلام</li>
                                </ul>
                            </div>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">4. إجراءات الاسترداد</h2>
                            <div class="text-gray-600 leading-relaxed space-y-3">
                                <p>لطلب الاسترداد، يرجى اتباع الخطوات التالية:</p>
                                <ol class="list-decimal list-inside space-y-2">
                                    <li>اتصل بنا خلال فترة الاسترداد المحددة</li>
                                    <li>قدم رقم الطلب وسبب الاسترداد</li>
                                    <li>أرسل صور المنتج (إذا كان تالفاً)</li>
                                    <li>احتفظ بالمنتج حتى يتم تأكيد الاسترداد</li>
                                    <li>ستتلقى تأكيد الاسترداد خلال 24 ساعة</li>
                                </ol>
                            </div>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">5. طرق الاسترداد</h2>
                            <div class="text-gray-600 leading-relaxed space-y-3">
                                <p>نقوم بإرجاع المبلغ بالطريقة التالية:</p>
                                <ul class="list-disc list-inside space-y-2">
                                    <li><strong>الدفع الإلكتروني:</strong> إرجاع إلى نفس البطاقة خلال 5-7 أيام عمل</li>
                                    <li><strong>الدفع عند الاستلام:</strong> خصم من المبلغ المستحق</li>
                                    <li><strong>المحفظة الإلكترونية:</strong> إرجاع إلى المحفظة خلال 24 ساعة</li>
                                    <li><strong>التحويل البنكي:</strong> خلال 3-5 أيام عمل</li>
                                </ul>
                            </div>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">6. رسوم الاسترداد</h2>
                            <div class="text-gray-600 leading-relaxed space-y-3">
                                <p>رسوم الاسترداد حسب الحالة:</p>
                                <ul class="list-disc list-inside space-y-2">
                                    <li><strong>خطأ من جانبنا:</strong> استرداد كامل بدون رسوم</li>
                                    <li><strong>طلب العميل (خلال 24 ساعة):</strong> استرداد كامل</li>
                                    <li><strong>طلب العميل (بعد 24 ساعة):</strong> خصم 10% رسوم إدارية</li>
                                    <li><strong>المنتجات المفتوحة:</strong> خصم 20% من قيمة المنتج</li>
                                </ul>
                            </div>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">7. المنتجات المستثناة من الاسترداد</h2>
                            <div class="text-gray-600 leading-relaxed space-y-3">
                                <p>لا يمكن استرداد المنتجات التالية:</p>
                                <ul class="list-disc list-inside space-y-2">
                                    <li>المنتجات المخصصة حسب الطلب</li>
                                    <li>المنتجات القابلة للتلف بعد انتهاء صلاحيتها</li>
                                    <li>المنتجات المفتوحة أو المستخدمة جزئياً</li>
                                    <li>المنتجات التي تم تلفها بسبب سوء الاستخدام</li>
                                    <li>المنتجات الموسمية بعد انتهاء الموسم</li>
                                </ul>
                            </div>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">8. استرداد التكاليف الإضافية</h2>
                            <p class="text-gray-600 leading-relaxed">
                                في حالة الاسترداد بسبب خطأ من جانبنا، نتحمل جميع التكاليف الإضافية مثل:
                                <br>• تكلفة الشحن
                                <br>• رسوم المعالجة
                                <br>• أي رسوم إضافية مرتبطة بالطلب
                            </p>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">9. استبدال المنتجات</h2>
                            <div class="text-gray-600 leading-relaxed space-y-3">
                                <p>بدلاً من الاسترداد، يمكنك اختيار:</p>
                                <ul class="list-disc list-inside space-y-2">
                                    <li>استبدال المنتج بآخر من نفس النوع</li>
                                    <li>استبدال المنتج بمنتج مختلف (مع دفع الفرق)</li>
                                    <li>استبدال المنتج بمنتج من فئة أخرى</li>
                                    <li>استبدال المنتج بمنتج من نفس القيمة</li>
                                </ul>
                            </div>
                        </section>

                        <section>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">10. الاتصال بنا</h2>
                            <p class="text-gray-600 leading-relaxed">
                                لطلب الاسترداد أو الاستفسار عن سياسة الاسترداد، يرجى الاتصال بنا:
                                <br>البريد الإلكتروني: refund@engeb.com
                                <br>الهاتف: +966 50 123 4567
                                <br>واتساب: +966 50 123 4567
                                <br>ساعات العمل: من 8 صباحاً إلى 10 مساءً (السبت - الخميس)
                            </p>
                        </section>
                    </div>
                </div>',
                'meta_description' => 'سياسة الاسترداد لموقع إنجب - تعرف على شروط وأحكام إرجاع واسترداد المنتجات',
                'meta_keywords' => 'سياسة الاسترداد, إنجب, إرجاع المنتجات, استرداد الأموال, ضمان الجودة',
                'is_active' => true,
                'is_fixed' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($pages as $page) {
            StaticPage::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
