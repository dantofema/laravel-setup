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
//                ['subcategories', 'Subcategory'],

            ],
            'belongsToMany' => [
//                ['tags', 'Tag'],
            ],
            'belongsTo' => [
                ['author', 'Author'],
//               ['category', 'Category'],
            ],
        ],
//        'search' => ['title', 'subtitle', 'created_at', 'tags.name', 'category.name'],
        'search' => ['title', 'subtitle', 'created_at', 'author.title'],
    ],
    'table' => [
        'name' => 'posts',
        'columns' => [
            ['string', 'title', 'unique'],
            ['string', 'slug', 'unique', 'from' => 'title'],
            ['string', 'image', 'nullable'],
            ['text', 'lead', 'nullable'],
            ['text', 'body'],
            ['string', 'epigraph', 'nullable'],
            ['string', 'subtitle'],
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
        'useModels' => [
//            'App\Models\Category',
//            'App\Models\Tag',
            'App\Models\Post',
        ],
        'properties' => [
            'sortField' => 'created_at',
            'editing' => 'Post',
            'newImage' => [
                'disk' => 'notas',
                'field' => 'image',
            ],
        ],
        'save' => ['slug' => 'title'],
        'rules' => [
            'editing.title' => 'required|unique:App\Models\Post,title, . \$this->editing->id',
            'editing.subtitle' => 'nullable',
            'editing.lead' => 'required',
            'editing.body' => 'required',
            'editing.epigraph' => 'nullable',
            'editing.author_id' => 'nullable',
//            'editing.category_id' => 'required',
            'tags' => 'nullable',
        ],
    ],
    'view' => [
        'jetstream' => true,
        'title' => false,
        'actions' => [
            'edit' => true,
            'delete' => true,
        ],
        'table' => [
            'columns' => [
                'title' => [
                    'sortable' => true,
                    'label' => 'Título',
                ],
                'author.title' => [
                    'sortable' => false,
                    'label' => 'Autor',
                ],
            ],
        ],
    ],
    'test' => [
    ],
];