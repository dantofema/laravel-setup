<?php

namespace Dantofema\LaravelSetup\Services;

use Dantofema\LaravelSetup\Facades\Field;
use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileSystemService
{
    protected const ORIGINAL_LINKS = "public_path('storage') => storage_path('app/public'),";
    protected const ORIGINAL_DISKS = " 'disks' => [";
    protected const FILESYSTEM_PHP = 'config/filesystems.php';
    protected const FILESYSTEM_STUB = __DIR__ . '/../Stubs/filesystems.php.stub';

    public function execute (array $config)
    {
        if (empty(Field::config($config)->getFile()))
        {
            return;
        }

        $disk = strtolower(Text::config($config)->name('model'));

        if (Str::contains(File::get(self::FILESYSTEM_PHP), $disk))
        {
            return;
        }

        $content = $this->replaceLinks($disk);

        $content = $this->replaceDisks($disk, $content);

        File::put(self::FILESYSTEM_PHP, $content);
    }

    private function replaceLinks (string $disk): string
    {
        $links = str_replace("'storage'", "'" . $disk . "'", self::ORIGINAL_LINKS);
        $links = str_replace("/public'", "/" . $disk . "'", $links);
        $links = self::ORIGINAL_LINKS . PHP_EOL . $links;
        return str_replace(self::ORIGINAL_LINKS, $links, File::get(self::FILESYSTEM_PHP));
    }

    private function replaceDisks (string $disk, string $content): string|array
    {
        $stub = self::ORIGINAL_DISKS
            . PHP_EOL
            . str_replace('modelLower', $disk, file_get_contents(self::FILESYSTEM_STUB));

        return str_replace(self::ORIGINAL_DISKS, $stub, $content);
    }

}