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
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang($id)</h4>
                    <form class="form">
                        @foreach($settings as $key => $val)
                        <div class="form-group m-t-40 row">
                            <label for="example-text-input" class="col-md-2 col-form-label">@lang($key)</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" value="{{ $val }}" id="example-text-input" disabled>
                            </div>
                        </div>
                        @endforeach

{{--                            <div class="form-actions">
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> @lang('Save')</button>
                                <button type="button" class="btn btn-inverse">@lang('Cancel')</button>
                            </div>--}}
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
@endsection
@section('footer-js')
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    <script src="{{ asset('js/validation.js') }}"></script>
    <script src="{{ asset('assets/plugins/toast-master/js/jquery.toast.js') }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script>
        !function (window, document, $) {
            "use strict";
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
        }(window, document, jQuery);


        jQuery(document).ready(function () {
            // For select 2
            $(".select2").select2();
            $('.selectpicker').selectpicker();

        });
    </script>
@endsection
@endsection