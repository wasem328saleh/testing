<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'يجب قبول :attribute.',
    'accepted_if' => 'يجب قبول :attribute عندما :other يكون :value.',
    'active_url' => ':attribute ليس عنوان URL صالح.',
    'after' => ':attribute يجب أن يكون :date أو بعد :date.',

    'after_or_equal' => ':attribute يجب أن يكون تاريخًا بعد أو مساويًا لـ :date.',
    'alpha' => ':attribute يجب أن يحتوي فقط على أحرف.',
    'alpha_dash' => ':attribute يجب أن يحتوي فقط على أحرف وأرقام وشرطات وشرطات سفلية.',
    'alpha_num' => ':attribute يجب أن يحتوي فقط على أحرف وأرقام.',
    'array' => ':attribute يجب أن يكون مصفوفة.',
    'before' => ':attribute يجب أن يكون تاريخًا قبل :date.',
    'before_or_equal' => ':attribute يجب أن يكون تاريخًا قبل أو مساويًا لـ :date.',
    'between' => [
        'array' => ':attribute يجب أن يحتوي بين :min و :max عنصر.',
        'file' => ':attribute يجب أن يكون بين :min و :max كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون بين :min و :max.',
        'string' => ':attribute يجب أن يكون بين :min و :max حرف.',
    ],
    'boolean' => 'يجب أن يكون حقل :attribute صحيحًا أو خاطئًا.',
    'confirmed' => 'تأكيد :attribute لا يتطابق.',
    'current_password' => 'كلمة المرور غير صحيحة.',
    'date' => ':attribute ليس تاريخًا صالحًا.',
    'date_equals' => ':attribute يجب أن يكون تاريخًا مساويًا لـ :date.',
    'date_format' => ':attribute لا يتطابق مع الصيغة :format.',
    'declined' => 'يجب رفض :attribute.',
    'declined_if' => 'يجب رفض :attribute عندما :other يكون :value.',
    'different' => ':attribute و :other يجب أن يكونوا مختلفين.',
    'digits' => ':attribute يجب أن يحتوي على :digits أرقام.',
    'digits_between' => ':attribute يجب أن يكون بين :min و :max أرقام.',
    'dimensions' => ':attribute يحتوي على أبعاد صورة غير صالحة.',
    'distinct' => 'حقل :attribute يحتوي على قيمة مكررة.',
    'doesnt_end_with' => ':attribute قد لا ينتهي بأحد القيم التالية: :values.',
    'doesnt_start_with' => ':attribute قد لا يبدأ بأحد القيم التالية: :values.',
    'email' => ':attribute يجب أن يكون عنوان بريد إلكتروني صالحًا.',
    'ends_with' => ':attribute يجب أن ينتهي بأحد القيم التالية: :values.',
    'enum' => ':attribute المحدد غير صالح.',
    'exists' => ':attribute غير صالح',
    'file' => ':attribute يجب أن يكون ملفًا.',
    'filled' => 'حقل :attribute يجب أن يحتوي على قيمة.',
    'gt' => [
        'array' => ':attribute يجب أن يحتوي على أكثر من :value عنصر.',
        'file' => ':attribute يجب أن يكون أكبر من :value كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون أكبر من :value.',
        'string' => ':attribute يجب أن يكون أكبر من :value حرف.',
    ],
    'gte' => [
        'array' => ':attribute يجب أن يحتوي على :value عنصرًا أو أكثر.',
        'file' => ':attribute يجب أن يكون أكبر من أو مساويًا لـ :value كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون أكبر من أو مساويًا لـ :value.',
        'string' => ':attribute يجب أن يكون أكبر من أو مساويًا لـ :value حرف.',
    ],
    'image' => ':attribute يجب أن يكون صورة.',
    'in' => ':attribute المحدد غير صالح.',
    'in_array' => 'حقل :attribute غير موجود في :other.',
    'integer' => ':attribute يجب أن يكون عددًا صحيحًا.',
    'ip' => ':attribute يجب أن يكون عنوان IP صالحًا.',
    'ipv4' => ':attribute يجب أن يكون عنوان IPv4 صالحًا.',
    'ipv6' => ':attribute يجب أن يكون عنوان IPv6 صالحًا.',
    'json' => ':attribute يجب أن يكون سلسلة JSON صالحة.',
    'lt' => [
        'array' => ':attribute يجب أن يحتوي على أقل من :value عنصر.',
        'file' => ':attribute يجب أن يكون أصغر من :value كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون أصغر من :value.',
        'string' => ':attribute يجب أن يكون أصغر من :value حرف.',
    ],
    'lte' => [
        'array' => ':attribute يجب أن لا يحتوي على أكثر من :value عنصر.',
        'file' => ':attribute يجب أن يكون أصغر من أو مساويًا لـ :value كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون أصغر من أو مساويًا لـ :value.',
        'string' => ':attribute يجب أن يكون أصغر من أو مساويًا لـ :value حرف.',
    ],
    'mac_address' => ':attribute يجب أن يكون عنوان MAC صالحًا.',
    'max' => [
        'array' => ':attribute يجب ألا يحتوي على أكثر من :max عنصر.',
        'file' => ':attribute يجب ألا يكون أكبر من :max كيلوبايت.',
        'numeric' => ':attribute يجب ألا يكون أكبر من :max.',
        'string' => ':attribute يجب ألا يكون أكبر من :max حرف.',
    ],
    'max_digits' => ':attribute يجب ألا يحتوي على أكثر من :max أرقام.',
    'mimes' => ':attribute يجب أن يكون ملفًا من النوع: :values.',
    'mimetypes' => ':attribute يجب أن يكون ملفًا من النوع: :values.',
    'min' => [
        'array' => ':attribute يجب أن يحتوي على ما لا يقل عن :min عنصر.',
        'file' => ':attribute يجب أن يكون على الأقل :min كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون على الأقل :min.',
        'string' => ':attribute يجب أن يكون على الأقل :min حرف.',
    ],
    'min_digits' => ':attribute يجب أن يحتوي على ما لا يقل عن :min أرقام.',
    'multiple_of' => ':attribute يجب أن يكون مضاعفًا لـ :value.',
    'not_in' => ':attribute المحدد غير صالح.',
    'not_regex' => 'صيغة :attribute غير صالحة.',
    'numeric' => ':attribute يجب أن يكون رقمًا.',
    'password' => [
        'letters' => ':attribute يجب أن يحتوي على ما لا يقل عن حرف واحد.',
        'mixed' => ':attribute يجب أن يحتوي على حرف واحد على الأقل من الحروف الكبيرة والصغيرة.',
        'numbers' => ':attribute يجب أن يحتوي على ما لا يقل عن رقم واحد.',
        'symbols' => ':attribute يجب أن يحتوي على ما لا يقل عن رمز واحد.',
        'uncompromised' => ':attribute المُعطى قد ظهر في تسريب بيانات. يُرجى اختيار :attribute مختلفًا.',
    ],
    'present' => 'حقل :attribute يجب أن يكون موجودًا.',
    'prohibited' => 'حقل :attribute ممنوع.',
    'prohibited_if' => 'حقل :attribute ممنوع عندما :other يكون :value.',
    'prohibited_unless' => 'حقل :attribute ممنوع ما لم يكن :other في :values.',
    'prohibits' => 'حقل :attribute يمنع :other من التواجد.',
    'regex' => 'صيغة :attribute غير صالحة.',
    'required' => 'حقل :attribute مطلوب.',
    'required_array_keys' => 'حقل :attribute يجب أن يحتوي على إدخالات لـ :values.',
    'required_if' => 'حقل :attribute مطلوب عندما :other يكون :value.',
    'required_if_accepted' => 'حقل :attribute مطلوب عند قبول :other.',
    'required_unless' => 'حقل :attribute مطلوب ما لم يكن :other في :values.',
    'required_with' => 'حقل :attribute مطلوب عند تواجد :values.',
    'required_with_all' => 'حقل :attribute مطلوب عند تواجد :values.',
    'required_without' => 'حقل :attribute مطلوب عند عدم تواجد :values.',
    'required_without_all' => 'حقل :attribute مطلوب عند عدم تواجد أي من :values.',
    'same' => ':attribute و :other يجب أن يتطابقوا.',
    'size' => [
        'array' => ':attribute يجب أن يحتوي على :size عنصر.',
        'file' => ':attribute يجب أن يكون :size كيلوبايت.',
        'numeric' => ':attribute يجب أن يكون :size.',
        'string' => ':attribute يجب أن يكون :size حرف.',
    ],
    'starts_with' => ':attribute يجب أن يبدأ بأحد القيم التالية: :values.',
    'string' => ':attribute يجب أن يكون نصًا.',
    'timezone' => ':attribute يجب أن يكون منطقة زمنية صالحة.',
    'unique' => ':attribute تم استخدامه بالفعل.',
    'uploaded' => 'فشل تحميل :attribute.',
    'url' => ':attribute يجب أن يكون عنوان URL صالح.',
    'uuid' => ':attribute يجب أن يكون UUID صالحًا.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'cuostm-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader-friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'classification_id' => 'معرف التصنيف',
        'publication_type' => 'نوع النشر',
        'main_category_id' => 'معرف الفئة الرئيسية',
        'sub_category_id' => 'معرف الفئة الفرعية',
        'area' => 'المساحة',
        'ownership_type_id' => 'معرف نوع الملكية',
        'price' => 'السعر',
        'country_id' => 'معرف البلد',
        'city_id' => 'معرف المدينة',
        'region_id' => 'معرف المنطقة',
        'secondary_address' => 'عنوان ثانوي',
        'features' => 'المميزات',
        'features.*' => 'عنصر من المميزات',
        'medias' => 'الصور',
        'medias.*' => 'صورة',
        'ownership_papers' => 'أوراق الملكية',
        'ownership_papers.*' => 'ورقة الملكية',
        'directions' => 'الاتجاهات',
        'directions.*' => 'الاتجاه',
        'rent_type' => 'نوع الإيجار',
        'description' => 'الوصف',
        'property_id' => 'معرف العقار',
        'media_id' => 'معرف الصورة',
        'title' => 'العنوان',
        'permissions' => 'الصلاحيات',
        'permissions.*' => 'الصلاحية',
        'device_token' => 'رمز الجهاز',
        'country_name' => 'اسم البلد',
        'city_name' => 'اسم المدينة',
        'region_name' => 'اسم المنطقة',
        'first_name' => 'الاسم الأول',
        'last_name' => 'اسم العائلة',
        'email' => 'البريد الإلكتروني',
        'phone_number' => 'رقم الهاتف',
        'image_profile' => 'صورة الملف الشخصي',
        'identification_papers' => 'أوراق الهوية',
        'identification_papers.*' => 'عنصر من أوراق الهوية',
        'user_id' => 'معرف المستخدم',
        'key' => 'مفتاح',
        'value' => 'قيمة',
        'info_id' => 'معرف المعلومات',
        'category_id' => 'معرف الفئة',
        'role_name' => 'اسم الدور',
        'validity_period' => 'مدة الصلاحية',
        'number_of_advertisements' => 'عدد الإعلانات',
        'validity_period_per_advertisement' => 'مدة الصلاحية لكل إعلان',
        'user_type' => 'نوع المستخدم',
        'package_id' => 'معرف الباقة',
        'feature_id' => 'معرف الميزة',
        'name' => 'الاسم',
        'code' => 'الكود',
        'new_password' => 'كلمة المرور الجديدة',
        'password' => 'كلمة المرور',
        'complaint_id' => 'معرف الشكوى',
        'reply' => 'الرد',
        'complaint_image' => 'صورة الشكوى',
        'language' => 'اللغة',
        'room_type_id' => 'معرف نوع الغرفة',
        'direction_id' => 'معرف الاتجاه',
        'pledge_type_id' => 'معرف نوع الإكساء',
        'image' => 'صورة',
        'services' => 'الخدمات',
        'services.*' => 'عنصر من الخدمات',
        'order_id' => 'معرف الطلب',
        'provider_id' => 'معرف مقدم الخدمة',
        'role_id' => 'معرف الدور',
        'subscribe_id' => 'معرف الاشتراك',
        'advertisement_id' => 'معرف الإعلان',
        'score' => 'النقاط',
        'serial_number' => 'الرقم التسلسلي',
        'text' => 'النص',
        'favorite_id' => 'معرف المفضلة',
    ],
];
