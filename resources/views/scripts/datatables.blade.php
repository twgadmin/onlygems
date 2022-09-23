{{-- FYI: Datatables do not support colspan or rowpan --}}

    {{-- <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script> --}}
    {{-- <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script> --}}

{{-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> --}}

<script src="{!! url('assets/datatables/js/jquery.dataTables.min.js') !!}"></script>

<script type="text/javascript">
    jQuery(function ($) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }); // set csrf token for all AJAX requests

        if($('.data-table').length > 0) {

            var url = $('.data-table').data('url');

            var viewusers = $(".data-table").DataTable({
                autoWidth: false,
                "order": [[ 0, 'desc' ]],
                "aoColumnDefs": [{
                    bSortable: false,
                    // aTargets: [2, 3, 4, 5, 6, 7, 8]
                }],
                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    type: 'POST',
                    data: function (d) {
                        d.card_category = $("#cards-category").val();
                        d.cards_sort = $('#cards-sort').val();
                    }
                },
                fnPreDrawCallback: function () {
                    $('.dataTables_length').addClass("col-sm-3");
                    $(".dataTables_filter").addClass("col-sm-3");
                    $('#type-filter').addClass("col-sm-6");
                    $(".data-table_filter").addClass("pull-right");
                    $(".dataTables_processing").removeClass("card");
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex) {
                    $("td:eq(0)", nRow).addClass("text-center");
                    $("td:gt(2)", nRow).addClass("text-center");
                },
                initComplete: function () {
                    $(".dataTables_processing").removeClass("card");

                },
                language: {
                    processing: '<div class="lds-ripple"><div></div><div></div></div>',
                    search: '',
                    searchPlaceholder: "Search...",
                    sLengthMenu: "_MENU_",
                }
            });


            /** Cut doc type dropdown and paste it inside table header */
            var allfltr = $(".docType-wrapper").html();
            if (allfltr != undefined) {
                $(".docType-wrapper").html('');
                $(".dataTables_length").after(allfltr);
            }

            /** reload datatable on file type change */
            $("#cards-category").on("change", function () {
                $(".data-table").DataTable().ajax.reload();
            });

            $("#cards-sort").on("change", function () {
                $(".data-table").DataTable().ajax.reload();
            });
        }
    });
</script>
