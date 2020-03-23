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
                    <h4 class="card-title">@lang('Tasks List')</h4>
                    <div class="table-responsive m-t-40">
                        <table id="tasks" class="display nowrap table table-hover table-striped table-bordered"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>@lang('Sku')</th>
                                <th>@lang('Sku Desc')</th>
                                <th>@lang('Management Company')</th>
                                <th>@lang('Building')</th>
                                <th>@lang('Note')</th>
                                <th>@lang('Done Note')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Due date')</th>
                                <th>@lang('Done date')</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>@lang('Sku')</th>
                                <th>@lang('Sku Desc')</th>
                                <th>@lang('Management Company')</th>
                                <th>@lang('Building')</th>
                                <th>@lang('Note')</th>
                                <th>@lang('Done Note')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Due date')</th>
                                <th>@lang('Done date')</th>
                            </tr>
                            </tfoot>
                            <tbody>

                            @foreach ($tasks as $task)
                                @if(!empty($task->sku))
                                    <tr>
                                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                                        <td>{{ $task->sku->name ?? ''}}</td>
                                        <td>{{ $task->sku->description ?? '' }}</td>
                                        <td>{{ $task->building->managementCompany->name }}</td>
                                        <td>{{ $task->building->name }}</td>
                                        <td>{{ $task->note }}</td>
                                        <td id="done_note">{{ $task->done_note }}</td>
                                        <td id="task_status">
                                            @if($task->status->slug == 'pending')
                                                <span class="label label-warning label-rounded">{{ $task->status->name }}</span>
                                            @elseif($task->status->slug == 'done')
                                                <span class="label label-success label-rounded">{{ $task->status->name }}</span>
                                            @else
                                                <span class="label label-danger label-rounded">{{ $task->status->name }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $task->last_date }}</td>
                                        <td>
                                            @if($task->status->slug == 'done' || $task->status->slug == 'done_overdue')
                                                {{ $task->updated_at }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
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
            $('#example tbody').on('click', 'tr.group', function () {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });

            $('button.task-done').on('click', function (e) {
                row = $(this);
                task_id = row.closest("tr").find("input[name=task_id]").val();
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{!! csrf_token() !!}'
                    }
                });
                $.ajax({
                    url: "{{ url('/tasks/updatestatus') }}",
                    method: 'post',
                    data: {
                        task_id: task_id,
                    },
                    success: function (result) {
                        if (result['success']) {
                            row.closest("tr").find("td#task_status").html('<span class="label label-success label-rounded">' + result["status_name"] + '</span>');
                            row.closest("tr").find("td#task_actions").html('');
                        }
                    }
                });
            });

            $('button.task-edit').click(function () {
                var link = $(this).attr("data-task-link");

                window.location.replace(link);
            });
        });
        $('#tasks').DataTable({
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