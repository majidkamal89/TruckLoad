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

    'accepted'             => ':attribute må være akseptert.',
    'active_url'           => ':attribute er ikke en gyldig URL.',
    'after'                => ':attribute må være en dato etter :date.',
    'after_or_equal'       => ':attribute må være en dato etter eller samme dato som :date.',
    'alpha'                => ':attribute kan kun inneholde bokstaver.',
    'alpha_dash'           => ':attribute kan kun inneholde bokstaver, tall eller streker.',
    'alpha_num'            => ':attribute kan kun inneholde bokstaver eller tall.',
    'array'                => ':attribute må være et array.',
    'before'               => ':attribute må være en dato før :date.',
    'before_or_equal'      => ':attribute må være en dato før eller samme dato som :date.',
    'between'              => [
        'numeric' => ':attribute må være mellom :min og :max.',
        'file'    => ':attribute må være mellom :min og :max kilobyte.',
        'string'  => ':attribute må være mellom :min og :max tegn.',
        'array'   => ':attribute må være mellom :min og :max enheter.',
    ],
    'boolean'              => ':attribute feltet må være true eller false.',
    'confirmed'            => 'Bekreftelsen av :attribute matcher ikke.',
    'date'                 => ':attribute er ikke en gyldig dato.',
    'date_format'          => ':attribute er ikke gyldig i henhold til formatet :format.',
    'different'            => ':attribute og :other må være forskjellig.',
    'digits'               => ':attribute må være på :digits siffer.',
    'digits_between'       => ':attribute må være på mellom :min og :max siffer.',
    'dimensions'           => ':attribute har ugyldige dimensjoner.',
    'distinct'             => ':attribute feltet har en duplikat verdi.',
    'email'                => ':attribute må være en gyldig epostadresse.',
    'exists'               => 'Det valgte :attribute er gyldig.',
    'file'                 => ':attribute må være en fil.',
    'filled'               => ':attribute feltet må ha en verdi.',
    'image'                => ':attribute må være et bilde.',
    'in'                   => 'Det valgte :attribute er ugyldig.',
    'in_array'             => ':attribute feltet eksisterer ikke i :other.',
    'integer'              => ':attribute må være et heltall.',
    'ip'                   => ':attribute må være en gyldig IPadresse.',
    'ipv4'                 => ':attribute må være en gyldig IPv4-adresse.',
    'ipv6'                 => ':attribute må være en gyldig IPv6 adresse.',
    'json'                 => ':attribute må være en gyldig JSON-streng.',
    'max'                  => [
        'numeric' => ':attribute kan ikke være høyere enn :max.',
        'file'    => ':attribute kan ikke være større enn :max kilobyte.',
        'string'  => ':attribute kan ikke være lengre enn :max tegn.',
        'array'   => ':attribute kan ikke ha mer enn :max enheter.',
    ],
    'mimes'                => ':attribute må være en fil av typen: :values.',
    'mimetypes'            => ':attribute må være en fil av typen: :values.',
    'min'                  => [
        'numeric' => ':attribute må være minst :min.',
        'file'    => ':attribute må være minst :min kilobyte.',
        'string'  => ':attribute må være minst :min tegn.',
        'array'   => ':attribute må ha minst :min enheter.',
    ],
    'not_in'               => 'Det valgte :attribute er ugyldig.',
    'numeric'              => ':attribute må være et tall.',
    'present'              => ':attribute feltet må være tilstede.',
    'regex'                => ':attribute formatet er ugyldig.',
    'required'             => ':attribute feltet er påkrevet.',
    'required_if'          => ':attribute feltet er påkrevet når :other er :value.',
    'required_unless'      => ':attribute feltet er påkrevet med mindre :other er i :values.',
    'required_with'        => ':attribute feltet er påkevet når :values er tilstede.',
    'required_with_all'    => ':attribute feltet er påkrevet når :values er tilstede.',
    'required_without'     => ':attribute feltet er påkrevet når :values ikke er tilstede.',
    'required_without_all' => ':attribute feltet er påkrevet når ingen av :values er tilstede.',
    'same'                 => ':attribute og :other må være like.',
    'size'                 => [
        'numeric' => ':attribute må være :size.',
        'file'    => ':attribute må være :size kilobyte.',
        'string'  => ':attribute må inneholde :size tegn.',
        'array'   => ':attribute må inneholde :size enheter.',
    ],
    'string'               => ':attribute må være en streng.',
    'timezone'             => ':attribute må være en gyldig sone.',
    'unique'               => ':attribute her allerede i bruk.',
    'uploaded'             => ':attribute - opplasting feilet.',
    'url'                  => ':attribute formatet er ugyldig.',

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
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
