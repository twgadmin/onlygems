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

                                <a class="dropdown-item btn btn-default" href="{!! asset('add-new-card') !!}">
                                    <i class="fa fa-fw fa-plus" aria-hidden="true"></i>
                                    {!! trans('cardhedger.buttons.create-new') !!}
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

                        @if(config('transactions.enableSearchUsers'))
                            @include('partials.search-users-form')
                        @endif

                        <div class="table-responsive products-table">
                            <table data-url="{!! asset('fetch-cardhedger-list') !!}" class="table table-striped table-sm data-table">
                                <caption id="user_count">
                                    {{-- {{ trans_choice('transactions.products-table.caption', 1, ['userscount' => $users->count()]) }} --}}
                                </caption>
                                <thead class="thead">
                                    <tr>
                                        <th>{!! trans('cardhedger.cardhedger-table.id') !!}</th>

                                        <th class="hidden-sm hidden-xs hidden-md">{!! trans('cardhedger.cardhedger-table.name') !!}</th>

                                        <th class="hidden-sm hidden-xs hidden-md">{!! trans('cardhedger.cardhedger-table.grade') !!}</th>

                                        <th class="hidden-sm hidden-xs hidden-md">{!! trans('cardhedger.cardhedger-table.price') !!}</th>

                                        <th class="hidden-sm hidden-xs hidden-md">{!! trans('cardhedger.cardhedger-table.card-number') !!}</th>

                                        <th class="hidden-sm hidden-xs hidden-md">{!! trans('cardhedger.cardhedger-table.card-variant') !!}</th>

                                        <th class="hidden-sm hidden-xs hidden-md">{!! trans('cardhedger.cardhedger-table.card-description') !!}</th>

                                        <th class="hidden-sm hidden-xs hidden-md">{!! trans('cardhedger.cardhedger-table.sale-date') !!}</th>

                                        <th class="hidden-sm hidden-xs hidden-md">{!! trans('usersmanagement.users-table.created') !!}</th>

                                    </tr>
                                </thead>
                                <tbody id="products_table">

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
    @if(config('transactions.tooltipsEnabled'))
        @include('scripts.tooltips')
    @endif
    @if(config('transactions.enableSearchUsers'))
        @include('scripts.search-users')
    @endif
@endsection
