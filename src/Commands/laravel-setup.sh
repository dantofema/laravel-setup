composer require laravel/jetstream;
php artisan jetstream:install livewire;
npm install;
npm run dev;
php artisan migrate;
php artisan vendor:publish --tag=jetstream-views;
composer require --dev laravel/dusk;
php artisan dusk:install;
composer require livewire/livewire;
npm install -D tailwindcss@latest postcss@latest autoprefixer@latest;
npx tailwindcss init;
composer require spatie/laravel-permission;
php artisan config:clear;
php artisan migrate;
composer require laraveles/spanish;
php artisan vendor:publish --tag=lang;
composer require --dev barryvdh/laravel-debugbar;
composer require blade-ui-kit/blade-ui-kit;
composer require blade-ui-kit/blade-icons;
composer require blade-ui-kit/blade-heroicons;
composer require beyondcode/laravel-query-detector --dev;
php artisan storage:link;
mkdir storage/app/public/images;
composer require wildside/userstamps;
composer require spatie/laravel-feed;
composer require binarytorch/larecipe;
php artisan larecipe:install;
composer require --dev barryvdh/laravel-ide-helper;
php artisan ide-helper:generate;
php artisan ide-helper:models --nowrite --reset;
php artisan ide-helper:meta;
php artisan composer update;
php artisan composer dump-autoload;
#php artisan test --stop-on-failure;
#php artisan dusk --stop-on-failure;

