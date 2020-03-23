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
                    <h4 class="m-b-0 text-white">@lang('Add New Building')</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('buildings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-body">
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Name')<span
                                                    class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input name="name" type="text" id="buildingName" class="form-control"
                                                   required
                                                   data-validation-required-message=@lang('This field is required') value="{{ old('name') }}">
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('City')<span
                                                    class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input name="city" type="text" id="buildingCity" class="form-control"
                                                   required
                                                   data-validation-required-message=@lang('This field is required') value="{{ old('city') }}">
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Address')<span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input name="address" type="text" id="buildingAddress" class="form-control"
                                                   required
                                                   data-validation-required-message=@lang('This field is required') value="{{ old('address') }}">
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Upload image')</label>
                                        <input name="image" type="file" id="buildingImage"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Description')</label>
                                        <input name="description" type="text" id="buildingDescription"
                                               class="form-control" value="{{ old('description') }}">
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Management Company')<span
                                                    class="text-danger">*</span></label>
                                        <div class="controls">
                                            <select name="management_company_id"
                                                    class="select2 form-control custom-select" style="height:36px;"
                                                    required>
                                                <option value="">@lang('Select Company')</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->

                            <div class="card-header bg-info">
                                <h4 class="m-b-0 text-white">@lang('Addition building information')</h4>
                            </div>

                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Tenants count') <span
                                                    class="text-danger">*</span></label>
                                        <input name="tenants_count" type="number" id="tenants_count"
                                               class="form-control" value="{{ old('tenants_count') }}">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Floors') <span
                                                    class="text-danger">*</span></label>
                                        <input name="floors" type="number" id="floors" class="form-control" value="{{ old('floors') }}">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Parking levels')</label>
                                        <input name="parking_levels" type="number" id="parking_levels"
                                               class="form-control"
                                               value="{{ old('parking_levels') }}">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Entrepreneur') </label>
                                        <input name="entrepreneur" type="text" id="entrepreneur" value="{{ old('entrepreneur') }}" class="form-control">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Constructor')</label>
                                        <input name="constructor" type="text" id="constructor" class="form-control"
                                               value="{{ old('constructor') }}">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Names of committee members') </label>
                                        <input name="committee_members" type="text" id="committee_members" value="{{ old('committee_members') }}"
                                               class="form-control">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Building Description')</label>
                                        <textarea name="building_description" id="building_description"
                                                  class="form-control">{{ old('building_description') }}</textarea>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->
                        </div>
                        <!--/row-->


                        <div class="card-header bg-info">
                            <h4 class="m-b-0 text-white">@lang('Create New User For Building')</h4>
                        </div>

                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">@lang('Name') <span
                                                class="text-danger">*</span></label>
                                    <input name="username" type="text" id="name" class="form-control"
                                           value="{{ old('username') }}">
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">@lang('Email') <span
                                                class="text-danger">*</span></label>
                                    <input name="useremail" type="text" id="email" class="form-control"
                                           value="{{ old('useremail') }}">
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">@lang('Phone') <span
                                                class="text-danger">*</span></label>
                                    <input name="userphone" type="number" id="phone" class="form-control"
                                           value="{{ old('userphone') }}">
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">@lang('Password') </label>
                                    <input name="userpassword" type="password" id="password" class="form-control">
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <!--/row-->

                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="checkbox" name="sms" class="check" id="flat-checkbox-2"
                                           data-checkbox="icheckbox_flat-blue">
                                    <label for="flat-checkbox-2">@lang('Sms Notification')</label>
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><i
                                        class="fa fa-check"></i> @lang('Save')</button>
                            <button type="reset" id="reset-btn"
                                    class="btn btn-inverse">@lang('Clear Form')</button>
                        </div>
                    </form>
                </div>
                <!--/row-->
            </div>
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
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js') }}"
            type="text/javascript"></script>
    <!-- icheck -->
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

            $("#reset-btn").click(function () {
                $('.select2').val('').change();
            });

        });
    </script>
@endsection
@endsection