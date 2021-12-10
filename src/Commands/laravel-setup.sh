#!/usr/bin/env bash

./vendor/bin/sail composer update;
./vendor/bin/sail composer dump-autoload;

# jetstream
./vendor/bin/sail composer require laravel/jetstream;
./vendor/bin/sail php artisan jetstream:install livewire;
./vendor/bin/sail npm install;
./vendor/bin/sail npm run dev;
./vendor/bin/sail php artisan migrate;
./vendor/bin/sail php artisan vendor:publish --tag=jetstream-views;

# dusk
./vendor/bin/sail composer require --dev laravel/dusk;
./vendor/bin/sail php artisan dusk:install;

# livewire
./vendor/bin/sail composer require livewire/livewire

# tailwindcss
./vendor/bin/sail npm install -D tailwindcss@latest postcss@latest autoprefixer@latest
./vendor/bin/sail npx tailwindcss init

# spatie permissions
./vendor/bin/sail composer require spatie/laravel-permission;
./vendor/bin/sail php artisan config:clear;
./vendor/bin/sail php artisan migrate;

# laraveles
./vendor/bin/sail composer require laraveles/spanish;
./vendor/bin/sail php artisan vendor:publish --tag=lang;

# debugbar
./vendor/bin/sail composer require --dev barryvdh/laravel-debugbar;

# blade components
./vendor/bin/sail composer require blade-ui-kit/blade-ui-kit;
./vendor/bin/sail composer require blade-ui-kit/blade-icons;
./vendor/bin/sail composer require blade-ui-kit/blade-heroicons;

# query-detector
./vendor/bin/sail composer require beyondcode/laravel-query-detector --dev;

# link
./vendor/bin/sail php artisan storage:link;
mkdir storage/app/public/images

# userstamps
# ./vendor/bin/sail composer require wildside/userstamps;

# spatie/laravel-feed
./vendor/bin/sail composer require spatie/laravel-feed

# laRecipe
./vendor/bin/sail composer require binarytorch/larecipe
./vendor/bin/sail php artisan larecipe:install

# ide-helper
./vendor/bin/sail composer require --dev barryvdh/laravel-ide-helper;
./vendor/bin/sail php artisan ide-helper:generate;
./vendor/bin/sail php artisan ide-helper:models --nowrite --reset;
./vendor/bin/sail php artisan ide-helper:meta;

#pest
./vendor/bin/sail composer require pestphp/pest --dev --with-all-dependencies
./vendor/bin/sail composer require pestphp/pest-plugin-laravel --dev
./vendor/bin/sail php artisan pest:install -q
./vendor/bin/sail composer require pestphp/pest-plugin-faker --dev

./vendor/bin/sail composer update;
./vendor/bin/sail composer dump-autoload;

./vendor/bin/sail test --stop-on-failure;
./vendor/bin/sail ./vendor/bin/pest
./vendor/bin/sail dusk --stop-on-failure;

sudo chown -R dantofema ./

git init;
git add .;
git commit -m "first commit";
git branch -M main;

# blade ui components
# @bukStyles(true)
# @bukScripts(true)
# php artisan icons:cache
# php artisan icons:clear