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
                    <h4 class="card-title">@lang('Tickets List')</h4>
                    <div class="table-responsive m-t-40">
                        <table id="tickets" class="display nowrap table table-hover table-striped table-bordered"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>@lang('Image')</th>
                                @if(Auth::user()->hasrole('management_company') || Auth::user()->hasrole('director'))
                                    <th>@lang('Building')</th>
                                @endif
                                <th>@lang('Title')</th>
                                <th>@lang('Message')</th>
                                <th>@lang('status')</th>
                                <th>@lang('Done Note')</th>
                                <th>@lang('Created')</th>
                                @if(Auth::user()->hasrole('management_company'))
                                <th>@lang('Actions')</th>
                                @endif
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>@lang('Image')</th>
                                @if(Auth::user()->hasrole('management_company') || Auth::user()->hasrole('director'))
                                    <th>@lang('Building')</th>
                                @endif
                                <th>@lang('Title')</th>
                                <th>@lang('Message')</th>
                                <th>@lang('status')</th>
                                <th>@lang('Done Note')</th>
                                <th>@lang('Created')</th>
                                @if(Auth::user()->hasrole('management_company'))
                                    <th>@lang('Actions')</th>
                                @endif
                            </tr>
                            </tfoot>
                            <tbody>

                            @foreach ($tickets as $ticket)
                                <tr>
                                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                    <td>@if ($ticket->image)<img style="width: 50px"
                                                                 src="{{ url('/images/'.$ticket->image) }}"/>@endif</td>
                                    @if(Auth::user()->hasrole('management_company') || Auth::user()->hasrole('director'))
                                        <td>{{ $ticket->name }}</td>
                                    @endif
                                    <td>{{ $ticket->title }}</td>
                                    <td>{{ $ticket->message }}</td>
                                    <td id="ticket_status">
                                        @if($ticket->status->slug == 'pending')
                                            <span class="label label-warning label-rounded">{{ $ticket->status->name }}</span>
                                        @elseif($ticket->status->slug == 'done')
                                            <span class="label label-success label-rounded">{{ $ticket->status->name }}</span>
                                        @endif
                                    </td>
                                    <td id="done_note">{{ $ticket->done_note }}</td>
                                    <td>{{ $ticket->created_at }}</td>
                                    @if(Auth::user()->hasrole('management_company'))
                                    <td id="ticket_actions">
                                        @if(!($ticket->status->slug == 'done'))
                                            <button type="button"
                                                    class="ticket-done-modal btn btn-rounded btn-success"
                                                    data-toggle="modal" data-target="#responsive-modal"><i
                                                        class="fa fa-check"></i> @lang('Done')</button>
                                            {{--@else
                                                <button type="button"
                                                        class="ticket-change-modal btn btn-rounded btn-success"
                                                        data-toggle="modal" data-target="#chnage-pic-modal">@lang('Change pic')</button>--}}
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                    <!-- sample modal content -->
                    <div id="responsive-modal" class="modal fade" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">@lang('Ticket Done')</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                            style="margin: -1rem auto -1rem -1rem !important;">×
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="message-text" class="control-label">@lang('Note'):</label>
                                            <textarea class="form-control" id="message-text"
                                                      data-ticket-id=""></textarea>
                                        </div>
                                        {{--<div class="form-group">
                                            <label class="control-label">@lang('Upload image')</label>
                                            <input name="image" type="file" id="buildingImage"
                                                   class="form-control">
                                        </div>--}}
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default waves-effect"
                                            data-dismiss="modal">@lang('Cancel')</button>
                                    <button type="button"
                                            class="ticket-done btn btn-success waves-effect waves-light">@lang('Add Note')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal -->

                    <!-- sample modal content -->
                    <div id="chnage-pic-modal" class="modal fade" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">@lang('Ticket Done')</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                            style="margin: -1rem auto -1rem -1rem !important;">×
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label class="control-label">@lang('Upload image')</label>
                                            <input name="image" type="file" id="buildingImageCh"
                                                   class="form-control">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default waves-effect"
                                            data-dismiss="modal">@lang('Cancel')</button>
                                    <button type="button"
                                            class="ticket-change-pic btn btn-success waves-effect waves-light">@lang('Add Note')</button>
                                </div>
                            </div>
                        </div>
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
    <script src="{{ asset('assets/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
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

            $('table img').click(function (evt) {
                $.imgParent = $(this).closest('td');
                var $modal = $('#pic-box');
                $(this).css('width', '100%');
                $modal.find('#image-wrapper').append(this);
                $modal.modal('show');
            });

            $('#pic-box').on('hidden.bs.modal', function () {
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

            $('button.ticket-done-modal').on('click', function (e) {
                row = $(this);
                ticket_id = row.closest("tr").find("input[name=ticket_id]").val();

                $('#responsive-modal form #message-text').attr("data-ticket-id", ticket_id);
            });

            $('button.ticket-change-modal').on('click', function (e) {
                row = $(this);
                ticket_id = row.closest("tr").find("input[name=ticket_id]").val();

                $('#chnage-pic-modal form #buildingImageCh').attr("data-ticket-id", ticket_id);
            });

            $('button.ticket-change-pic').on('click', function (e) {
                var data = new FormData($('#chnage-pic-modal form')[0]);
                data.append('ticket_id', $('#chnage-pic-modal form #buildingImageCh').data('ticket-id'));

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{!! csrf_token() !!}'
                    }
                });
                $.ajax({
                    url: "{{ url('/tickets/updatepic') }}",
                    method: 'post',
                    data: data,
                    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                    processData: false, // NEEDED, DON'T OMIT THIS
                    success: function (result) {
                        if (result['success']) {
                            window.location.reload();
                        }
                    }
                });
            });

            $('button.ticket-done').on('click', function (e) {

                ticket_id = $('#responsive-modal form #message-text').data('ticket-id');
                ticket_done_note = $('#responsive-modal form #message-text').val();

                var data = new FormData($('#responsive-modal form')[0]);
                data.append('ticket_id', $('#responsive-modal form #message-text').data('ticket-id'));

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{!! csrf_token() !!}'
                    }
                });
                $.ajax({
                    url: "{{ url('/tickets/updatestatus') }}",
                    method: 'post',
                    data: data,
                    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                    processData: false, // NEEDED, DON'T OMIT THIS
                    success: function (result) {
                        if (result['success']) {
                            row.closest("tr").find("td#ticket_status").html('<span class="label label-success label-rounded">' + result["status_name"] + '</span>');
                            row.closest("tr").find("td#done_note").html(result["done_note"]);
                            row.closest("tr").find("td#ticket_actions").html('');
                        }
                    }
                });

                $("[data-dismiss=modal]").trigger({type: "click"});
            });
        });
        $('#tickets').DataTable({
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