<?php
return [
    'model' => [
        'namespace' => 'App\Models',
        'name' => 'Post',
        'use' => ['SoftDeletes', 'Userstamps'],
        'relationships' => [
            'hasMany' => [
                ['subcategories' => 'Subcategory'],
            ],
            'belongsToMany' => [
                ['subcategories' => 'Subcategory'],
            ],
            'belongsTo' => [
                ['category' => 'Category'],
            ],
        ],
        'search' => ['title', 'subtitle', 'created_at', 'tags.name', 'category.name'],
    ],
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