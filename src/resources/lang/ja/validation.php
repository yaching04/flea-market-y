<?php

return [
    'required' => ':attributeを入力してください。',

    'custom' => [
        'name' => [
            'required' => 'お名前を入力してください。',
        ],
        'email' => [
            'required' => 'メールアドレスを入力してください。',
            'email' => '有効なメールアドレスを入力してください。',
            'unique' => 'このメールアドレスは既に使用されています。',
        ],
        'password' => [
            'required' => 'パスワードを入力してください。',
            'min' => 'パスワードは:min文字以上で入力してください。',
            'confirmed' => 'パスワードが一致しません。',
        ],
        'password_confirmation' => [
            'required' => '確認用パスワードを入力してください。',
        ],
    ],

    'attributes' => [
        'name'                  => 'お名前',
        'email'                 => 'メールアドレス',
        'password'              => 'パスワード',
        'password_confirmation' => '確認用パスワード',
    ],
];

