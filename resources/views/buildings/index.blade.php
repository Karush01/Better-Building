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

    <!-- Modal -->
    <div id="pic-box" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Picture')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="image-wrapper"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('Buildings List')</h4>
                    <div class="table-responsive m-t-40">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>@lang('Image')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('City')</th>
                                <th>@lang('Address')</th>
                                <th>@lang('Description')</th>
                                <th>@lang('Management Company')</th>
                                <th>@lang('Created')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>@lang('Image')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('City')</th>
                                <th>@lang('Address')</th>
                                <th>@lang('Description')</th>
                                <th>@lang('Management Company')</th>
                                <th>@lang('Created')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                            </tfoot>
                            <tbody>

                            @foreach ($buildings as $building)
                                <tr>
                                    <td>@if ($building->image)<img style="width: 50px" src="{{ asset('/images/'.$building->image) }}"/>@endif</td>
                                    <td>{{ $building->name }}</td>
                                    <td>{{ $building->city }}</td>
                                    <td>{{ $building->address }}</td>
                                    <td>{{ $building->description }}</td>
                                    <td>{{ $building->managementcompany->name }}</td>
                                    <td>{{ $building->created_at }}</td>
                                    <td>
                                        <button type="button" data-building-link="{{ route('buildings.show', $building->id) }}" class="btn waves-effect waves-light btn-rounded btn-info building-show"><i class="fa fa-eye"></i></button>
                                        @if(Auth::user()->hasrole('director'))
                                            <button type="button" data-building-edit-link="{{ route('buildings.edit', $building->id) }}" class="btn waves-effect waves-light btn-rounded btn-info building-edit"><i class="fa fa-pencil"></i></button>
                                            <button type="button"
                                                    data-toggle="modal" data-target="#deleteModal" data-delete-link="{{ route('buildings.destroy', $building->id) }}"
                                                    class="btn waves-effect waves-light btn-rounded btn-danger building-delete">
                                                <i class="fa fa-trash"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    <!-- sample modal content -->
                    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">@lang('Delete Building')</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>@lang('delete building confirm line 1')</p>
                                    <p>@lang('delete building confirm line 2')</p>
                                </div>
                                <div class="modal-footer">
                                    <form action=""
                                          method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                data-toggle="modal" data-target="#myModal" class="btn waves-effect waves-light btn-rounded btn-danger building-delete">
                                            @lang('Approve Delete')</button>
                                    </form>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
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
            $('button.building-delete').on('click', function () {
                $('#deleteModal form').attr('action', $(this).data('delete-link'));
            });

            $('table img').click(function(evt){
                $.imgParent = $(this).closest('td');
                var $modal = $('#pic-box');
                $(this).css('width', '100%');
                $modal.find('#image-wrapper').append(this);
                $modal.modal('show');
            });

            $('#pic-box').on('hidden.bs.modal', function(){
                var $img = $('#image-wrapper img');
                $img.css('width', '50px');
                $.imgParent.append($img);
            })


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
                $('button.building-show').click(function() {
                    var link = $(this).attr("data-building-link");

                    window.location.replace(link);
                });
            $('button.building-edit').click(function() {
                var link = $(this).attr("data-building-edit-link");

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