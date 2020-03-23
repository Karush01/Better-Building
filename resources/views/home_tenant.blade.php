@extends('layouts.app')

@section('content')
    <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">@lang('Building info') : @lang($building->name)</h3>
    </div>
    <!-- ============================================================== -->
    <!-- Stats box -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-lg-3">
            <a href="/buildings/{{$building->id}}" style="cursor: pointer">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <div class="m-r-20 align-self-center"><span class="lstick m-r-20"></span><img
                                        src="../assets/images/icon/income.png" alt="Companies"/></div>
                            <div class="align-self-center">
                                <h6 class="text-muted m-t-10 m-b-0">@lang('Total Open Tasks')</h6>
                                <h2 class="m-t-0">{{ $total_skus }}</h2></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3">
            <a href="/buildings/{{$building->id}}" style="cursor: pointer">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <div class="m-r-20 align-self-center"><span class="lstick m-r-20"></span><img
                                        src="../assets/images/icon/expense.png" alt="Buildings"/></div>
                            <div class="align-self-center">
                                <h6 class="text-muted m-t-10 m-b-0">@lang('Total Overdue Tasks')</h6>
                                <h2 class="m-t-0">{{ $total_overdue_tasks }}</h2></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3">
            <a href="/buildings/{{$building->id}}" style="cursor: pointer">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <div class="m-r-20 align-self-center"><span class="lstick m-r-20"></span><img
                                        src="../assets/images/icon/assets.png" alt="Tasks"/></div>
                            <div class="align-self-center">
                                <h6 class="text-muted m-t-10 m-b-0">@lang('Total Closed Tasks')</h6>
                                <h2 class="m-t-0">{{ $total_done_tasks }}</h2></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3">
            <a href="/tickets" style="cursor: pointer">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <div class="m-r-20 align-self-center"><span class="lstick m-r-20"></span><img
                                        src="../assets/images/icon/staff.png" alt="Tickets"/></div>
                            <div class="align-self-center">
                                <h6 class="text-muted m-t-10 m-b-0">@lang('Total Open Tickets')</h6>
                                <h2 class="m-t-0">{{ $total_open_tickets }}</h2></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Sales overview chart -->
    <!-- ============================================================== -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-body b-l">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Page Content -->
    <!-- ============================================================== -->

@section('footer-js')
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <script src="{{ asset('assets/plugins/chartist-js/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js') }}"></script>
    <!--c3 JavaScript -->
    <script src="{{ asset('assets/plugins/d3/d3.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/c3-master/c3.min.js') }}"></script>
    <!-- Chart JS -->
    <script src="{{ asset('js/dashboard2.js') }}"></script>
    !-- Calendar JavaScript -->
    <script src="{{ asset('assets/plugins/calendar/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/plugins/calendar/dist/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/calendar/dist/locale-all.js') }}"></script>
    <script src="{{ asset('assets/plugins/calendar/dist/cal-init.js') }}"></script>

    <script>
        $(document).ready(function () {
            $.CalendarApp.init("{!! csrf_token() !!}", "{{ url('/home/gettasksbymonthyear') }}", "{{ Illuminate\Support\Facades\Auth::id() }}");
        });
    </script>
@endsection

@section('head-css')
    <!-- This page CSS -->
    <!-- chartist CSS -->
    <link href="{{ asset('assets/plugins/chartist-js/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css') }}"
          rel="stylesheet">
    <!--c3 CSS -->
    <link href="{{ asset('assets/plugins/c3-master/c3.min.css') }}" rel="stylesheet">
    <!-- Dashboard 2 Page CSS -->
    <link href="{{ asset('css/pages/dashboard2.css') }}" rel="stylesheet">
    <!-- Calendar CSS -->
    <link href="{{ asset('assets/plugins/calendar/dist/fullcalendar.css') }}" rel="stylesheet"/>
@endsection


@endsection
