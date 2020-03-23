@extends('layouts.app')

@section('content')
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>@lang('Whoops!')</strong> @lang('There were some problems with your input.')<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>@lang($error)</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <!-- Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h4 class="m-b-0 text-white">@lang('Update Task')</h4>
                </div>
                <div class="card-body" id="edit-task">
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Building') <span
                                                    class="text-danger">*</span></label>
                                        <div class="controls">
                                            <select name="building_id"
                                                    class="select2 form-control custom-select" style="height:36px;"
                                                    required>
                                                <option value="{{ $task->building->id }}">{{ $task->building->name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Sku') <span
                                                    class="text-danger">*</span></label>
                                        <div class="controls">
                                            <select name="sku_id" class="select2 form-control custom-select sku-select"
                                                    style="height:36px;" required>
                                                <option value="{{ $task->sku->id }}"
                                                        data-сyclicdays="{{ $task->sku->сyclic_days }}">{{ $task->sku->name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->



                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Cyclic Days') <span
                                                    class="text-danger">*</span></label>
                                        <div class="controls">
                                            <select id="сyclic_days" name="сyclic_days"
                                                    class="select2 form-control custom-select duration-select"
                                                    style="height:36px;" required>
                                                <option value="">@lang('Select Duration')</option>

                                                @foreach ($durations as $duration)
                                                    <option value="{{ $duration->сyclic_days }}" @if($task->сyclic_days == $duration->сyclic_days) selected @endif>{{ $duration->name }}</option>
                                                @endforeach

                                            </select>

                                        </div>
                                    </div>
                                </div>


                                <!--/span-->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label" for="start_date">@lang('Start Date') <span
                                                    class="text-danger">*</span></label>
                                        <input name="start_date" type="text" id="start_date" required
                                               class="form-control datepicker" value="" placeholder="לחץ כאן כידי לשנות מועד"
                                               data-validation-required-message="@lang('This field is required')">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Description')</label>
                                        <input name="note" type="text" id="note" value="{{ $task->note }}"
                                               class="form-control">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> @lang('Save Edit')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Row -->
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
@section('head-css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet"/>
@endsection
@section('footer-js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ asset('js/validation.js') }}"></script>
    <script src="{{ asset('assets/plugins/toast-master/js/jquery.toast.js') }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js') }}"
            type="text/javascript"></script>
    <script>
        !function (window, document, $) {
            "use strict";
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
        }(window, document, jQuery);


        jQuery(document).ready(function () {

            $(".datepicker").datepicker({
                dateFormat: 'dd/mm/yy'
            });
            // For select 2
            $(".select2").select2();
            $('.selectpicker').selectpicker();

            $('document').ready(function () {
                $('.select2.sku-select').on('change.select2', function (evt) {
                    $('#сyclic_days').val($('.select2.sku-select option:selected').data('сyclicdays'));
                });
            });

            $("#edit-task").submit(function (e) {
                $(".preloader").fadeIn();
            });
        });
    </script>
@endsection
@endsection