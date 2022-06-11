<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#61-title
    |
    */

    'title' => 'ƎFP SALES',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#62-favicon
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#63-logo
    |
    */

    'logo' => '',
    'logo_img' => 'images/Logo.png',
    'logo_img_mini' => 'images/LogoMini.png',
    'logo_img_class' => 'brand-image',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-md',
    'logo_img_alt' => 'ƎFP SALES',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#64-user-menu
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-lightblue',
    'usermenu_image' => true,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#71-layout
    |
    */

    'layout_topnav' => true,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => true,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#721-authentication-views-classes
    |
    */

    // 'classes_auth_card' => 'card-outline card-primary',
    // 'classes_auth_header' => '',
    // 'classes_auth_body' => '',
    // 'classes_auth_footer' => '',
    // 'classes_auth_icon' => '',
    // 'classes_auth_btn' => 'btn-flat btn-primary',
    'enable_footer' => true,
    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#722-admin-panel-classes
    |
    */
    // 'classes_body' => '',
    // 'classes_brand' => '',
    // 'classes_brand_text' => '',
    // 'classes_content_wrapper' => '',
    // 'classes_content_header' => '',
    // 'classes_content' => '',
    // 'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    // 'classes_sidebar_nav' => '',
    // 'classes_topnav' => 'navbar-white navbar-light',
    // 'classes_topnav_nav' => 'navbar-expand', 
    // 'classes_topnav_container' => 'container',
    'classes_body' => 'layout-fixed',
    'classes_brand' => '',
    'classes_brand_text' => 'EFP SALES',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-light-navy nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact text-lg shadow-lg bg-white rounded',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-dark navbar-lightblue',
    'classes_topnav_nav' => 'navbar-expand-sm',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#73-sidebar
    |
    */

    'sidebar_mini' => true,
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#74-control-sidebar-right-sidebar
    |
    */

    'right_sidebar' => true,
    'right_sidebar_icon' => 'fad fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#65-urls
    |
    */

    'use_route_url' => false,

    'dashboard_url' => 'home',

    'logout_url' => 'logout',

    'login_url' => 'login',

    'register_url' => 'register',

    'password_reset_url' => 'password/reset',

    'password_email_url' => 'password/email',

    'profile_url' => 'profile',

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#92-laravel-mix
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#8-menu-configuration
    |
    */

    'menu' => [
        [
            'text'        => 'Chats',
            'icon' => 'fad fa-table',
            'url'         => 'indexchat',
            // 'icon'        => 'far fa-fw fa-file',
            // 'label'       => 4,
            'label_color' => 'success',
        ],
        [
            'text'        => 'Tablero',
            'icon' => 'fad fa-table',
            'url'         => 'home',
            // 'icon'        => 'far fa-fw fa-file',
            // 'label'       => 4,
            'label_color' => 'success',
        ],
        [
            'text' => 'Leads',
            'icon' => 'fad fa-id-card',
            'url'  => 'leadcrud',
            'can'  => 'crm'
        ],
        [
            'text' => 'Matrículas',
            'icon' => 'fad fa-user-graduate',
            'url'  => 'contractcrud',
            'can'  => 'crm'
        ],
        [
            'text' => 'Mis Alumnos',
            'icon' => 'fad fa-user-graduate',
            'url'  => 'tutoringcrud',
            'can'  => 'teacher'
        ],
        [
            'text' => 'Calendario',
            'icon' => 'fad fa-calendar-day',
            'url'  => 'appointments',
            'can'  => 'crm'
        ],
        [
            'text' => 'Certificados',
            'icon' => 'fad fa-file-certificate',
            'url'  => 'certificatecrud',
            'can'  => 'admin'
        ],
        [
            'text' => 'Prácticas',
            'icon' => 'fad fa-id-card-alt',
            'url'  => 'contractinternshipscrud',
            'can'  => 'admin'
        ],
        [
            'text' => 'Finanzas',
            'icon' => 'fad fa-coin',
            'can'  => 'crm',
            'submenu' => [
                [
                    'text' => 'Liquidaciones',
                    'url'  => 'liquidationcrud',
                    'icon' => 'fad fa-money-check-edit-alt text-lightblue'
                ],
                [
                    'text' => 'Histórico de Liquidaciones',
                    'url'  => 'liquidationcrudlog',
                    'icon' => 'fad fa-history text-lightblue',
                    'can'  => 'admin'
                ],
                [
                    'text' => 'Estadísticas y Comparativas',
                    'url'  => 'financialgraph',
                    'icon' => 'fad fa-chart-area text-lightblue',
                    'can'  => 'admin'
                ],
                
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#83-custom-menu-filters
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#91-plugins
    |
    */

    
    'plugins' => [
        'Datatables' => [//Crud de elementos, exportar información, guardar en excel, pdf, etc
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.22/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.2/fc-3.3.1/fh-3.1.7/kt-2.5.3/r-2.2.6/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.0/sp-1.2.1/sl-1.3.1/datatables.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/v/bs4-4.1.1/jszip-2.5.0/dt-1.10.22/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.2/fc-3.3.1/fh-3.1.7/kt-2.5.3/r-2.2.6/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.0/sp-1.2.1/sl-1.3.1/datatables.min.css',
                ],
            ],
        ],
        'Select2' => [//dropdown selector más complejos con buscador
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [//Para graficos dinámicos
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [//Para alertas en modals
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@10',
                ],
            ],
        ],
        'ToastR' => [//Para alertas
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css',
                ],
            ],
        ],
        'Pace' => [//Preloader
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    // 'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/themes/blue/pace-theme-center-circle.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    // 'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/pace.min.js',
                ],
            ],
        ],
        'momentJS' => [//manejo de fechas en javascript
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/es.min.js',
                ],
            ],
        ],
        'FullCalendar' => [//calendario 
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/fullcalendar@5.4.0/main.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/fullcalendar@5.4.0/main.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/fullcalendar@5.4.0/locales-all.js',
                ],
            ],
        ],
        'Ion Slider' => [//para elmeentos de filtro sliders
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js',
                ],
            ],
        ],
        'Date Range Picker' => [//Rango de fechas
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js',
                ],
            ],
        ],
        'Jquery Confirm' => [//Confirm mas dinámico
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css',
                ],
            ],
        ],
        'Jquery Mask' => [//Para mascaras en elementos
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.js',
                ],
            ],
        ],
        'Financials' => [//Manejo de formatos financieros en javascript
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js',
                ],
            ],
        ],
        'SummerNote' => [//Texto enriquesido
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js',
                ],
            ],
        ],
        'Pignose' => [//Ver Calendario
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    // 'location' => 'js/components/pignose.calendar.min.js',
                    'location' => '//cdn.jsdelivr.net/npm/pg-calendar@1.4.9/dist/js/pignose.calendar.min.js',
                    
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    // 'location' => 'css/pignose.calendar.min.css',
                    'location' => '//cdn.jsdelivr.net/npm/pg-calendar@1.4.9/src/css/pignose.calendar.css',
                ],
            ],
        ],
        'Bootrstrap Switch' => [//Convierte check y radio en switch 
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap2/bootstrap-switch.min.css',
                ],
            ],
        ],
        'Bootrstrap Toggle Switch' => [//Otro  check y radio en switch 
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css',
                ],
            ],
        ],
        'Bootrstrap File Style 2' => [//Botón Upload con stilo
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-filestyle/2.1.0/bootstrap-filestyle.min.js',
                ],
            ],
        ],
        'DropZone JS' => [//Para subir multiples archivos
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/components/dropzone.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone.css',
                ],
            ],
        ],
        'signaturePad' => [//Para firmar Online
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js',
                ],
            ],
        ],
        'evoCalendar' => [//EvoCalendar
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/components/evo-calendar.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'css/evo-calendar.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'css/evo-calendar.orange-coral.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'css/evo-calendar.royal-navy.css',
                ],
            ],
        ],
        'jQueryMap' => [//jqueryMap
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/components/jquery-jvectormap.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/components/jquery-jvectormap-es-mill.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/components/query-jvectormap-us-aea.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/jvectormap/2.0.5/jquery-jvectormap.css',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#93-livewire
    */

    'livewire' => false,
];
