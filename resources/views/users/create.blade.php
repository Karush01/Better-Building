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
                    <h4 class="m-b-0 text-white">@lang('Add New User')</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST" novalidate>
                        @csrf

                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Name') <span
                                                    class="text-danger">*</span></label>
                                        <input name="name" type="text" id="name" class="form-control"
                                               value="{{ old('name') }}">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Email') <span
                                                    class="text-danger">*</span></label>
                                        <input name="email" type="text" id="email" class="form-control"
                                               value="{{ old('email') }}">
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
                                        <input name="phone" type="number" id="phone" class="form-control"
                                               value="{{ old('phone') }}">
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
@endsection
@section('footer-js')
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
            // For select 2
            $(".select2").select2();
            $('.selectpicker').selectpicker();
            $('.select2.building-select').parent().css('display', 'none');

            $('.select2.role-select').on('change', function () {
                if (this.value == 3) {
                    $('.select2.building-select').parent().css('display', 'block');
                    $('.select2.building-select').prop('required', true);
                } else {
                    $('.select2.building-select').parent().css('display', 'none');
                }
            });

            $( "#reset-btn" ).click(function() {
                $('.select2').val('').change();
            });

        });
    </script>
@endsection
@endsection