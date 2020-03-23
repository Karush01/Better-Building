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
    <!-- Modal -->
    <div id="pic-box" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="image-wrapper"></div>
                </div>
            </div>

        </div>
    </div>
    <!-- Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h4 class="m-b-0 text-white">@lang('Edit Building')</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('buildings.update', $building->id) }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-body">
                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Name')<span
                                                    class="text-danger">*</span></label>
                                        <div class="controls">
                                            <input name="name" type="text" id="buildingName" class="form-control"
                                                   value="{{ $building->name }}"
                                                   required data-validation-required-message="This field is required">
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
                                                   value="{{ $building->city }}"
                                                   required
                                                   data-validation-required-message="This field is required">
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
                                                   value="{{ $building->address }}"
                                                   required data-validation-required-message="This field is required">
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Upload image')</label>
                                        <input name="image" type="file" id="buildingImage"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    @if ($building->image)<img style="width: 200px" id="building-photo" src="{{ url('/images/'.$building->image) }}"/>@endif
                                </div>
                            </div>
                            <!--/row-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Description')</label>
                                        <input name="description" type="text" id="buildingDescription"
                                               value="{{ $building->description }}"
                                               class="form-control">
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
                                                    @if($company->id == $building->management_company_id)
                                                        <option value="{{ $company->id }}"
                                                                selected>{{ $company->name }}</option>
                                                    @else
                                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select></div>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>


                            <div class="card-header bg-info">
                                <h4 class="m-b-0 text-white">@lang('Addition building information')</h4>
                            </div>

                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Tenants count') <span
                                                    class="text-danger">*</span></label>
                                        <input name="tenants_count" type="number" id="tenants_count"
                                               class="form-control" value="{{ $building->tenants_count }}">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Floors') <span
                                                    class="text-danger">*</span></label>
                                        <input name="floors" type="number" id="floors" class="form-control" value="{{$building->floors}}">
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
                                               value="{{$building->parking_levels}}">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Entrepreneur') </label>
                                        <input name="entrepreneur" type="text" value="{{$building->entrepreneur}}" id="entrepreneur" class="form-control">
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
                                               value="{{$building->constructor}}">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Names of committee members') </label>
                                        <input name="committee_members" type="text" id="committee_members"
                                               class="form-control" value="{{$building->committee_members}}">
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!--/row-->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang('Building Description')</label>
                                        <input name="building_description" type="text" id="building_description"
                                               class="form-control"
                                               value="{{$building->building_description}}">
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
            $('#building-photo').click(function(evt){
                $.imgParent = $(this).closest('td');
                var $modal = $('#pic-box');
                var photo = $(this).clone()
                photo.css('width', '100%');
                $modal.find('#image-wrapper').html('');
                $modal.find('#image-wrapper').append(photo);
                $modal.modal('show');
            });
        });
    </script>
@endsection
@endsection