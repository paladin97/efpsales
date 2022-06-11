<!-- Notifications Dropdown Menu -->
<li class="nav-item">
    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fad fa-expand-arrows-alt"></i>
    </a>
</li>
@if (!(Auth::user()->hasRole('teacher')))
    @include('notifications.index')
@endif

<!-- End Notifications Dropdown Menu -->
@if((Auth::user()->hasRole('superadmin'))|| (Auth::user()->hasRole('admin')) )
    <li class="nav-item">
        <a class="nav-link" href="#" data-widget="control-sidebar"
            @if(!config('adminlte.right_sidebar_slide'))
                data-controlsidebar-slide="false"
            @endif
            @if(config('adminlte.right_sidebar_scrollbar_theme', 'os-theme-light') != 'os-theme-light')
                data-scrollbar-theme="{{ config('adminlte.right_sidebar_scrollbar_theme') }}"
            @endif
            @if(config('adminlte.right_sidebar_scrollbar_auto_hide', 'l') != 'l')
                data-scrollbar-auto-hide="{{ config('adminlte.right_sidebar_scrollbar_auto_hide') }}"
            @endif>
            @if (Auth::user()->hasRole('superadmin'))
                <i class="{{ config('adminlte.right_sidebar_icon') }}"></i>
            @endif
        </a>
    </li>
@endif
