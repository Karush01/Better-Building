@extends('layouts.app')

@section('content')
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    @if ($errors->any())
        <div class="alert alert-danger"><strong>@lang('Whoops!')</strong> @lang('There were some problems with your input.')<br><br>
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
                    <h4 class="m-b-0 text-white">@lang('Edit Sku')</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('skus.update', $sku->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-body">
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Name')<span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input name="name" type="text" id="skuName" class="form-control" value="{{ $sku->name }}"
                                                   required data-validation-required-message="@lang('This field is required')">
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Description')<span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input name="description" type="text" id="skuDescription" class="form-control" value="{{ $sku->description }}"
                                                   required
                                                   data-validation-required-message="@lang('This field is required')">
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Treatment Factor')</label>
                                        <div class="controls">
                                            <input name="treatmentfactor" type="text" id="skutreaTmentfactor" class="form-control" value="{{ $sku->treatmentfactor }}"
                                                   required
                                                   data-validation-required-message="@lang('This field is required')">
                                        </div>
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
                                                    <option value="{{ $duration->сyclic_days }}" {{ $duration->сyclic_days === $sku->сyclic_days ? "selected" : "" }}>{{ $duration->name }}</option>
                                            @endforeach

                                            <option value="other">@lang('Other')</option>
                                        </select>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-2">
                                    <div class="form-group" id="сyclic_days_div">
                                        <label class="control-label">@lang('Cyclic Days') </label>
                                        <div class="controls">
                                            <input name="сyclic_days" type="number" id="сyclic_days"
                                                   class="form-control" min="1" required
                                                   data-validation-required-message="@lang('This field most be more then 0')" value="{{ $sku->сyclic_days }}">
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-lg-12">
                                    <div class="form-group" id="procedure_div">
                                        <label class="control-label">@lang('Instruction') </label>

                                        <textarea name="instruction" id="instruction" rows="5"
                                                  class="form-control">{{$sku->instruction}}</textarea>

                                    </div>
                                </div>
                            </div>
                            <!--/row-->
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="checkbox" id="regression_div">
                                        <input type="checkbox" name="regression" {{$sku->regression?"checked":""}} class="check" id="flat-checkbox-2"
                                               data-checkbox="icheckbox_flat-blue" value="true">
                                        <label for="flat-checkbox-2">@lang('Approve 3 days regression')</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> @lang('Save Edit')</button>
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
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/pages/form-icheck.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/icheck/skins/all.css') }}" rel="stylesheet">
@endsection
@section('footer-js')
    <script src="{{ asset('js/validation.js') }}"></script>
    <script src="{{ asset('assets/plugins/toast-master/js/jquery.toast.js') }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/icheck/icheck.init.js') }}"></script>
    <script>
        !function (window, document, $) {
            "use strict";
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
        }(window, document, jQuery);


        jQuery(document).ready(function () {
            // For select 2
            $(".select2").select2();
            $('.selectpicker').selectpicker();

            $('#сyclic_days_div').parent().css('display', 'none');

            $('.select2.duration-select').on('change', function () {
                $('#сyclic_days').val('');

                if (this.value == 'other') {
                    $('#сyclic_days_div').parent().css('display', 'block');
                } else {
                    $('#сyclic_days_div').parent().css('display', 'none');
                    $('#сyclic_days').val($('.select2.duration-select option:selected').val());
                }
            });

        });
    </script>
@endsection
@endsection