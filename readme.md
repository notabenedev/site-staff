## Конфиг
    php artisan vendor:publish --provider="Notabenedev\SiteStaff\StaffServiceProvider" --tag=config

## Install
    php artisan migrate
    php artisan vendor:publish --provider="Notabenedev\SiteStaff\StaffServiceProvider" --tag=public --force
    php artisan make:staff
                            {--all : Run all}
                            {--models : Export models}
                            {--controllers : Export models}
                            {--observers : Export observers}
                            {--policies : Export models}
                            {--menu : Create admin menu}
                            {--vue : Export Vue components}
                            {--scss : Export Scss}
                            {--js : Export Scripts}
    npm run dev

## Update
    v1.0.8 fix departments metas
    v1.0.7 fix admin employees rule
    v1.0.6 change employee teaser id
    v1.0.5 employee authorize, delete content-section
    v1.0.4 departments show fix
    v1.0.3 breadcrumb, ico
        - php artisan vendor:publish --provider="Notabenedev\SiteStaff\StaffServiceProvider" --tag=public --force