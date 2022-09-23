<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a style="padding : 22px 0px 50px 50px" href="{{ route('home') }}" class="brand-link">
        <img src="<?php echo asset('storage/logo.png'); ?>" alt="FredFund Logo" class="brand-image  elevation-3">
        {{-- <p>
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
        </p> --}}
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu')
            </ul>
        </nav>
    </div>

</aside>
