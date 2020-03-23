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
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        </div>
    @endif

    <!-- Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h4 class="m-b-0 text-white">@lang('Edit Management Company')</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('managementcompanies.update', $company->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-body">
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Name')</label>
                                        <input name="name" type="text" id="companyName" class="form-control" value="{{ $company->name }}" required data-validation-required-message="This field is required">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('City')</label>
                                        <input name="city" type="text" id="companyCity" class="form-control" value="{{ $company->city }}" required data-validation-required-message="This field is required">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Address')</label>
                                        <input name="address" type="text" id="companyAddress" class="form-control" value="{{ $company->address }}" required data-validation-required-message="This field is required">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Phone')</label>
                                        <input name="phone" type="text" id="companyCity" class="form-control" value="{{ $company->phone }}" required data-validation-required-message="This field is required"></div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Description')</label>
                                        <input name="description" type="text" id="companyDescription" value="{{ $company->description }}" class="form-control">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('Save Edit')</button>
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
@section('footer-js')
    <script src="{{ asset('js/validation.js') }}"></script>
    <script src="{{ asset('assets/plugins/toast-master/js/jquery.toast.js') }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
    <script>
        ! function(window, document, $) {
            "use strict";
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
        }(window, document, jQuery);
    </script>
@endsection
@endsection