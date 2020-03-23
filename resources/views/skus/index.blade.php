@extends('layouts.app')

@section('content')
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success"> @lang($message)
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('Skus List')</h4>
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Description')</th>
                                <th>@lang('Text')</th>
                                <th>@lang('Regression')</th>
                                <th>@lang('Duration')</th>
                                <th>@lang('Created')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Description')</th>
                                <th>@lang('Text')</th>
                                <th>@lang('Regression')</th>
                                <th>@lang('Duration')</th>
                                <th>@lang('Created')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                            </tfoot>
                            <tbody>

                            @foreach ($skus as $sku)
                                <tr>
                                    <td>{{ $sku->name }}</td>
                                    <td>{{ $sku->description }}</td>
                                    <td>{{ $sku->free_text }}</td>
                                    <td>{{ $sku->regression?"Yes":"No" }}</td>
                                    @foreach($durations as $duration)
                                        @if($duration->сyclic_days == $sku->сyclic_days)
                                            <td>{{ $duration->name }}</td>
                                            @php($flag = true)
                                            @break
                                        @endif
                                    @endforeach
                                    @if(empty($flag))
                                        <td>{{ $sku->сyclic_days }}</td>
                                    @endif
                                    <td>{{ $sku->created_at }}</td>
                                    <td>
                                        <button type="button"
                                                data-sku-edit-link="{{ route('skus.edit', $sku->id) }}"
                                                class="btn waves-effect waves-light btn-rounded btn-info sku-edit">
                                            <i class="fa fa-pencil"></i></button>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->

@section('footer-js')
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--stickey kit -->
    <script src="../assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="../assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- This is data table -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>

    <script src="{{ asset('assets/plugins/toast-master/js/jquery.toast.js') }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
            var table = $('#example').DataTable({
                "columnDefs": [{
                    "visible": false,
                    "targets": 2
                }],
                "order": [
                    [2, 'asc']
                ],
                "displayLength": 25,
                "drawCallback": function (settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;
                    api.column(2, {
                        page: 'current'
                    }).data().each(function (group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                            last = group;
                        }
                    });
                }
            });
            // Order by the grouping
            $('#example tbody').on('click', 'tr.group', function () {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });

            $('button.sku-edit').click(function () {
                var link = $(this).attr("data-sku-edit-link");

                window.location.replace(link);
            });
        });
        $('#example23').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "language": {
                "url": "{{ asset('../js/datatables/Hebrew.json') }}"
            }
        });
    </script>
@endsection
@endsection