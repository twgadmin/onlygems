@extends('layouts.app')

@section('template_title')
	{{ trans('titles.activation') }}
@endsection

@section('content')
	<div class="container">
    	<div class="row">
            <div class="col-lg-12 col-xs-12 col-sm-12 col-sm-12">
                <h4>Email & Phone Number Verification</h4>
                @include('partials.errors')
                @include('partials.status')
            </div>
        </div>
		@if(Auth::user()->signup_confirmation_ip_address=='')
        <div class="row">
			<div class="col-md-10 offset-md-1">
				<div class="card card-default">
					<div class="card-header">{{ trans('titles.activation') }}</div>
					<div class="card-body">
						<p>{{ trans('auth.regThanks') }}</p>
						<p>{{ trans('auth.anEmailWasSent',['email' => $email, 'date' => $date ] ) }}</p>
						<p>{{ trans('auth.clickInEmail') }}</p>
						<p><a href='{!! route('send.verification.email') !!}' class="btn btn-primary">Resend Email</a></p>
					</div>
				</div>
			</div>
		</div>
        @endif        
        @if((Auth::user()->phone_verified_at=='')||(Auth::user()->phone_verified_at=='0000-00-00 00:00:00'))
        <div class="row">
			<div class="col-md-10 offset-md-1">
				<div class="card card-default">
					<div class="card-header">Mobile Phone Verification Requried</div>
                    <form method="POST" action="{!! route('verify.phone.number') !!}" accept-charset="UTF-8" class="form-horizontal" role="form" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
					<div class="card-body">
						<p>Sms Code:</p>
						<p><input type="text" name="code" required="required" class="form-control"></p>
						<p><input type="submit" name="submit" value="Verify" class="btn btn-primary"></p>
						<?php /*<p><a href='/activation' class="btn btn-primary">{{ trans('auth.clickHereResend') }}</a></p> */ ?>
                        <a href="{!! route('send.verification.code') !!}">Resend Code<a>
					</div>
                    </form>
				</div>
			</div>
		</div>
        @endif        
	</div>
@endsection
