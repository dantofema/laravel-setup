<?php

namespace Dantofema\LaravelSetup\Traits;

use Dantofema\LaravelSetup\Facades\Text;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait FileExistsTrait
{
    /**
     * @throws Exception
     */
    protected function exists(string $type): bool
    {
        if ($type == 'migration') {
            $this->migrationFileExists();
        }

        if (File::exists(Text::config($this->config)->path($type))) {
            $this->error('The ' . $type . ' file "' . Text::config($this->config)->filename($type) . '" already exists ');
            $this->error('Exit');

            throw new Exception('Livewire file exists');
        }

        return false;
    }

    protected function migrationFileExists(): bool
    {
        return collect(File::files('database/migrations/'))
            ->contains(function ($file) {
                $name = $this->config['table']['name'];
<<<<<<< HEAD

                if (Str::contains($file, '_create_' . $name . '_table.php'))
                {
=======
                if (Str::contains($file, '_create_' . $name . '_table.php')) {
>>>>>>> 5d36defe2c536482610e31c26878e25028cc7f16
                    throw new Exception('Migration file exists');
                }
            });
    }

    /**
     * @throws Exception
     */
    protected function configFileExists(): bool
    {
        if (File::exists($this->argument('path'))) {
            return true;
        }
<<<<<<< HEAD

        throw new Exception('Not found "' . $this->argument('path') . '"');
=======
        $this->error('Not found "' . $this->argument('path') . '"');
        $this->error('Exit');

        throw new Exception('Migration file exists');
>>>>>>> 5d36defe2c536482610e31c26878e25028cc7f16
    }
}
