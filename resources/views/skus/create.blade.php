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
                    <h4 class="m-b-0 text-white">@lang('Add New Sku')</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('skus.store') }}" method="POST" novalidate>
                        @csrf

                        <div class="form-body">
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Name')</label>
                                        <input name="name" type="text" id="skuName" class="form-control" required
                                               data-validation-required-message="This field is required">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Description')</label>
                                        <input name="description" type="text" id="skuDescription" class="form-control">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Treatment Factor')</label>
                                        <input name="treatmentfactor" type="text" id="skutreaTmentfactor"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Duration')</label>
                                        <select name="duration_id"
                                                class="select2 form-control custom-select duration-select"
                                                style="height:36px;" required>
                                            <option value="">@lang('Select Duration')</option>

                                            @foreach ($durations as $duration)
                                                <option value="{{ $duration->сyclic_days }}">{{ $duration->name }}</option>
                                            @endforeach

                                            {{--                                            <option value="other">@lang('Other')</option>--}}
                                        </select>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-2">
                                    <div class="form-group" id="сyclic_days_div">
                                        <label class="control-label">@lang('Cyclic Days') </label>

                                        <input name="сyclic_days" type="number" id="сyclic_days"
                                               class="form-control" min="1" required
                                               data-validation-required-message="@lang('This field most be more then 0')">

                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-lg-12">
                                    <div class="form-group" id="procedure_div">
                                        <label class="control-label">@lang('Instruction') </label>

                                        <textarea name="instruction" id="instruction" rows="5"
                                                  class="form-control"></textarea>

                                    </div>
                                </div>

                            </div>
                            <!--/row-->
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="checkbox" name="regression" class="check" id="flat-checkbox-2"
                                               data-checkbox="icheckbox_flat-blue" value="true" checked>
                                        <label for="flat-checkbox-2">@lang('Approve 3 days regression')</label>


                                    </div>
                                </div>
                            </div>

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
    <link href="{{ asset('css/pages/form-icheck.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/icheck/skins/all.css') }}" rel="stylesheet">
@endsection
@section('footer-js')
    <script src="{{ asset('js/validation.js') }}"></script>
    <script src="{{ asset('assets/plugins/toast-master/js/jquery.toast.js') }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
    <!-- icheck -->
    <script src="{{ asset('assets/plugins/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/icheck/icheck.init.js') }}"></script>
    <script>
        !function (window, document, $) {
            "use strict";
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
        }(window, document, jQuery);

        jQuery(document).ready(function () {
            $('#сyclic_days_div').parent().css('display', 'none');

            $('.select2.duration-select').on('change', function () {
                $('#сyclic_days').val('');
                //
                // if (this.value == 'other') {
                //     $('#сyclic_days_div').parent().css('display', 'block');
                // } else {
                $('#сyclic_days_div').parent().css('display', 'none');
                $('#сyclic_days').val($('.select2.duration-select option:selected').val());
                // }
            });

            $("#reset-btn").click(function () {
                $('.select2').val('').change();
            });
        });
    </script>
@endsection
@endsection