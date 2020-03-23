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
                    <h4 class="card-title">@lang('Building Tasks')</h4>
                    <div class="table-responsive m-t-40">
                        <table id="tasks" class="display nowrap table table-hover table-striped table-bordered"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>@lang('Image')</th>
                                <th>@lang('Sku')</th>
                                <th>@lang('Management Company')</th>
                                <th>@lang('Building')</th>
                                <th>@lang('Note')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Done Note')</th>
                                <th>@lang('Due date')</th>
                                <th>@lang('Done date')</th>
                                @if(Auth::user()->hasrole('management_company'))
                                    <th>@lang('Actions')</th>
                                @endif
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>@lang('Image')</th>
                                <th>@lang('Sku')</th>
                                <th>@lang('Management Company')</th>
                                <th>@lang('Building')</th>
                                <th>@lang('Note')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Done Note')</th>
                                <th>@lang('Due date')</th>
                                <th>@lang('Done date')</th>
                                @if(Auth::user()->hasrole('management_company'))
                                    <th>@lang('Actions')</th>
                                @endif
                            </tr>
                            </tfoot>
                            <tbody>

                            @foreach($tasks as $task)
                                <tr>
                                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                                    <td>@if ($task->image)<img style="width: 50px"
                                                                 src="{{ asset('/images/'.$task->image) }}"/>@endif</td>
                                    <td>{{ $task->sku->name }}</td>
                                    <td>{{ $task->building->managementCompany->name }}</td>
                                    <td>{{ $task->building->name }}</td>
                                    <td>{{ $task->note }}</td>
                                    <td id="task_status">
                                        @if($task->status->slug == 'pending')
                                            <span class="label label-warning label-rounded">{{ $task->status->name }}</span>
                                        @elseif($task->status->slug == 'done')
                                            <span class="label label-success label-rounded">{{ $task->status->name }}</span>
                                        @else
                                            <span class="label label-danger label-rounded">{{ $task->status->name }}</span>
                                        @endif
                                    </td>
                                    <td id="done_note">{{ $task->done_note }}</td>
                                    <td>{{ $task->last_date }}</td>
                                    <td>
                                        @if($task->status->slug == 'done' || $task->status->slug == 'done_overdue')
                                            {{ $task->updated_at }}
                                        @endif
                                    </td>
                                    @if(Auth::user()->hasrole('management_company'))
                                        <td id="task_actions">
                                            @if($task->sku->regression)
                                                <?php  $check_date = date('Y-m-d', strtotime('-3 days', strtotime($task->last_date))); ?>
                                            @else
                                                <?php $check_date = now(); ?>
                                            @endif

                                            @if(!($task->status->slug == 'done') && !($task->status->slug == 'done_overdue') && (date('Y-m-d') >= $check_date))
                                                <button type="button"
                                                        class="task-done-modal btn btn-rounded btn-success"
                                                        data-toggle="modal" data-target="#responsive-modal"><i
                                                            class="fa fa-check"></i> @lang('Done')</button>
                                            @elseif(($task->status->slug == 'done') || ($task->status->slug == 'done_overdue'))
                                                <button type="button"
                                                        class="task-change-modal btn btn-rounded btn-success"
                                                        data-toggle="modal"
                                                        data-target="#chnage-pic-modal">@lang('Change pic')</button>
                                            @endif
                                            @else
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
                                    <h4 class="modal-title">@lang('Task Done')</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                            style="margin: -1rem auto -1rem -1rem !important;">×
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="form-group">
                                            <label for="message-text" class="control-label">@lang('Note'):</label>
                                            <textarea class="form-control" id="message-text" data-task-id=""></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">@lang('Add document')</label>
                                            <input name="image" type="file" id="taskImage"
                                                   class="form-control">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default waves-effect"
                                            data-dismiss="modal">@lang('Cancel')</button>
                                    <button type="button"
                                            class="task-done btn btn-success waves-effect waves-light">@lang('Add Note')</button>
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
                                    <h4 class="modal-title">@lang('Task Done')</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                            style="margin: -1rem auto -1rem -1rem !important;">×
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label class="control-label">@lang('Upload image')</label>
                                            <input name="image" type="file" id="taskImageCh"
                                                   class="form-control">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default waves-effect"
                                            data-dismiss="modal">@lang('Cancel')</button>
                                    <button type="button"
                                            class="task-change-pic btn btn-success waves-effect waves-light">@lang('Add Note')</button>
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

            $('#example tbody').on('click', 'tr.group', function () {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });

            $('#responsive-modal').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
            })

            $('button.task-done-modal').on('click', function (e) {
                row = $(this);
                task_id = row.closest("tr").find("input[name=task_id]").val();

                $('#responsive-modal form #message-text').attr("data-task-id", task_id);
            });

            $('button.task-done').on('click', function (e) {
                /*task_id = $('#responsive-modal form #message-text').data('task-id');
                task_done_note = $('#responsive-modal form #message-text').val();*/

                var data = new FormData($('#responsive-modal form')[0]);
                data.append('task_id', $('#responsive-modal form #message-text').data('task-id'));

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{!! csrf_token() !!}'
                    }
                });
                $.ajax({
                    url: "{{ url('/tasks/updatestatus') }}",
                    method: 'post',
                    data: data,
                    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                    processData: false, // NEEDED, DON'T OMIT THIS
                    success: function (result) {
                        if (result['success']) {
                            row.closest("tr").find("td#task_status").html('<span class="label label-success label-rounded">' + result["status_name"] + '</span>');
                            row.closest("tr").find("td#done_note").html(result["done_note"]);
                            row.closest("tr").find("td#task_actions").html('');
                        }
                    }
                });

                $("[data-dismiss=modal]").trigger({type: "click"});
            });

            $('button.task-edit').click(function () {
                var link = $(this).attr("data-task-edit-link");

                window.location.replace(link);
            });

            $('button.task-show').click(function () {
                var link = $(this).attr("data-task-show-link");

                window.location.replace(link);
            });
        });
        $('#tasks').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "order": [
                [5, 'asc']
            ],
            "language": {
                "url": "{{ asset('../js/datatables/Hebrew.json') }}"
            }
        });
    </script>
@endsection
@endsection