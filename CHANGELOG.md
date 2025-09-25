## Versions
###    v2.1.5 address to form (uses  staff-types package)

Обновление

- если используется пакет staff-types, в проетке добавить новое поле address к форме


    php artisan vendor:publish --provider="Notabenedev\SiteStaff\StaffServiceProvider" --tag=public --force

###    v2.1.0-2.1.4 add employee site page & can use staff-types package
    
    config: site-staff.employeePage = false by default
    php artisan make:staff --controllers

###    v2.0.3 add btn_enabled to employee
- добавлено поле boolean для указания доступности кнопки записи для сотрудника
- fix конфига: employeeBtnName

Обновление

- php artisan migrate
- 
Проверить переопределение:
- Шаблонов: admin.employees: create, edit,show;   site.empllyees:teaser, includes.modal; site.departments: index, show;
- Конфиг employeeBtnName
- Admin/EmployeeController: store, update
- 
###    v2.0.1-2.0.2 fix employee form
- добавлено доп поле с именем сотрудника
- восстнаовление имени сотрудника после отправки формы записи
- закрытие уведомлений об отпарвке формы после закртия модального окна с формой
- обновлены шаблоны: site.employees.includes.form, admin.employees.edit


            php artisan vendor:publish --provider="Notabenedev\SiteStaff\StaffServiceProvider" --tag=public --force

###    v2.0.0 base 5
- new filters: empoloyees-grid-xxl-3, employees-grid-xxl-4
- обновлен staff-modal.js
- обновлены шаблоны: admin.departments.includes.pills & table-list, admin.employees.index & menu & includes.table-list
- обновлены шаблоны: site.employees.tesaser


        

        php artisan vendor:publish --provider="Notabenedev\SiteStaff\StaffServiceProvider" --tag=public --force
###     v1.0.9 base 4
###     v1.0.8 fix departments metas
###     v1.0.7 fix admin employees rule
###     v1.0.6 change employee teaser id
###     v1.0.5 employee authorize, delete content-section
###     v1.0.4 departments show fix
###     v1.0.3 breadcrumb, ico
        php artisan vendor:publish --provider="Notabenedev\SiteStaff\StaffServiceProvider" --tag=public --force