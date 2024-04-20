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

    'accepted' => 'يجب قبول هذا الحقل',
    'accepted_if' => 'يجب قبول هذا الحقل في حالة :other يساوي :value',
    'active_url' => 'لا يُمثّل رابطًا صحيحًا',
    'after' => 'يجب أن يكون بعد :date',
    'after_or_equal' => 'يجب أن يكون تاريخاً لاحقاً أو مطابقاً للتاريخ :date',
    'alpha' => 'يجب أن لا يحتوي سوى على حروف',
    'alpha_dash' => 'يجب أن لا يحتوي سوى على حروف، أرقام ومطّات',
    'alpha_num' => 'يجب أن يحتوي على حروفٍ وأرقامٍ فقط',
    'array' => 'يجب أن يكون array',
    'ascii' => 'هذا الحقل يدعم فقط الحروف اللاتينية',
    'attached' => 'هذا الحقل تم إرفاقه بالفعل',
    'before' => 'يجب أن يكون قبل :date',
    'before_or_equal' => 'يجب أن يكون تاريخا سابقا أو مطابقا للتاريخ :date',
    'between' => [
        'array' => 'يجب أن يحتوي على عدد من العناصر بين :min و :max',
        'file' => 'يجب أن يكون حجم الملف بين :min و :max كيلوبايت',
        'numeric' => 'يجب أن تكون القيمة بين :min و :max',
        'string' => 'يجب أن يكون عدد حروف النّص بين :min و :max',
    ],
    'boolean' => 'يجب أن تكون قيمة هذا الحقل إما true أو false ',
    'can' => 'هذا الحقل يحتوي على قيمة غير مصرّح بها',
    'confirmed' => 'التأكيد غير متطابق',
    'date' => 'هذا ليس تاريخًا صحيحًا',
    'date_equals' => 'يجب أن يكون تاريخاً مطابقاً للتاريخ :date',
    'date_format' => 'لا يتوافق مع الشكل :format',
    'decimal' => 'يجب أن يحتوي هذا الحقل على :decimal منزلة عشرية',
    'declined' => 'يجب رفض هذه القيمة',
    'declined_if' => 'يجب رفض هذه القيمة في حالة :other هو :value',
    'different' => 'يجب أن تكون القيمة مختلفة عن :other',
    'digits' => 'يجب أن يحتوي على :digits رقم',
    'digits_between' => 'يجب أن يكون بين :min و :max',
    'dimensions' => 'الصورة تحتوي على أبعاد غير صالحة',
    'distinct' => 'هذا الحقل يحمل قيمة مُكرّرة',
    'doesnt_end_with' => 'هذا الحقل يجب ألّا ينتهي بأحد القيم التالية :values',
    'doesnt_start_with' => 'هذا الحقل يجب ألّا يبدأ بأحد القيم التالية :values',
    'email' => 'يجب أن يكون عنوان بريد إلكتروني صحيح البنية',
    'ends_with' => 'يجب أن ينتهي بأحد القيم التالية :values',
    'enum' => 'القيمة المحددة غير موجودة في قائمة القيم المسموح بها',
    'exists' => 'القيمة المعطاة غير موجودة',
    'extensions' => 'يجب أن يحتوي هذا الحقل على أحد الإمتدادات التالية :values',
    'file' => 'المحتوى يجب أن يكون ملفا',
    'filled' => 'هذا الحقل إجباري',
    'gt' => [
        'array' => 'يجب أن يحتوي على أكثر من :value',
        'file' => 'يجب أن يكون حجم الملف أكبر من :value كيلوبايت',
        'numeric' => 'يجب أن تكون القيمة أكبر من :value',
        'string' => 'يجب أن يكون المكتوب أكثر من :value',
    ],
    'gte' => [
        'array' => 'يجب أن يحتوي على الأقل على :value',
        'file' => 'يجب أن يكون حجم الملف على الأقل :value كيلوبايت',
        'numeric' => 'يجب أن تكون القيمة مساوية أو أكبر من :value',
        'string' => 'يجب أن يكون المكتوب على الأقل :value حرف',
    ],
    'hex_color' => 'يجب أن يحتوي هذا الحقل على صيغة لون HEX صالحة',
    'image' => 'يجب أن تكون صورةً',
    'in' => 'القيمة المعطاة غير موجودة في قائمة القيم المسموح بها',
    'in_array' => 'هذه القيمة غير موجودة في :other',
    'integer' => 'يجب أن يكون عددًا صحيحًا',
    'ip' => 'يجب أن يكون عنوان IP صحيحًا',
    'ipv4' => 'يجب أن يكون عنوان IPv4 صحيحًا',
    'ipv6' => 'يجب أن يكون عنوان IPv6 صحيحًا',
    'json' => 'يجب أن يكون نصًا من نوع JSON',
    'list' => 'يجب أن يكون هذا الحقل قائمة',
    'lowercase' => 'يجب أن يحتوي الحقل على حروف صغيرة',
    'lt' => [
        'array' => 'يجب أن يحتوي على أقل من :value',
        'file' => 'يجب أن يكون حجم الملف أصغر من :value كيلوبايت',
        'numeric' => 'يجب أن تكون القيمة أصغر من :value',
        'string' => 'يجب أن يكون المكتوب أقل من :value حرف',
    ],
    'lte' => [
        'array' => 'يجب أن لا يحتوي على أكثر من :value',
        'file' => 'يجب أن لا يتجاوز حجم الملف :value كيلوبايت',
        'numeric' => 'يجب أن تكون القيمة مساوية أو أصغر من :value',
        'string' => 'يجب أن لا يتجاوز المكتوب :value',
    ],
    'mac_address' => 'يجب أن تكون القيمة عنوان MAC صالحاً',
    'max' => [
        'array' => 'يجب أن لا يحتوي على أكثر من :max',
        'file' => 'يجب أن لا يتجاوز حجم الملف :max كيلوبايت',
        'numeric' => 'يجب أن تكون القيمة مساوية أو أصغر من :max حرف',
        'string' => 'يجب أن لا يتجاوز المكتوب :max حرف',
    ],
    'max_digits' => 'يجب ألا يحتوي هذا الحقل على أكثر من :max رقم',
    'mimes' => 'يجب أن يكون ملفًا من نوع :values',
    'mimetypes' => 'يجب أن يكون ملفًا من نوع :values',
    'min' => [
        'array' => 'يجب أن يحتوي على الأقل على :min',
        'file' => 'يجب أن يكون حجم الملف على الأقل :min كيلوبايت',
        'numeric' => 'يجب أن تكون القيمة مساوية أو أكبر من :min',
        'string' => 'يجب أن يكون المكتوب على الأقل :min حرف',
    ],
    'min_digits' => 'يجب أن يحتوي هذا الحقل على الأقل :min رقم',
    'missing' => 'يجب أن يكون هذا الحقل مفقوداً',
    'missing_if' => 'يجب أن يكون هذا الحقل مفقوداً عندما :other يساوي :value',
    'missing_unless' => 'يجب أن يكون هذا الحقل مفقوداً ما لم يكن :other يساوي :value',
    'missing_with' => 'يجب أن يكون هذا الحقل مفقوداً عند توفر :values',
    'missing_with_all' => 'يجب أن يكون هذا الحقل مفقوداً عند توفر :values',
    'multiple_of' => 'يجب أن تكون القيمة من مضاعفات :value',
    'not_in' => 'يجب ألا تكون القيمة المحددة في القائمة',
    'not_regex' => 'صيغة غير صالحة',
    'numeric' => 'يجب أن يكون رقمًا',
    'password' => [
        'letters' => 'يجب أن يحتوي هذا الحقل على حرف واحد على الأقل',
        'mixed' => 'يجب أن يحتوي هذا الحقل على حرف كبير وحرف صغير على الأقل',
        'numbers' => 'يجب أن يحتوي هذا الحقل على رقمٍ واحدٍ على الأقل',
        'symbols' => 'يجب أن يحتوي هذا الحقل على رمزٍ واحدٍ على الأقل',
        'uncompromised' => 'قيمة هذا الحقل ظهرت في بيانات مُسربة. الرجاء اختيار قيمة مختلفة',
    ],
    'present' => 'يجب توفر هذا الحقل',
    'present_if' => 'يجب توفر هذا الحقل عندما :other يساوي :value',
    'present_unless' => 'يجب توفر هذا الحقل ما لم يكن :other يساوي :value',
    'present_with' => 'يجب توفر هذا الحقل عند توفر :values',
    'present_with_all' => 'يجب توفر هذا الحقل عند توفر :values',
    'prohibited' => 'هذا الحقل محظور',
    'prohibited_if' => 'هذا الحقل محظور إذا كان :other هو :value',
    'prohibited_unless' => 'هذا الحقل محظور ما لم يكن :other ضمن :values',
    'prohibits' => 'هذا الحقل يحظر تواجد الحقل :other',
    'regex' => 'الصيغة غير صحيحة',
    'relatable' => 'هذا الحقل قد لا يكون مرتبطا بالمصدر المحدد',
    'required' => 'هذا الحقل مطلوب',
    'required_array_keys' => 'يجب أن يحتوي هذا الحقل على مدخلات لـ :values',
    'required_if' => 'هذا الحقل مطلوب في حال ما إذا كان :other يساوي :value',
    'required_if_accepted' => 'هذا الحقل مطلوب عند قبول الحقل :other',
    'required_unless' => 'هذا الحقل مطلوب في حال ما لم يكن :other يساوي :values',
    'required_with' => 'هذا الحقل مطلوب إذا توفّر :values',
    'required_with_all' => 'هذا الحقل مطلوب إذا توفّر :values',
    'required_without' => 'هذا الحقل مطلوب إذا لم يتوفّر :values',
    'required_without_all' => 'هذا الحقل مطلوب إذا لم يتوفّر :values',
    'same' => 'يجب أن يتطابق هذا الحقل مع :other',
    'size' => [
        'array' => 'يجب أن يحتوي على :size عنصر',
        'file' => 'يجب أن يكون حجم الملف :size كيلوبايت',
        'numeric' => 'يجب أن تكون القيمة مساوية لـ :size',
        'string' => 'يجب أن يحتوي النص على :size حرف',
    ],
    'starts_with' => 'يجب أن يبدأ بأحد القيم التالية :values',
    'string' => 'يجب أن يكون نصًا',
    'timezone' => 'يجب أن يكون نطاقًا زمنيًا صحيحًا',
    'ulid' => 'يجب أن يكون بصيغة ULID سليمة',
    'unique' => 'هذه القيمة مُستخدمة من قبل',
    'uploaded' => 'فشل في عملية التحميل',
    'uppercase' => 'يجب أن يحتوي الحقل على حروف كبيرة',
    'url' => 'الصيغة غير صحيحة',
    'uuid' => 'يجب أن يكون بصيغة UUID سليمة',

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

    'attributes' => [],

];
