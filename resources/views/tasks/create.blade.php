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

    @if ($message = Session::get('success'))
        <div class="alert alert-success"> @lang($message)
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <!-- Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h4 class="m-b-0 text-white">@lang('Add New Task')</h4>
                </div>
                <div class="card-body" id="add-task">
                    <form action="{{ route('tasks.store') }}" method="POST" novalidate enctype="multipart/form-data">
                        @csrf

                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Building') <span
                                                    class="text-danger">*</span></label>
                                        <div class="controls">
                                            <select name="building_id"
                                                    class="select2 form-control custom-select" style="height:36px;"
                                                    required
                                                    data-validation-required-message="@lang('This field is required')">
                                                <option value="">@lang('Select Building')</option>
                                                @if(isset($building)):
                                                <option value="{{ $building->id }}"
                                                        selected>{{ $building->name }}</option>
                                                @else
                                                    @foreach ($buildings as $building)
                                                        <option value="{{ $building->id }}">{{ $building->name }}</option>
                                                    @endforeach
                                                @endif
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
                                                    style="height:36px;" required
                                                    data-validation-required-message="@lang('This field is required')">
                                                <option value="">@lang('Select Sku')</option>

                                                @foreach ($skus as $sku)
                                                    <option value="{{ $sku->id }}"
                                                            data-сyclicdays="{{ $sku->сyclic_days }}">{{ $sku->name }}
                                                        - {{ $sku->description }}</option>
                                                @endforeach
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
                                                    <option value="{{ $duration->сyclic_days }}">{{ $duration->name }}</option>
                                                @endforeach

                                            </select>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label" for="start_date">@lang('Start Date') <span
                                                    class="text-danger">*</span></label>
                                        <input name="start_date" type="text" id="start_date" required
                                               class="form-control datepicker" value="{{date('d/m/Y')}}"
                                               data-validation-required-message="@lang('This field is required')">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Description') <span
                                                    class="text-danger">*</span></label>
                                        <input name="note" type="text" id="note" required class="form-control"
                                               data-validation-required-message="@lang('This field is required')">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Image') <span
                                                    class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input name="image" class="form-control" type="file" id="taskImage">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/row-->
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> @lang('Save')
                            </button>
                            <button type="reset" id="reset-btn" class="btn btn-inverse">@lang('Clear Form')</button>
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
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

@endsection
@section('footer-js')
    <script src="{{ asset('js/validation.js') }}"></script>
    <script src="{{ asset('assets/plugins/toast-master/js/jquery.toast.js') }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.full.min.js') }}"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
                    $('#сyclic_days').val($('.select2.sku-select option:selected').data('сyclicdays')).trigger('change');
                });
            });

            $("#add-task").submit(function (e) {
                $(".preloader").fadeIn();
            });

            $("#reset-btn").click(function () {
                $('.select2').val('').change();
            });
        });
    </script>
@endsection
@endsection