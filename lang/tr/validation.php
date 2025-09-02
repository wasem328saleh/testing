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

    'accepted' => ':attribute alanı kabul edilmelidir.',
    'accepted_if' => ':other alanı :value olduğunda :attribute alanı kabul edilmelidir.',
    'active_url' => ':attribute alanı geçerli bir URL olmalıdır.',
    'after' => ':attribute alanı :date tarihinden sonra bir tarih olmalıdır.',
    'after_or_equal' => ':attribute alanı :date tarihinden sonra veya bu tarihe eşit bir tarih olmalıdır.',
    'alpha' => ':attribute alanı yalnızca harfler içerebilir.',
    'alpha_dash' => ':attribute alanı yalnızca harfler, sayılar, tireler ve alt çizgiler içerebilir.',
    'alpha_num' => ':attribute alanı yalnızca harfler ve sayılar içerebilir.',
    'array' => ':attribute alanı bir dizi olmalıdır.',
    'ascii' => ':attribute alanı yalnızca tek baytlı alfasayısal karakterler ve semboller içerebilir.',
    'before' => ':attribute alanı :date tarihinden önce bir tarih olmalıdır.',
    'before_or_equal' => ':attribute alanı :date tarihinden önce veya bu tarihe eşit bir tarih olmalıdır.',
    'between' => [
        'array' => ':attribute alanı :min ile :max öğe arasında olmalıdır.',
        'file' => ':attribute alanı :min ile :max kilobayt arasında olmalıdır.',
        'numeric' => ':attribute alanı :min ile :max arasında olmalıdır.',
        'string' => ':attribute alanı :min ile :max karakter arasında olmalıdır.',
    ],
    'boolean' => ':attribute alanı doğru veya yanlış olmalıdır.',
    'can' => ':attribute alanı yetkisiz bir değer içeriyor.',
    'confirmed' => ':attribute alanı onayı eşleşmiyor.',
    'current_password' => 'Şifre hatalı.',
    'date' => ':attribute alanı geçerli bir tarih olmalıdır.',
    'date_equals' => ':attribute alanı :date tarihine eşit olmalıdır.',
    'date_format' => ':attribute alanı :format biçimi ile eşleşmelidir.',
    'decimal' => ':attribute alanı :decimal ondalık basamağa sahip olmalıdır.',
    'declined' => ':attribute alanı reddedilmelidir.',
    'declined_if' => ':other alanı :value olduğunda :attribute alanı reddedilmelidir.',
    'different' => ':attribute alanı ile :other alanı farklı olmalıdır.',
    'digits' => ':attribute alanı :digits haneli olmalıdır.',
    'digits_between' => ':attribute alanı :min ile :max haneli olmalıdır.',
    'dimensions' => ':attribute alanı geçersiz resim boyutlarına sahip.',
    'distinct' => ':attribute alanı yinelenen bir değere sahip.',
    'doesnt_end_with' => ':attribute alanı şunlardan biri ile bitmemelidir: :values.',
    'doesnt_start_with' => ':attribute alanı şunlardan biri ile başlamamalıdır: :values.',
    'email' => ':attribute alanı geçerli bir e-posta adresi olmalıdır.',
    'ends_with' => ':attribute alanı şunlardan biri ile bitmelidir: :values.',
    'enum' => 'Seçilen :attribute geçersiz.',
    'exists' => 'Seçilen :attribute geçersiz.',
    'extensions' => ':attribute alanı şu uzantılardan birine sahip olmalıdır: :values.',
    'file' => ':attribute alanı bir dosya olmalıdır.',
    'filled' => ':attribute alanının bir değeri olmalıdır.',
    'gt' => [
        'array' => ':attribute alanı :value öğeden fazla olmalıdır.',
        'file' => ':attribute alanı :value kilobayttan büyük olmalıdır.',
        'numeric' => ':attribute alanı :value değerinden büyük olmalıdır.',
        'string' => ':attribute alanı :value karakterden uzun olmalıdır.',
    ],
    'gte' => [
        'array' => ':attribute alanı en az :value öğe içermelidir.',
        'file' => ':attribute alanı en az :value kilobayt olmalıdır.',
        'numeric' => ':attribute alanı en az :value değerinde olmalıdır.',
        'string' => ':attribute alanı en az :value karakter uzunluğunda olmalıdır.',
    ],
    'hex_color' => ':attribute alanı geçerli bir onaltılık renk kodu olmalıdır.',
    'image' => ':attribute alanı bir resim olmalıdır.',
    'in' => 'Seçilen :attribute geçersiz.',
    'in_array' => ':attribute alanı :other içinde mevcut olmalıdır.',
    'integer' => ':attribute alanı bir tamsayı olmalıdır.',
    'ip' => ':attribute alanı geçerli bir IP adresi olmalıdır.',
    'ipv4' => ':attribute alanı geçerli bir IPv4 adresi olmalıdır.',
    'ipv6' => ':attribute alanı geçerli bir IPv6 adresi olmalıdır.',
    'json' => ':attribute alanı geçerli bir JSON dizgesi olmalıdır.',
    'lowercase' => ':attribute alanı küçük harf olmalıdır.',
    'lt' => [
        'array' => ':attribute alanı :value öğeden az olmalıdır.',
        'file' => ':attribute alanı :value kilobayttan az olmalıdır.',
        'numeric' => ':attribute alanı :value değerinden az olmalıdır.',
        'string' => ':attribute alanı :value karakterden kısa olmalıdır.',
    ],
    'lte' => [
        'array' => ':attribute alanı en fazla :value öğe içermelidir.',
        'file' => ':attribute alanı en fazla :value kilobayt olmalıdır.',
        'numeric' => ':attribute alanı en fazla :value değerinde olmalıdır.',
        'string' => ':attribute alanı en fazla :value karakter uzunluğunda olmalıdır.',
    ],
    'mac_address' => ':attribute alanı geçerli bir MAC adresi olmalıdır.',
    'max' => [
        'array' => ':attribute alanı en fazla :max öğe içerebilir.',
        'file' => ':attribute alanı en fazla :max kilobayt olabilir.',
        'numeric' => ':attribute alanı en fazla :max değerinde olabilir.',
        'string' => ':attribute alanı en fazla :max karakter uzunluğunda olabilir.',
    ],
    'max_digits' => ':attribute alanı en fazla :max haneli olmalıdır.',
    'mimes' => ':attribute alanı :values türünde bir dosya olmalıdır.',
    'mimetypes' => ':attribute alanı :values türünde bir dosya olmalıdır.',
    'min' => [
        'array' => ':attribute alanı en az :min öğe içermelidir.',
        'file' => ':attribute alanı en az :min kilobayt olmalıdır.',
        'numeric' => ':attribute alanı en az :min değerinde olmalıdır.',
        'string' => ':attribute alanı en az :min karakter uzunluğunda olmalıdır.',
    ],
    'min_digits' => ':attribute alanı en az :min haneli olmalıdır.',
    'missing' => ':attribute alanı eksik olmalıdır.',
    'missing_if' => ':other alanı :value olduğunda :attribute alanı eksik olmalıdır.',
    'missing_unless' => ':other alanı :value olmadığı sürece :attribute alanı eksik olmalıdır.',
    'missing_with' => ':values alanı mevcut olduğunda :attribute alanı eksik olmalıdır.',
    'missing_with_all' => ':values alanları mevcut olduğunda :attribute alanı eksik olmalıdır.',
    'multiple_of' => ':attribute alanı :value değerinin katı olmalıdır.',
    'not_in' => 'Seçilen :attribute geçersiz.',
    'not_regex' => ':attribute alanı formatı geçersiz.',
    'numeric' => ':attribute alanı bir sayı olmalıdır.',
    'password' => [
        'letters' => ':attribute alanı en az bir harf içermelidir.',
        'mixed' => ':attribute alanı en az bir büyük harf ve bir küçük harf içermelidir.',
        'numbers' => ':attribute alanı en az bir rakam içermelidir.',
        'symbols' => ':attribute alanı en az bir sembol içermelidir.',
        'uncompromised' => 'Verilen :attribute bir veri sızıntısında görüldü. Lütfen farklı bir :attribute seçin.',
    ],
    'present' => ':attribute alanı mevcut olmalıdır.',
    'present_if' => ':other alanı :value olduğunda :attribute alanı mevcut olmalıdır.',
    'present_unless' => ':other alanı :value olmadığı sürece :attribute alanı mevcut olmalıdır.',
    'present_with' => ':values alanı mevcut olduğunda :attribute alanı mevcut olmalıdır.',
    'present_with_all' => ':values alanları mevcut olduğunda :attribute alanı mevcut olmalıdır.',
    'prohibited' => ':attribute alanı yasaklanmıştır.',
    'prohibited_if' => ':other alanı :value olduğunda :attribute alanı yasaklanmıştır.',
    'prohibited_unless' => ':other alanı :values içinde olmadığı sürece :attribute alanı yasaklanmıştır.',
    'prohibits' => ':attribute alanı, :other alanının mevcut olmasını yasaklar.',
    'regex' => ':attribute alanı formatı geçersiz.',
    'required' => ':attribute alanı gereklidir.',
    'required_array_keys' => ':attribute alanı şu girdileri içermelidir: :values.',
    'required_if' => ':other alanı :value olduğunda :attribute alanı gereklidir.',
    'required_if_accepted' => ':other alanı kabul edildiğinde :attribute alanı gereklidir.',
    'required_unless' => ':other alanı :values içinde olmadığı sürece :attribute alanı gereklidir.',
    'required_with' => ':values alanı mevcut olduğunda :attribute alanı gereklidir.',
    'required_with_all' => ':values alanları mevcut olduğunda :attribute alanı gereklidir.',
    'required_without' => ':values alanı mevcut olmadığında :attribute alanı gereklidir.',
    'required_without_all' => ':values alanlarından hiçbiri mevcut olmadığında :attribute alanı gereklidir.',
    'same' => ':attribute alanı :other alanı ile eşleşmelidir.',
    'size' => [
        'array' => ':attribute alanı :size öğe içermelidir.',
        'file' => ':attribute alanı :size kilobayt olmalıdır.',
        'numeric' => ':attribute alanı :size olmalıdır.',
        'string' => ':attribute alanı :size karakter olmalıdır.',
    ],
    'starts_with' => ':attribute alanı şunlardan biri ile başlamalıdır: :values.',
    'string' => ':attribute alanı bir metin dizisi olmalıdır.',
    'timezone' => ':attribute alanı geçerli bir saat dilimi olmalıdır.',
    'unique' => ':attribute zaten alınmış.',
    'uploaded' => ':attribute yüklenemedi.',
    'uppercase' => ':attribute alanı büyük harf olmalıdır.',
    'url' => ':attribute alanı geçerli bir URL olmalıdır.',
    'ulid' => ':attribute alanı geçerli bir ULID olmalıdır.',
    'uuid' => ':attribute alanı geçerli bir UUID olmalıdır.',

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
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'classification_id' => 'Classification ID',
        'publication_type' => 'Publication Type',
        'main_category_id' => 'Main Category ID',
        'sub_category_id' => 'Sub Category ID',
        'area' => 'Area',
        'ownership_type_id' => 'Ownership Type ID',
        'price' => 'Price',
        'country_id' => 'Country ID',
        'city_id' => 'City ID',
        'region_id' => 'Region ID',
        'secondary_address' => 'Secondary Address',
        'features' => 'Features',
        'features.*' => 'Feature Item',
        'medias' => 'Images',
        'medias.*' => 'Image',
        'ownership_papers' => 'Ownership Papers',
        'ownership_papers.*' => 'Ownership Paper',
        'directions' => 'Directions',
        'directions.*' => 'Direction',
        'rent_type' => 'Rent Type',
        'description' => 'Description',
        'property_id' => 'Property ID',
        'media_id' => 'Media ID',
        'title' => 'Title',
        'permissions' => 'Permissions',
        'permissions.*' => 'Permission',
        'device_token' => 'Device Token',
        'country_name' => 'Country Name',
        'city_name' => 'City Name',
        'region_name' => 'Region Name',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'email' => 'Email',
        'phone_number' => 'Phone Number',
        'image_profile' => 'Profile Image',
        'identification_papers' => 'Identification Papers',
        'identification_papers.*' => 'Identification Paper Item',
        'user_id' => 'User ID',
        'key' => 'Key',
        'value' => 'Value',
        'info_id' => 'Info ID',
        'category_id' => 'Category ID',
        'role_name' => 'Role Name',
        'validity_period' => 'Validity Period',
        'number_of_advertisements' => 'Number of Advertisements',
        'validity_period_per_advertisement' => 'Validity Period Per Advertisement',
        'user_type' => 'User Type',
        'package_id' => 'Package ID',
        'feature_id' => 'Feature ID',
        'name' => 'Name',
        'code' => 'Code',
        'new_password' => 'New Password',
        'password' => 'Password',
        'complaint_id' => 'Complaint ID',
        'reply' => 'Reply',
        'complaint_image' => 'Complaint Image',
        'language' => 'Language',
        'room_type_id' => 'Room Type ID',
        'direction_id' => 'Direction ID',
        'pledge_type_id' => 'Finish Type ID',
        'image' => 'Image',
        'services' => 'Services',
        'services.*' => 'Service Item',
        'order_id' => 'Order ID',
        'provider_id' => 'Provider ID',
        'role_id' => 'Role ID',
        'subscribe_id' => 'Subscribe ID',
        'advertisement_id' => 'Advertisement ID',
        'score' => 'Score',
        'serial_number' => 'Serial Number',
        'text' => 'Text',
        'favorite_id' => 'Favorite ID',
    ],

];
