<?php
return [
    'model' => 'App\Models\Post',
    'table' => [
        'name' => 'posts',
        'columns' => [
            ['string', 'title'],
            ['text', 'body'],
            ['string', 'epigraph', 'nullable'],
            ['string', 'name', 'nullable', 'unique'],
        ],
        'foreignKeys' => [
            ['user_id', 'users'],
            ['author_id', 'authors', 'nullable'],
            ['key_id', 'keys', 'nullable', 'unique'],
        ],
    ],
];