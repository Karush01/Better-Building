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
                    <h4 class="m-b-0 text-white">@lang('Edit User')</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Name') <span
                                                    class="text-danger">*</span></label>
                                        <input name="name" type="text" id="name" class="form-control"
                                               value="{{ $user->name }}">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Email') <span
                                                    class="text-danger">*</span></label>
                                        <input name="email" type="text" id="email" class="form-control"
                                               value="{{ $user->email }}">
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
                                        <input name="phone" type="text" id="phone" class="form-control"
                                               value="{{ $user->phone }}">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Password') </label>
                                        <input name="password" type="password" id="password" class="form-control">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->

                            @if ( Auth::user()->hasrole( 'director' ) )
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">@lang('Role')</label>
                                            <select name="role_id"
                                                    class="select2 form-control custom-select role-select"
                                                    style="height:36px;">
                                                <option value="">@lang('Select Role')</option>
                                                @foreach ($roles as $role)
                                                    @if($role->id == $user->roles[0]->getAttribute('id'))
                                                        <option value="{{ $role->id }}"
                                                                selected>@lang($role->name)</option>
                                                    @else
                                                        <option value="{{ $role->id }}">@lang($role->name)</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">@lang('Building')</label>
                                            <select name="building_id"
                                                    class="select2 form-control custom-select building-select"
                                                    style="height:36px;">
                                                <option value="">@lang('Select Building')</option>

                                                @foreach ($buildings as $building)
                                                    <option value="{{ $building->id }}" {{ isset($user->building) ? ($user->building->id === $building->id ? "selected" : "") : "" }}>{{ $building->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <!--/row-->

                                @if($user->roles[0]->name == 'tenant')
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="checkbox" name="sms" class="check" id="flat-checkbox-2"
                                                       data-checkbox="icheckbox_flat-blue" {{ $user->sms === 1 ? "checked" : "" }}>
                                                <label for="flat-checkbox-2">@lang('Sms Notification')</label>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <!--/row-->
                                @endif
                            @endif
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
            if ($('.select2.role-select').find(":selected").val() != 3) {
                $('.select2.building-select').parent().css('display', 'none');
            }

            $('.select2.role-select').on('change', function () {
                if (this.value == 3) {
                    $('.select2.building-select').parent().css('display', 'block');
                    $('.select2.building-select').prop('required', true);
                } else {
                    $('.select2.building-select').parent().css('display', 'none');
                }
            });

        });
    </script>
@endsection
@endsection