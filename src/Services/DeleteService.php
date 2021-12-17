<?php

namespace Dantofema\LaravelSetup\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DeleteService
{

    public function view (array $config)
    {
        File::delete(gen()->config()->view($config));
        if ( ! gen()->config()->isAllInOne($config))
        {
            File::delete(gen()->config()->isModel()->view($config));
        }
    }

    public function test (array $config)
    {
        File::delete(gen()->config()->test($config));
    }

    public function factory (array $config)
    {
        File::delete(gen()->config()->factory($config));
    }

    public function model (array $config)
    {
        File::delete(gen()->config()->model($config));
    }

    public function livewire (array $config)
    {
        (new RouteService())->delete($config);
        File::delete(gen()->config()->view($config));
    }

    public function migration (array $config): void
    {
        collect(File::files('database/migrations/'))
            ->contains(function ($file) use ($config) {
                if (Str::contains($file, '_create_'
                    . gen()->config()->table($config)
                    . '_table.php'))
                {
                    File::delete($file);
                }

                $relationshipFields = gen()->field()->getRelationships($config);

                foreach ($relationshipFields as $relationshipField)
                {
                    if (
                        $relationshipField['relationship']['type'] === 'belongsToMany' and
                        Str::contains($file, $relationshipField['relationship']['pivot']['table'])
                    )
                    {
                        File::delete($file);
                    }
                }
            });
    }

}
