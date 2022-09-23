<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"/>
    <link rel="stylesheet" href="{!! url('assets/datatables/css/jquery.dataTables.min.css') !!}" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="{!! url('assets/select/dist/css/ajax-bootstrap-select.css') !!}"/>
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/css/bootstrap-datetimepicker.min.css">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap-theme.min.css"> --}}
    <link rel="stylesheet" href="{!! url('assets/tags-input/bootstrap-tagsinput.css') !!}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    @yield('third_party_stylesheets')

    @stack('page_css')

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Main Header -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">

                    {{-- <img src="@if (Auth::User()->profile->avatar_status == 1) {{ Auth::User()->profile->avatar }} @else {{ Gravatar::get(Auth::User()->email) }} @endif" alt="{{ Auth::User()->name }}" class="user-image img-circle elevation-2"> --}}

                    <img id="user_selected_avatar" class="user-image img-circle elevation-2" src="@if (isset(Auth::User()->profile)&&Auth::User()->profile->avatar !== NULL) {{ Auth::User()->profile->avatar }} @else <?php echo asset('storage/avatar.png'); ?> @endif" alt="{{ Auth::User()->name }}">

                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <!-- User image -->
                    <li class="user-header bg-primary">
                        {{-- <img src="@if ($user->profile->avatar_status == 1) {{ $user->profile->avatar }} @else {{ Gravatar::get($user->email) }} @endif" alt="{{ $user->name }}" class="user-image img-circle elevation-2"> --}}

                        {{-- <img src="@if (Auth::User()->profile->avatar_status == 1) {{ Auth::User()->profile->avatar }} @else {{ Gravatar::get(Auth::User()->email) }} @endif" alt="{{ Auth::User()->name }}" class="user-image img-circle elevation-2"> --}}

                        <img id="user_selected_avatar" class="user-image img-circle elevation-2" src="@if (Auth::User()->profile->avatar !== NULL) {{ Auth::User()->profile->avatar }} @else <?php echo asset('storage/avatar.png'); ?> @endif" alt="{{ Auth::User()->name }}">

                        <p>
                            {{ Auth::user()->name }}
                            <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <a href="{{ url('/profile/'.Auth::user()->name) }}" class="btn btn-default btn-flat">Profile</a>
                        <a href="#" class="btn btn-default btn-flat float-right" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Sign out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Left side column. contains the logo and sidebar -->
@include('layouts.sidebar')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content">
            @yield('content')
        </section>
    </div>

    <!-- Main Footer -->
    <footer class="main-footer">
        {{-- <div class="float-right d-none d-sm-block">
            <b>Version</b> 3.0.5
        </div> --}}
        <strong>Copyright &copy; <?php echo date('Y'); ?> Fred Fund - NFT Collectible Asset Fund</strong> All rights
        reserved.
    </footer>
</div>
{{-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> --}}



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

<script type="text/javascript" src="{!! url('assets/select/dist/js/ajax-bootstrap-select.js') !!}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment-with-locales.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/js/bootstrap-datetimepicker.min.js"></script>

@if(config('usersmanagement.enabledDatatablesJs'))
    @include('scripts.datatables')
@endif



{{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

<script src="{!! url('assets/tinymce/js/tinymce.min.js') !!}"></script>

@yield('third_party_scripts')

@stack('page_scripts')

@include('scripts.form-modal-script')

@if(config('settings.googleMapsAPIStatus'))
<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxE_M2fe9OT0SG7x913Z4qxtfP6n3wRu0&libraries=places">
</script>
    @include('scripts.gmaps-address-lookup-api3')
@endif

@include('scripts.save-modal-script')
@include('scripts.delete-modal-script')
@include('scripts.user-avatar-dz')

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script> --}}

<script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>
<script src="{!! url('assets/tags-input/bootstrap-tagsinput.min.js') !!}"></script>
<script src="{!! url('assets/double-scroll/jquery.doubleScroll.js') !!}"></script>


<script type="text/javascript" src="{{ asset('js/custom.js') }}" defer></script>
<script type="text/javascript">
$(document).ready( function () {
    $('#sample1').DataTable();
    $('.has-treeview').click(function(){
        if ($('.has-treeview').hasClass('menu-open')){
            $('.has-treeview').removeClass('menu-open');
            $('.nav-treeview').css('display','none');
        } else {
            $('.has-treeview').addClass('menu-open');
            $('.nav-treeview').css('display','block');    
        }   
    });
    
    $('#order_processed').on('click',function(){
        debugger;
        var data = true;
        if (typeof $("input[name='minted']:checked").val() === "undefined") {
            $('#minted_error').html('Minted is requried!');
            $("#minted_error").css("color", "red");
            $('#minted').focus();
            data = false;                   
        }else{
            $('#minted_error').empty().html('');
        }
        /*
        if(($('#minted').val()=='')||($('#minted').val()==undefined)){
            debugger;
            $('#minted_error').html('Minted is requried!');
            $("#minted_error").css("color", "red");
            $('#minted').focus();
            data = false;                   
        }else{
            debugger;
            $('#minted_error').empty().html('');
        }
        */
        if (typeof $("input[name='sent_to_wallet']:checked").val() === "undefined") {
            $('#sent_to_wallet_error').html('Sent To Wallet is requried!');
            $("#sent_to_wallet_error").css("color", "red");
            $('#sent_to_wallet').focus();
            data = false;                   
        }else{
            $('#sent_to_wallet_error').empty().html('');
        }
        /*        
        if(($('#sent_to_wallet').val()=='')||($('#sent_to_wallet').val()==undefined)){
            debugger;
            $('#sent_to_wallet_error').html('Sent To Wallet is requried!');
            $("#sent_to_wallet_error").css("color", "red");
            $('#sent_to_wallet').focus();
            data = false;                   
        }else{
            debugger;
            $('#sent_to_wallet_error').empty().html('');
        }
        */
        if(data === true){
            $("#processedorder").submit();  
        }
        
        return false;
    });
});
</script>
</body>
</html>
