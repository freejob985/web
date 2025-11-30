/**
 * Simple Icon Picker using SweetAlert2
 * No external dependencies required
 */

(function() {
    'use strict';
    
    // مجموعة شاملة من أيقونات Font Awesome
    const FONT_AWESOME_ICONS = [
        // Shopping & E-commerce (20)
        { class: 'fas fa-shopping-cart', name: 'سلة التسوق' },
        { class: 'fas fa-shopping-bag', name: 'حقيبة تسوق' },
        { class: 'fas fa-shopping-basket', name: 'سلة' },
        { class: 'fas fa-store', name: 'متجر' },
        { class: 'fas fa-credit-card', name: 'بطاقة ائتمان' },
        { class: 'fas fa-money-bill-wave', name: 'نقود' },
        { class: 'fas fa-coins', name: 'عملات' },
        { class: 'fas fa-wallet', name: 'محفظة' },
        { class: 'fas fa-cash-register', name: 'كاشير' },
        { class: 'fas fa-receipt', name: 'فاتورة' },
        { class: 'fas fa-barcode', name: 'باركود' },
        { class: 'fas fa-qrcode', name: 'QR كود' },
        { class: 'fas fa-percentage', name: 'نسبة مئوية' },
        { class: 'fas fa-tags', name: 'وسوم' },
        { class: 'fas fa-tag', name: 'وسم' },
        { class: 'fas fa-gift', name: 'هدية' },
        { class: 'fas fa-ticket-alt', name: 'تذكرة' },
        
        // Products & Items (10)
        { class: 'fas fa-box', name: 'صندوق' },
        { class: 'fas fa-boxes', name: 'صناديق' },
        { class: 'fas fa-cube', name: 'مكعب' },
        { class: 'fas fa-cubes', name: 'مكعبات' },
        { class: 'fas fa-archive', name: 'أرشيف' },
        { class: 'fas fa-star', name: 'نجمة' },
        { class: 'fas fa-certificate', name: 'شهادة' },
        { class: 'fas fa-award', name: 'جائزة' },
        { class: 'fas fa-medal', name: 'ميدالية' },
        { class: 'fas fa-gem', name: 'جوهرة' },
        
        // Food & Beverages (30)
        { class: 'fas fa-apple-alt', name: 'تفاح' },
        { class: 'fas fa-lemon', name: 'ليمون' },
        { class: 'fas fa-carrot', name: 'جزر' },
        { class: 'fas fa-pepper-hot', name: 'فلفل حار' },
        { class: 'fas fa-drumstick-bite', name: 'دجاج' },
        { class: 'fas fa-hamburger', name: 'برجر' },
        { class: 'fas fa-pizza-slice', name: 'بيتزا' },
        { class: 'fas fa-ice-cream', name: 'آيس كريم' },
        { class: 'fas fa-cookie', name: 'بسكويت' },
        { class: 'fas fa-birthday-cake', name: 'كعكة' },
        { class: 'fas fa-bread-slice', name: 'خبز' },
        { class: 'fas fa-cheese', name: 'جبن' },
        { class: 'fas fa-fish', name: 'سمك' },
        { class: 'fas fa-egg', name: 'بيض' },
        { class: 'fas fa-bacon', name: 'لحم مقدد' },
        { class: 'fas fa-hotdog', name: 'هوت دوج' },
        { class: 'fas fa-wine-bottle', name: 'زجاجة نبيذ' },
        { class: 'fas fa-wine-glass', name: 'كأس نبيذ' },
        { class: 'fas fa-beer', name: 'بيرة' },
        { class: 'fas fa-coffee', name: 'قهوة' },
        { class: 'fas fa-mug-hot', name: 'كوب ساخن' },
        { class: 'fas fa-glass-whiskey', name: 'ويسكي' },
        { class: 'fas fa-cocktail', name: 'كوكتيل' },
        { class: 'fas fa-glass-martini', name: 'مارتيني' },
        { class: 'fas fa-utensils', name: 'أدوات طعام' },
        { class: 'fas fa-blender', name: 'خلاط' },
        
        // User & People (15)
        { class: 'fas fa-user', name: 'مستخدم' },
        { class: 'fas fa-users', name: 'مستخدمون' },
        { class: 'fas fa-user-circle', name: 'مستخدم دائري' },
        { class: 'fas fa-user-shield', name: 'مستخدم محمي' },
        { class: 'fas fa-user-check', name: 'مستخدم موافق' },
        { class: 'fas fa-user-plus', name: 'إضافة مستخدم' },
        { class: 'fas fa-user-friends', name: 'أصدقاء' },
        { class: 'fas fa-user-tie', name: 'رجل أعمال' },
        { class: 'fas fa-user-ninja', name: 'نينجا' },
        { class: 'fas fa-user-astronaut', name: 'رائد فضاء' },
        { class: 'fas fa-user-graduate', name: 'خريج' },
        { class: 'fas fa-user-md', name: 'طبيب' },
        { class: 'fas fa-user-nurse', name: 'ممرضة' },
        { class: 'fas fa-user-injured', name: 'مصاب' },
        { class: 'fas fa-id-card', name: 'بطاقة هوية' },
        
        // Communication (15)
        { class: 'fas fa-envelope', name: 'بريد' },
        { class: 'fas fa-phone', name: 'هاتف' },
        { class: 'fas fa-mobile-alt', name: 'موبايل' },
        { class: 'fas fa-comment', name: 'تعليق' },
        { class: 'fas fa-comments', name: 'تعليقات' },
        { class: 'fas fa-bell', name: 'جرس' },
        { class: 'fas fa-bullhorn', name: 'بوق' },
        { class: 'fas fa-paper-plane', name: 'طائرة ورقية' },
        { class: 'fas fa-inbox', name: 'صندوق وارد' },
        { class: 'fas fa-fax', name: 'فاكس' },
        { class: 'fas fa-at', name: 'رمز @' },
        { class: 'fas fa-hashtag', name: 'هاشتاق' },
        { class: 'fas fa-sms', name: 'رسالة نصية' },
        { class: 'fas fa-voicemail', name: 'بريد صوتي' },
        { class: 'fas fa-phone-volume', name: 'صوت الهاتف' },
        
        // Navigation & Actions (20)
        { class: 'fas fa-home', name: 'رئيسية' },
        { class: 'fas fa-search', name: 'بحث' },
        { class: 'fas fa-cog', name: 'إعدادات' },
        { class: 'fas fa-heart', name: 'قلب' },
        { class: 'fas fa-bookmark', name: 'مرجعية' },
        { class: 'fas fa-share', name: 'مشاركة' },
        { class: 'fas fa-download', name: 'تحميل' },
        { class: 'fas fa-upload', name: 'رفع' },
        { class: 'fas fa-edit', name: 'تعديل' },
        { class: 'fas fa-trash', name: 'حذف' },
        { class: 'fas fa-plus', name: 'إضافة' },
        { class: 'fas fa-minus', name: 'طرح' },
        { class: 'fas fa-check', name: 'صح' },
        { class: 'fas fa-times', name: 'خطأ' },
        { class: 'fas fa-info-circle', name: 'معلومات' },
        { class: 'fas fa-exclamation-triangle', name: 'تحذير' },
        { class: 'fas fa-question-circle', name: 'سؤال' },
        { class: 'fas fa-thumbs-up', name: 'إعجاب' },
        { class: 'fas fa-thumbs-down', name: 'عدم إعجاب' },
        { class: 'fas fa-power-off', name: 'إيقاف' },
        
        // Arrows (12)
        { class: 'fas fa-arrow-right', name: 'سهم يمين' },
        { class: 'fas fa-arrow-left', name: 'سهم يسار' },
        { class: 'fas fa-arrow-up', name: 'سهم أعلى' },
        { class: 'fas fa-arrow-down', name: 'سهم أسفل' },
        { class: 'fas fa-chevron-right', name: 'شيفرون يمين' },
        { class: 'fas fa-chevron-left', name: 'شيفرون يسار' },
        { class: 'fas fa-chevron-up', name: 'شيفرون أعلى' },
        { class: 'fas fa-chevron-down', name: 'شيفرون أسفل' },
        { class: 'fas fa-angle-right', name: 'زاوية يمين' },
        { class: 'fas fa-angle-left', name: 'زاوية يسار' },
        { class: 'fas fa-angle-up', name: 'زاوية أعلى' },
        { class: 'fas fa-angle-down', name: 'زاوية أسفل' },
        
        // Media & Files (15)
        { class: 'fas fa-image', name: 'صورة' },
        { class: 'fas fa-images', name: 'صور' },
        { class: 'fas fa-camera', name: 'كاميرا' },
        { class: 'fas fa-video', name: 'فيديو' },
        { class: 'fas fa-film', name: 'فيلم' },
        { class: 'fas fa-music', name: 'موسيقى' },
        { class: 'fas fa-file', name: 'ملف' },
        { class: 'fas fa-folder', name: 'مجلد' },
        { class: 'fas fa-file-alt', name: 'ملف نصي' },
        { class: 'fas fa-file-pdf', name: 'PDF' },
        { class: 'fas fa-file-word', name: 'Word' },
        { class: 'fas fa-file-excel', name: 'Excel' },
        { class: 'fas fa-file-powerpoint', name: 'PowerPoint' },
        { class: 'fas fa-file-image', name: 'ملف صورة' },
        { class: 'fas fa-file-video', name: 'ملف فيديو' },
        
        // Business & Office (15)
        { class: 'fas fa-briefcase', name: 'حقيبة' },
        { class: 'fas fa-building', name: 'مبنى' },
        { class: 'fas fa-city', name: 'مدينة' },
        { class: 'fas fa-industry', name: 'صناعة' },
        { class: 'fas fa-chart-line', name: 'رسم بياني خطي' },
        { class: 'fas fa-chart-bar', name: 'رسم بياني شريطي' },
        { class: 'fas fa-chart-pie', name: 'رسم بياني دائري' },
        { class: 'fas fa-calculator', name: 'آلة حاسبة' },
        { class: 'fas fa-clipboard', name: 'حافظة' },
        { class: 'fas fa-calendar', name: 'تقويم' },
        { class: 'fas fa-clock', name: 'ساعة' },
        { class: 'fas fa-tasks', name: 'مهام' },
        { class: 'fas fa-project-diagram', name: 'مخطط مشروع' },
        { class: 'fas fa-balance-scale', name: 'ميزان' },
        { class: 'fas fa-gavel', name: 'مطرقة قاضي' },
        
        // Technology (20)
        { class: 'fas fa-desktop', name: 'سطح مكتب' },
        { class: 'fas fa-laptop', name: 'لابتوب' },
        { class: 'fas fa-tablet-alt', name: 'تابلت' },
        { class: 'fas fa-mobile-alt', name: 'موبايل' },
        { class: 'fas fa-tv', name: 'تلفاز' },
        { class: 'fas fa-keyboard', name: 'لوحة مفاتيح' },
        { class: 'fas fa-mouse', name: 'فأرة' },
        { class: 'fas fa-headphones', name: 'سماعات' },
        { class: 'fas fa-wifi', name: 'واي فاي' },
        { class: 'fas fa-database', name: 'قاعدة بيانات' },
        { class: 'fas fa-server', name: 'خادم' },
        { class: 'fas fa-cloud', name: 'سحابة' },
        { class: 'fas fa-microchip', name: 'معالج' },
        { class: 'fas fa-memory', name: 'ذاكرة' },
        { class: 'fas fa-hdd', name: 'قرص صلب' },
        { class: 'fas fa-usb-drive', name: 'USB' },
        { class: 'fas fa-plug', name: 'قابس' },
        { class: 'fas fa-battery-full', name: 'بطارية ممتلئة' },
        { class: 'fas fa-power-off', name: 'إيقاف' },
        { class: 'fas fa-robot', name: 'روبوت' },
        
        // Transportation (15)
        { class: 'fas fa-car', name: 'سيارة' },
        { class: 'fas fa-truck', name: 'شاحنة' },
        { class: 'fas fa-motorcycle', name: 'دراجة نارية' },
        { class: 'fas fa-bicycle', name: 'دراجة' },
        { class: 'fas fa-bus', name: 'حافلة' },
        { class: 'fas fa-taxi', name: 'تاكسي' },
        { class: 'fas fa-plane', name: 'طائرة' },
        { class: 'fas fa-ship', name: 'سفينة' },
        { class: 'fas fa-rocket', name: 'صاروخ' },
        { class: 'fas fa-helicopter', name: 'هليكوبتر' },
        { class: 'fas fa-train', name: 'قطار' },
        { class: 'fas fa-subway', name: 'مترو' },
        { class: 'fas fa-tram', name: 'ترام' },
        { class: 'fas fa-anchor', name: 'مرساة' },
        { class: 'fas fa-shuttle-van', name: 'فان' },
        
        // Location (10)
        { class: 'fas fa-map-marker-alt', name: 'علامة موقع' },
        { class: 'fas fa-map', name: 'خريطة' },
        { class: 'fas fa-globe', name: 'كرة أرضية' },
        { class: 'fas fa-compass', name: 'بوصلة' },
        { class: 'fas fa-location-arrow', name: 'سهم موقع' },
        { class: 'fas fa-route', name: 'مسار' },
        { class: 'fas fa-directions', name: 'اتجاهات' },
        { class: 'fas fa-map-pin', name: 'دبوس خريطة' },
        { class: 'fas fa-street-view', name: 'عرض الشارع' },
        { class: 'fas fa-map-signs', name: 'لافتات' },
        
        // Weather & Nature (15)
        { class: 'fas fa-sun', name: 'شمس' },
        { class: 'fas fa-moon', name: 'قمر' },
        { class: 'fas fa-cloud', name: 'سحابة' },
        { class: 'fas fa-cloud-rain', name: 'مطر' },
        { class: 'fas fa-snowflake', name: 'ثلج' },
        { class: 'fas fa-bolt', name: 'برق' },
        { class: 'fas fa-wind', name: 'رياح' },
        { class: 'fas fa-leaf', name: 'ورقة' },
        { class: 'fas fa-tree', name: 'شجرة' },
        { class: 'fas fa-seedling', name: 'شتلة' },
        { class: 'fas fa-spa', name: 'سبا' },
        { class: 'fas fa-mountain', name: 'جبل' },
        { class: 'fas fa-water', name: 'ماء' },
        { class: 'fas fa-fire', name: 'نار' },
        { class: 'fas fa-rainbow', name: 'قوس قزح' },
        
        // Health & Medical (15)
        { class: 'fas fa-heart', name: 'قلب' },
        { class: 'fas fa-heartbeat', name: 'نبض' },
        { class: 'fas fa-hospital', name: 'مستشفى' },
        { class: 'fas fa-ambulance', name: 'إسعاف' },
        { class: 'fas fa-medkit', name: 'حقيبة طبية' },
        { class: 'fas fa-stethoscope', name: 'سماعة طبيب' },
        { class: 'fas fa-pills', name: 'حبوب' },
        { class: 'fas fa-syringe', name: 'حقنة' },
        { class: 'fas fa-thermometer', name: 'ميزان حرارة' },
        { class: 'fas fa-band-aid', name: 'ضمادة' },
        { class: 'fas fa-tooth', name: 'سن' },
        { class: 'fas fa-dna', name: 'DNA' },
        { class: 'fas fa-virus', name: 'فيروس' },
        { class: 'fas fa-microscope', name: 'ميكروسكوب' },
        { class: 'fas fa-x-ray', name: 'أشعة' },
        
        // Sports (10)
        { class: 'fas fa-futbol', name: 'كرة قدم' },
        { class: 'fas fa-basketball-ball', name: 'كرة سلة' },
        { class: 'fas fa-football-ball', name: 'كرة قدم أمريكية' },
        { class: 'fas fa-baseball-ball', name: 'بيسبول' },
        { class: 'fas fa-volleyball-ball', name: 'كرة طائرة' },
        { class: 'fas fa-golf-ball', name: 'جولف' },
        { class: 'fas fa-table-tennis', name: 'تنس طاولة' },
        { class: 'fas fa-dumbbell', name: 'دمبل' },
        { class: 'fas fa-running', name: 'جري' },
        { class: 'fas fa-biking', name: 'ركوب دراجة' },
        
        // Tools (10)
        { class: 'fas fa-tools', name: 'أدوات' },
        { class: 'fas fa-wrench', name: 'مفتاح ربط' },
        { class: 'fas fa-hammer', name: 'مطرقة' },
        { class: 'fas fa-screwdriver', name: 'مفك' },
        { class: 'fas fa-toolbox', name: 'صندوق عدة' },
        { class: 'fas fa-paint-brush', name: 'فرشاة' },
        { class: 'fas fa-paint-roller', name: 'رول دهان' },
        { class: 'fas fa-ruler', name: 'مسطرة' },
        { class: 'fas fa-cogs', name: 'تروس' },
        { class: 'fas fa-magic', name: 'عصا سحرية' }
    ];
    
    // دالة البحث عن الأيقونات
    function searchIcons(searchTerm) {
        if (!searchTerm || searchTerm.trim() === '') {
            return FONT_AWESOME_ICONS;
        }
        
        searchTerm = searchTerm.toLowerCase().trim();
        return FONT_AWESOME_ICONS.filter(icon => {
            return icon.class.toLowerCase().includes(searchTerm) ||
                   icon.name.toLowerCase().includes(searchTerm);
        });
    }
    
    // دالة إنشاء HTML للأيقونات
    function generateIconsHTML(icons) {
        if (icons.length === 0) {
            return `
                <div style="text-align: center; padding: 40px; color: #6c757d;">
                    <i class="fas fa-search" style="font-size: 3rem; opacity: 0.5; margin-bottom: 15px;"></i>
                    <h5>لم يتم العثور على أيقونات</h5>
                    <p>جرب كلمات بحث مختلفة</p>
                </div>
            `;
        }
        
        let html = '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; max-height: 400px; overflow-y: auto; padding: 10px;">';
        
        icons.forEach(icon => {
            html += `
                <div class="icon-picker-item" data-icon="${icon.class}" style="
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    padding: 15px 10px;
                    border: 2px solid #dee2e6;
                    border-radius: 10px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    background: white;
                    text-align: center;
                " onmouseover="this.style.borderColor='#667eea'; this.style.transform='translateY(-5px)'; this.style.boxShadow='0 5px 15px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.borderColor='#dee2e6'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <i class="${icon.class}" style="font-size: 2rem; margin-bottom: 8px; color: #495057;"></i>
                    <span style="font-size: 0.7rem; color: #6c757d;">${icon.name}</span>
                </div>
            `;
        });
        
        html += '</div>';
        return html;
    }
    
    // دالة فتح مكتبة الأيقونات
    window.openIconPicker = function(inputId, previewId) {
        let currentSearch = '';
        let iconsHTML = generateIconsHTML(FONT_AWESOME_ICONS);
        
        Swal.fire({
            title: '<i class="fas fa-icons"></i> اختيار أيقونة Font Awesome',
            html: `
                <div style="margin-bottom: 20px;">
                    <input type="text" id="iconSearchInput" class="swal2-input" placeholder="ابحث عن أيقونة... (مثال: cart, user, home)" style="margin: 0; width: 95%;">
                </div>
                <div id="iconsContainer">
                    ${iconsHTML}
                </div>
            `,
            width: '90%',
            showCancelButton: true,
            showConfirmButton: false,
            cancelButtonText: 'إلغاء',
            didOpen: () => {
                const searchInput = document.getElementById('iconSearchInput');
                const iconsContainer = document.getElementById('iconsContainer');
                
                // مستمع البحث
                searchInput.addEventListener('input', function(e) {
                    currentSearch = e.target.value;
                    const filteredIcons = searchIcons(currentSearch);
                    iconsContainer.innerHTML = generateIconsHTML(filteredIcons);
                    
                    // إعادة إضافة مستمعات النقر
                    addIconClickListeners(inputId, previewId);
                });
                
                // إضافة مستمعات النقر على الأيقونات
                addIconClickListeners(inputId, previewId);
                
                // تركيز على حقل البحث
                searchInput.focus();
            },
            customClass: {
                container: 'icon-picker-modal',
                popup: 'icon-picker-popup',
                title: 'icon-picker-title'
            }
        });
    };
    
    // دالة إضافة مستمعات النقر على الأيقونات
    function addIconClickListeners(inputId, previewId) {
        document.querySelectorAll('.icon-picker-item').forEach(item => {
            item.addEventListener('click', function() {
                const iconClass = this.getAttribute('data-icon');
                const input = document.getElementById(inputId);
                const preview = document.getElementById(previewId);
                
                if (input) {
                    input.value = iconClass;
                }
                
                if (preview) {
                    preview.innerHTML = `<i class="${iconClass}"></i>`;
                }
                
                // إغلاق المودل
                Swal.close();
                
                // إظهار رسالة نجاح
                if (typeof Toastify !== 'undefined') {
                    Toastify({
                        text: "✓ تم اختيار الأيقونة بنجاح",
                        duration: 2000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
                        stopOnFocus: true
                    }).showToast();
                }
            });
        });
    }
    
    console.log('✅ Icon Picker Simple loaded successfully with', FONT_AWESOME_ICONS.length, 'icons');
})();
