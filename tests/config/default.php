<?php
return [
    'backend' => true,
    'route' => [
        'path' => 'notas',
    ],
    'model' => [
        'name' => 'Post',
        'use' => ['SoftDeletes', 'Userstamps'],
    ],
    'table' => [
        'name' => 'posts',
    ],
    'livewire' => [
        'properties' => [
            'sortField' => 'created_at',
        ],
    ],
    'view' => [
        'jetstream' => true,
        'title' => false,
    ],

    /**
     * default values
     *
     * 'edit' = true
     * 'show' = true
     * 'nullable' = false
     * 'unique' = false
     * 'required' = false
     * 'searchable' = false
     * 'index' = false
     * 'sortable' = false
     * 'modelNamespace' = 'App\Models'
     * 'actions' => ['edit' => true, 'delete' => true],
     */
    'fields' => [
        [
            'name' => 'title',
            'label' => 'Título',
            'unique' => true,
            'searchable' => true,
            'index' => true,
            'sortable' => true,
            /**
             * types
             *
             * string, text
             */
            'type' => 'string',
            /**
             * HTML inputs in forms
             *
             * text, textarea, file, select
             */
            'form' => [
                'input' => 'text',
            ],
            /**
             * default values in rules
             *
             * [ nullable => false , unique => false , extra => [] ]
             */
            'rules' => [
                'required' => true,
                'unique' => "'unique:App\Models\Post,title,' . \$this->editing->id",
                'extra' => ['min:3', 'max:100'],
            ],
        ],
        [
            'name' => 'author_id',
            'label' => 'Autor',
            'nullable' => true,
            'searchable' => true,
            'index' => true,
            'sortable' => false,
            'form' => [
                'input' => 'select',
            ],
            'rules' => [
                'nullable' => true,
            ],
            'relationship' => [
                'type' => 'belongsTo',
                'name' => 'author',
                'model' => 'Author',
                'searchable' => 'name',
                'table' => 'authors',
                'label' => 'Autor',
                'namespace' => 'App\Models\\',
            ],
        ],
        [
            'name' => 'image',
            'type' => 'string',
            'label' => 'Foto',
            'nullable' => true,
            'form' => [
                'input' => 'file',
            ],
            'rules' => [
                'nullable' => true,
                'extra' => ['image', 'file'],
            ],
        ],
        [
            'name' => 'slug',
            'type' => 'string',
            'label' => 'Slug',
            'form' => [
                'input' => false,
            ],
            'source' => 'title',
            'rules' => [
                'required' => true,
            ],
        ],
        [
            'name' => 'body',
            'type' => 'text',
            'label' => 'Cuerpo',
            'form' => [
                'input' => 'textarea',
            ],
            'rules' => [
                'nullable' => true,
            ],
        ],
        [
            'name' => 'tags',
            'label' => 'Etiquetas',
            'nullable' => true,
            'searchable' => true,
            'index' => true,
            'sortable' => false,
            'form' => [
                'input' => 'text',
            ],
            'rules' => [
                'nullable' => true,
            ],
            'relationship' => [
                'type' => 'belongsToMany',
                'name' => 'tags',
                'model' => 'Tag',
                'searchable' => 'name',
                'table' => 'tags',
                'label' => 'Etiquetas',
                'namespace' => 'App\Models\\',
                'pivot' => [
                    'table' => 'post_tag',
                ],
            ],
        ],
    ],
];