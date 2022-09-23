@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-xs-12 col-sm-12 col-sm-12">
                <h4>Dashboard</h4>
                @include('partials.errors')
                @include('partials.status')
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                <h3>1</h3>
                <p>Users</p>
                </div>
                <div class="icon">
                    <i class="far fa-file-word"></i>
                </div>
                @role('user')
                    <a href="{{ url('/profile/'.Auth::user()->name) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                @endrole
                
                @role(['admin','subadmin'])
                <a href="{!! asset('users') !!}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                @endrole
            </div>
            </div>            
        </div>
    </div>
@stop
