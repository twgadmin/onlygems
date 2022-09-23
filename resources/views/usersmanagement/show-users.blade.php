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

                            <span id="card_title">
                            </span>

                            <div class="btn-group pull-right btn-group-xs">                            
                                {{-- <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v fa-fw" aria-hidden="true"></i>
                                    <span class="sr-only">
                                        {!! trans('usersmanagement.users-menu-alt') !!}
                                    </span>
                                </button> --}}

                                <a class="dropdown-item btn btn-default" href="{!! url('/users/create') !!}">
                                    <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                                    {!! trans('usersmanagement.buttons.create-new') !!}
                                </a>

                                {{-- <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="/users/create">
                                        <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                                        {!! trans('usersmanagement.buttons.create-new') !!}
                                    </a>
                                    <a class="dropdown-item" href="/users/deleted">
                                        <i class="fa fa-fw fa-group" aria-hidden="true"></i>
                                        {!! trans('usersmanagement.show-deleted-users') !!}
                                    </a>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        @if(config('usersmanagement.enableSearchUsers'))
                            @include('partials.search-users-form')
                        @endif

                        <div class="table-responsive users-table">
                            <table data-url="{!! asset('users-list') !!}" class="table table-striped table-sm data-table">
                                <caption id="user_count">
                                    {{-- {{ trans_choice('usersmanagement.users-table.caption', 1, ['userscount' => $users->count()]) }} --}}
                                </caption>
                                <thead class="thead">
                                    <tr>
                                        <th>{!! trans('usersmanagement.users-table.id') !!}</th>
                                        <th>{!! trans('usersmanagement.users-table.name') !!}</th>
                                        <th>{!! trans('usersmanagement.users-table.role') !!}</th>
                                        <th class="hidden-xs">{!! trans('usersmanagement.users-table.email') !!}</th>
                                        <th class="hidden-xs">{!! trans('usersmanagement.users-table.walletid') !!}</th>
                                        <th class="hidden-xs">{!! trans('usersmanagement.users-table.fname') !!}</th>
                                        <th class="hidden-xs">{!! trans('usersmanagement.users-table.lname') !!}</th>
                                        {{-- <th>{!! trans('usersmanagement.users-table.role') !!}</th> --}}
                                        <th>{!! trans('usersmanagement.users-table.phone') !!}</th>
                                        <th>{!! trans('usersmanagement.users-table.job_title') !!}</th>
                                        <th style="width:200px !important">{!! trans('usersmanagement.users-table.address') !!}</th>
                                        <th class="hidden-sm hidden-xs hidden-md">{!! trans('usersmanagement.users-table.created') !!}</th>
                                        <th>{!! trans('usersmanagement.users-table.actions') !!}</th>
                                        {{-- <th class="no-search no-sort"></th>
                                        <th class="no-search no-sort"></th> --}}
                                    </tr>
                                </thead>
                                <tbody id="users_table">

                                </tbody>

                                {{-- <tbody id="search_results"></tbody> --}}
                                {{-- @if(config('usersmanagement.enableSearchUsers'))
                                    <tbody id="search_results"></tbody>
                                @endif --}}

                            </table>

                            {{-- @if(config('usersmanagement.enablePagination'))
                                {{ $users->links() }}
                            @endif --}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- @include('modals.modal-delete') --}}

@endsection

@section('footer_scripts')
    {{-- @if ((count($users) > config('usersmanagement.datatablesJsStartCount')) && config('usersmanagement.enabledDatatablesJs')) --}}

    {{-- @endif --}}
    {{-- @include('scripts.datatables') --}}

    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    @if(config('usersmanagement.tooltipsEnabled'))
        @include('scripts.tooltips')
    @endif
    @if(config('usersmanagement.enableSearchUsers'))
        @include('scripts.search-users')
    @endif
@endsection
