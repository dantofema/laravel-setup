<?php

return [
    'backend' => true,
    'route' => [
        'path' => 'notas',
    ],
    'model' => [
        'namespace' => 'App\Models',
        'name' => 'Post',
        'use' => ['SoftDeletes', 'Userstamps'],
        'relationships' => [
            'hasMany' => [
                ['subcategories', 'Subcategory'],
                ['authors', 'Author'],
            ],
            'belongsToMany' => [
                ['tags', 'Tag'],
            ],
            'belongsTo' => [
                ['category', 'Category'],
            ],
        ],
        'search' => ['title', 'subtitle', 'created_at', 'tags.name', 'category.name'],
    ],
    'table' => [
        'name' => 'posts',
        'columns' => [
            ['string', 'title', 'unique'],
            ['string', 'slug', 'unique', 'from' => 'title'],
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
    'livewire' => [
        'namespace' => 'App\Http\Livewire\Backend',
        'view' => 'livewire.backend.post-livewire',
        'useModels' => [
            'App\Models\Category',
            'App\Models\Tag',
            'App\Models\Post',
        ],
        'properties' => [
            'sortField' => 'created_at',
            'editing' => 'Post',
        ],
        'save' => ['slug' => 'title'],
        'rules' => [
            'editing.title' => 'required|unique:App\Models\Post,title, . \$this->editing->id',
            'editing.subtitle' => 'nullable',
            'editing.lead' => 'required',
            'editing.body' => 'required',
            'editing.epigraph' => 'nullable',
            'editing.author' => 'nullable',
            'editing.category_id' => 'required',
            'tags' => 'nullable',
        ],
    ],
    'view' => [
        'title' => 'Notas',
        'edit' => [
            [
                'field' => 'title',
                'type' => 'text',
                'label' => 'Título',
            ],
            [
                'field' => 'body',
                'type' => 'textarea',
                'label' => 'Cuerpo',
            ],
        ],
    ],
    'test' => [
    ],
];
