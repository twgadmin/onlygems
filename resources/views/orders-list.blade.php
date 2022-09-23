@extends('layouts.app')

@section('template_title')
    {!! trans('usersmanagement.showing-all-users') !!}
@endsection

@section('template_linked_css')
    @if(config('usersmanagement.enabledDatatablesJs'))
        <link rel="stylesheet" type="text/css" href="{{ config('usersmanagement.datatablesCssCDN') }}">
    @endif
    <style type="text/css" media="screen">
        .users-table {
            border: 0;
        }
        .users-table tr td:first-child {
            padding-left: 15px;
        }
        .users-table tr td:last-child {
            padding-right: 15px;
        }
        .users-table.table-responsive,
        .users-table.table-responsive table {
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        @include('partials.errors')
                        @include('partials.status')
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Orders</span>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(config('transactions.enableSearchUsers'))
                            @include('partials.search-users-form')
                        @endif

                        <div class="table-responsive products-table">
                            <table id="sample1" class="table table-striped table-sm">
                                <thead class="thead">
                                    <tr>
                                        <td class="hidden-sm hidden-xs hidden-md">User</td>
                                        <td class="hidden-sm hidden-xs hidden-md">Qty</td>
                                        <td class="hidden-sm hidden-xs hidden-md">Status</td>
                                        <td class="hidden-sm hidden-xs hidden-md">Stage</td>
                                        <td class="hidden-sm hidden-xs hidden-md">Date Time</td>
                                        <td class="hidden-sm hidden-xs hidden-md">Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                @role('admin')
                                	@foreach($orders as $order)
                                            <tr>
                                                <td>{!! $order->user !!}</td>
                                                <td>{!! $order->qty !!}</td>
                                                <td>{!! $order->status !!}</td>
                                                <td>{!! $order->stage!!}</td>
                                                <td>{!! $order->created_at !!}</td>
                                                <td>
                    <a class="btn btn-sm btn-info btn-block edit-btn" href="{!! route('order.details',$order->id) !!}" data-toggle="tooltip" title="View"><i class="fa fa-search"></i></a>
            <?php /*<a class="btn btn-sm btn-primary btn-block edit-btn" href="users/17/edit" data-toggle="tooltip" title="Update"><i class="fa fa-shopping-basket"></i></a>*/ ?>
												</td>
                                            </tr>
                                    @endforeach
								@endrole
								@role('user')
                                	@foreach($orders as $order)
                                    	@if($order->user_id==Auth::user()->id)
                                            <tr>
                                                <td>{!! $order->user !!}</td>
                                                <td>{!! $order->qty !!}</td>
                                                <td>{!! $order->status !!}</td>
                                                <td>{!! $order->stage !!}</td>
                                                <td>{!! $order->created_at !!}</td>
                                                <td><a class="btn btn-sm btn-info btn-block edit-btn" href="{!! route('order.details',$order->id) !!}" data-toggle="tooltip" title="View"><i class="fa fa-search"></i></a></td>
                                            </tr>
                                        @endif
                                    @endforeach
								@endrole
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
    {{-- @if ((count($users) > config('usersmanagement.datatablesJsStartCount')) && config('usersmanagement.enabledDatatablesJs')) --}}

    {{-- @endif --}}
    {{-- @include('scripts.datatables') --}}

    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    @if(config('transactions.tooltipsEnabled'))
        @include('scripts.tooltips')
    @endif
    @if(config('transactions.enableSearchUsers'))
        @include('scripts.search-users')
    @endif
@endsection