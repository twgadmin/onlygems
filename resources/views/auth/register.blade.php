<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }} | Registration Page</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
          integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
          crossorigin="anonymous"/>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <a href="{{ url('/home') }}"><img src="<?php echo asset('storage/logo.png'); ?>"/> <?php /*<b>{{ config('app.name') }}</b>*/ ?></a>
    </div>
    <div class="card">
        <div class="card-body {{-- register-card-body --}}">
            <p class="login-box-msg">Register New Account</p>
               {!! Form::open(array('route' => 'register', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}               
			   <div class="input-group mb-3">
	               <input id="first_name" class="form-control @error('first_name') is-invalid @enderror" placeholder="{!! trans('forms.create_user_ph_firstname') !!}" name="first_name" type="text" value="{!! old('first_name') !!}"/>
                   <div class="input-group-append">
                        <div class="input-group-text"><span class="fas {{ trans('forms.create_user_icon_firstname') }}"></span></div>
                    </div>
                    @if ($errors->has('first_name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('first_name') }}</strong>
                    </span>
                    @enderror
                </div>

               
			   <div class="input-group mb-3">
                    <input id="last_name" class="form-control @error('last_name') is-invalid @enderror" placeholder="Last Name" name="last_name" type="text" value="{!! old('last_name') !!}"/>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas {{ trans('forms.create_user_icon_lastname') }}"></span></div>
                    </div>
                    @if ($errors->has('last_name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('last_name') }}</strong>
                    </span>
                    @enderror
               </div>
            
               
			   <div class="input-group mb-3">
		             <input id="email" class="form-control @error('email') is-invalid @enderror" placeholder="{!! trans('forms.create_user_ph_email') !!}" name="email" type="text" value="{!! old('email') !!}"/>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas {{ trans('forms.create_user_icon_email') }}"></span></div>
                    </div>
                    @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @enderror
               </div>            
                
			   <div class="input-group mb-3">
		            <input id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" placeholder="{!! trans('forms.create_user_ph_phone_number') !!}" name="phone_number" type="text" value="{!! old('phone_number') !!}"/>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas {{ trans('forms.create_user_icon_phone_number') }}"></span></div>
                    </div>
                    @if ($errors->has('phone_number'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('phone_number') }}</strong>
                    </span>
                    @enderror
               </div>                
			   <div class="input-group mb-3">
		            <input id="address" class="form-control location @error('address') is-invalid @enderror" placeholder="{!! trans('forms.create_user_ph_address') !!}" name="address" type="text" value="{!! old('address') !!}"/>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas {{ trans('forms.create_user_icon_address') }}"></span></div>
                    </div>
                    @if ($errors->has('address'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('address') }}</strong>
                    </span>
                    @enderror
               </div>
               <div class="input-group mb-3">
		            <input id="phone_number" class="form-control @error('walletid') is-invalid @enderror" placeholder="{!! trans('forms.create_user_ph_wallet_id') !!}" name="walletid" type="text" value="{!! old('walletid') !!}"/>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas {{ trans('forms.create_user_icon_wallet_id') }}"></span></div>
                    </div>
                   <div style="font-size: 12px;font-weight: 600;">Must be a Non-Custodial Wallet such as MetaMask</div> 
                    @if ($errors->has('walletid'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('walletid') }}</strong>
                    </span>
                    @enderror
               </div> 
               
               <input type="hidden" name="country" class="country" value=""/>
               <input type="hidden" name="zip" class="zip" value=""/>
               <input type="hidden" name="city" class="city" value=""/>
               <input type="hidden" name="state" class="state" value=""/>
            {!! Form::button(trans('forms.register_user_button_text'), array('class' => 'btn btn-primary margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
     		{!! Form::close() !!}
            <a href="{{ route('login') }}" class="text-center">I already have account</a>
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->

    <!-- /.form-box -->
</div>
<!-- /.register-box -->

<script src="{{ asset('js/app.js') }}" defer></script>
@if(config('settings.googleMapsAPIStatus'))
<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxE_M2fe9OT0SG7x913Z4qxtfP6n3wRu0&libraries=places">
</script>
    @include('scripts.gmaps-address-lookup-api3')
@endif
</body>
</html>
