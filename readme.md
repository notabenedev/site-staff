## Конфиг
    php artisan vendor:publish --provider="Notabenedev\SitePages\PagesServiceProvider" --tag=config

## Install
    php artisan migrate
    php artisan vendor:publish --provider="Notabenedev\SiteStaff\StaffServiceProvider" --tag=public --force
    php artisan make:staff
                            {--all : Run all}
                            {--models : Export models}
                            {--controllers : Export models}
                            {--observers : Export observers}
                            {--policies : Export models}
                            {--vue : Export Vue components}
    npm run dev