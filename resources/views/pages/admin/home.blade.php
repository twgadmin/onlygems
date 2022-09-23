@extends('layouts.app')

@section('template_title')
    Welcome {{ Auth::user()->name }}
@endsection

@section('head')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 offset-lg-1">
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          {{-- <h3>{{$docs}}</h3> --}}
          <p>Docs</p>
        </div>
        <div class="icon">
            <i class="far fa-file-word"></i>
        </div>
        <!-- <a href="/user" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
      </div>
    </div>

    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          {{-- <h3>{{$sheets}}</h3> --}}
          <p>Sheets</p>
        </div>
        <div class="icon">
            <i class="far fa-file-excel"></i>
        </div>
        <!-- <a href="/user" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
      </div>
    </div>

    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-danger">
        <div class="inner">
          {{-- <h3>{{$slides}}</h3> --}}
          <p>Slides</p>
        </div>
        <div class="icon">
            <i class="far fa-file-powerpoint"></i>
        </div>
        <!-- <a href="/user" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
      </div>
    </div>

    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-primary">
        <div class="inner">
          {{-- <h3>{{$others}}</h3> --}}
          <p>Others</p>
        </div>
        <div class="icon">
            <i class="far fa-file-alt"></i>
        </div>
        <!-- <a href="/user" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
      </div>
    </div>
  </div>


            </div>
        </div>
    </div>

@endsection
