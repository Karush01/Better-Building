@extends('layouts.app')

@section('content')
    <!-- ============================================================== -->
    <!-- Stats box -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-lg-3">
            <a href="/managementcompanies" style="cursor: pointer">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block">

                            <div class="m-r-20 align-self-center"><span class="lstick m-r-20"></span><img
                                        src="../assets/images/icon/income.png" alt="Companies"/></div>
                            <div class="align-self-center">
                                <h6 class="text-muted m-t-10 m-b-0">@lang('Total Companies')</h6>
                                <h2 class="m-t-0">{{ $companies }}</h2></div>

                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3">
            <a href="/buildings" style="cursor: pointer">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <div class="m-r-20 align-self-center"><span class="lstick m-r-20"></span><img
                                        src="../assets/images/icon/expense.png" alt="Buildings"/></div>
                            <div class="align-self-center">
                                <h6 class="text-muted m-t-10 m-b-0">@lang('Total Buildings')</h6>
                                <h2 class="m-t-0">{{ $buildings }}</h2></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3">
            <a href="/tasks" style="cursor: pointer">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <div class="m-r-20 align-self-center"><span class="lstick m-r-20"></span><img
                                        src="../assets/images/icon/assets.png" alt="Tasks"/></div>
                            <div class="align-self-center">
                                <h6 class="text-muted m-t-10 m-b-0">@lang('Total Tasks')</h6>
                                <h2 class="m-t-0">{{ $tasks_count }}</h2></div>
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
                                <h6 class="text-muted m-t-10 m-b-0">@lang('Total Tickets')</h6>
                                <h2 class="m-t-0">{{ $tickets }}</h2></div>
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
        <div class="col-lg-9 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <h4 class="card-title"><span class="lstick"></span>@lang('Tasks Chart')</h4>
                        <ul class="list-inline m-b-0 ml-auto">
                            <li>
                                <h6 class="text-muted text-success"><i
                                            class="fa fa-circle font-10 m-r-10 "></i>@lang('Done Tasks')</h6></li>
                            <li>
                                <h6 class="text-muted text-info"><i
                                            class="fa fa-circle font-10 m-r-10"></i>@lang('Open Tasks')</h6></li>
                            <li>
                                <h6 class="text-muted text-warning"><i
                                            class="fa fa-circle font-10 m-r-10"></i>@lang('Late Tasks')</h6></li>
                        </ul>
                    </div>
                    <div class="website-visitor p-relative m-t-30" style="height:410px; width:100%;"></div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- visit charts-->
        <!-- ============================================================== -->
        <div class="col-lg-3 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><span class="lstick"></span>@lang('Total Users')</h4>
                    <div id="visitor" style="height:290px; width:100%;"></div>
                    <table class="table vm font-14">
                        @foreach($users_count as $role_name => $count)
                            <tr>
                                <td class="b-0">@lang($role_name)</td>
                                <td class="text-right font-medium b-0">{{ round(($count/array_sum($users_count->all()))*100) }}
                                    %
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Projects of the month -->
    <!-- ============================================================== -->
    {{--<div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div>
                            <h4 class="card-title"><span class="lstick"></span>Projects of the Month</h4></div>
                        <div class="ml-auto">
                            <select class="custom-select b-0">
                                <option selected="">January 2017</option>
                                <option value="1">February 2017</option>
                                <option value="2">March 2017</option>
                                <option value="3">April 2017</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive m-t-20">
                        <table class="table vm no-th-brd no-wrap pro-of-month">
                            <thead>
                            <tr>
                                <th colspan="2">Assigned</th>
                                <th>Name</th>
                                <th>Priority</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="width:50px;"><span class="round"><img src="../assets/images/users/1.jpg"
                                                                                 alt="user" width="50"></span></td>
                                <td>
                                    <h6>Sunil Joshi</h6>
                                    <small class="text-muted">Web Designer</small>
                                </td>
                                <td>Elite Admin</td>
                                <td><span class="label label-success label-rounded">Low</span></td>
                            </tr>
                            <tr class="active">
                                <td><span class="round"><img src="../assets/images/users/2.jpg" alt="user"
                                                             width="50"></span></td>
                                <td>
                                    <h6>Andrew</h6>
                                    <small class="text-muted">Project Manager</small>
                                </td>
                                <td>Real Homes</td>
                                <td><span class="label label-info label-rounded">Medium</span></td>
                            </tr>
                            <tr>
                                <td><span class="round round-success"><img src="../assets/images/users/3.jpg" alt="user"
                                                                           width="50"></span></td>
                                <td>
                                    <h6>Bhavesh patel</h6>
                                    <small class="text-muted">Developer</small>
                                </td>
                                <td>MedicalPro Theme</td>
                                <td><span class="label label-primary label-rounded">High</span></td>
                            </tr>
                            <tr>
                                <td><span class="round round-primary"><img src="../assets/images/users/4.jpg" alt="user"
                                                                           width="50"></span></td>
                                <td>
                                    <h6>Nirav Joshi</h6>
                                    <small class="text-muted">Frontend Eng</small>
                                </td>
                                <td>Elite Admin</td>
                                <td><span class="label label-danger label-rounded">Low</span></td>
                            </tr>
                            <tr>
                                <td><span class="round round-warning"><img src="../assets/images/users/5.jpg" alt="user"
                                                                           width="50"></span></td>
                                <td>
                                    <h6>Micheal Doe</h6>
                                    <small class="text-muted">Content Writer</small>
                                </td>
                                <td>Helping Hands</td>
                                <td><span class="label label-success label-rounded">High</span></td>
                            </tr>
                            <tr>
                                <td><span class="round round-danger"><img src="../assets/images/users/6.jpg" alt="user"
                                                                          width="50"></span></td>
                                <td>
                                    <h6>Johnathan</h6>
                                    <small class="text-muted">Graphic</small>
                                </td>
                                <td>Digital Agency</td>
                                <td><span class="label label-info label-rounded">High</span></td>
                            </tr>
                            <tr>
                                <td><span class="round round-primary"><img src="../assets/images/users/4.jpg" alt="user"
                                                                           width="50"></span></td>
                                <td>
                                    <h6>Nirav Joshi</h6>
                                    <small class="text-muted">Frontend Eng</small>
                                </td>
                                <td>Elite Admin</td>
                                <td><span class="label label-danger label-rounded">Low</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>--}}
    <!-- ============================================================== -->
    <!-- Activity widget find scss into widget folder-->
    <!-- ============================================================== -->
    {{--<div class="col-lg-6 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <h4 class="card-title"><span class="lstick"></span>Activity</h4>
                    <!-- <span class="badge badge-success">9</span> -->
                    <div class="btn-group ml-auto m-t-10">
                        <a href="JavaScript:void(0)" class="icon-options-vertical link" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false"></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="javascript:void(0)">Action</a>
                            <a class="dropdown-item" href="javascript:void(0)">Another action</a>
                            <a class="dropdown-item" href="javascript:void(0)">Something else here</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:void(0)">Separated link</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="activity-box">
                <div class="card-body">
                    <!-- Activity item-->
                    <div class="activity-item">
                        <div class="round m-r-20"><img src="../assets/images/users/2.jpg" alt="user" width="50"/>
                        </div>
                        <div class="m-t-10">
                            <h5 class="m-b-0 font-medium">Mark Freeman <span class="text-muted font-14 m-l-10">| &nbsp; 6:30 PM</span>
                            </h5>
                            <h6 class="text-muted">uploaded this file </h6>
                            <table class="table vm b-0 m-b-0">
                                <tr>
                                    <td class="m-r-10 b-0"><img src="../assets/images/icon/zip.png" alt="user"/>
                                    </td>
                                    <td class="b-0">
                                        <h5 class="m-b-0 font-medium ">Homepage.zip</h5>
                                        <h6>54 MB</h6></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- Activity item-->
                    <!-- Activity item-->
                    <div class="activity-item">
                        <div class="round m-r-20"><img src="../assets/images/users/3.jpg" alt="user" width="50"/>
                        </div>
                        <div class="m-t-10">
                            <h5 class="m-b-5 font-medium">Emma Smith <span class="text-muted font-14 m-l-10">| &nbsp; 6:30 PM</span>
                            </h5>
                            <h6 class="text-muted">joined projectname, and invited <a href="javascript:void(0)">@maxcage,
                                    @maxcage, @maxcage, @maxcage, @maxcage,+3</a></h6>
                            <span class="image-list m-t-20">
                                            <a href="javascript:void(0)"><img src="../assets/images/users/1.jpg"
                                                                              class="img-circle" alt="user"
                                                                              width="50"></a>
                                            <a href="javascript:void(0)"><img src="../assets/images/users/2.jpg"
                                                                              class="img-circle" alt="user"
                                                                              width="50"></a>
                                            <a href="javascript:void(0)"><span class="round round-warning">C</span></a>
                                        <a href="javascript:void(0)"><span class="round round-danger">D</span></a>
                                        <a href="javascript:void(0)">+3</a>
                                        </span>
                        </div>
                    </div>
                    <!-- Activity item-->
                    <!-- Activity item-->
                    <div class="activity-item">
                        <div class="round m-r-20"><img src="../assets/images/users/4.jpg" alt="user" width="50"/>
                        </div>
                        <div class="m-t-10">
                            <h5 class="m-b-0 font-medium">David R. Jones <span class="text-muted font-14 m-l-10">| &nbsp; 9:30 PM, July 13th</span>
                            </h5>
                            <h6 class="text-muted">uploaded this file </h6>
                            <span>
                                            <a href="javascript:void(0)" class="m-r-10"><img
                                                        src="../assets/images/big/img1.jpg" alt="user"
                                                        width="60"></a>
                                            <a href="javascript:void(0)" class="m-r-10"><img
                                                        src="../assets/images/big/img2.jpg" alt="user"
                                                        width="60"></a>
                                        </span>
                        </div>
                    </div>
                    <!-- Activity item-->
                    <!-- Activity item-->
                    <div class="activity-item">
                        <div class="round m-r-20"><img src="../assets/images/users/6.jpg" alt="user" width="50"/>
                        </div>
                        <div class="m-t-10">
                            <h5 class="m-b-5 font-medium">David R. Jones <span class="text-muted font-14 m-l-10">| &nbsp; 6:30 PM</span>
                            </h5>
                            <h6 class="text-muted">Commented on<a href="javascript:void(0)">Test Project</a></h6>
                            <p class="m-b-0">It has survived not only five centuries, but also the leap into
                                unchanged.</p>
                        </div>
                    </div>
                    <!-- Activity item-->
                </div>
            </div>
        </div>
    </div>--}}
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

    <script>
        $(document).ready(function () {
            createUsersChartist({!! json_encode($users_count) !!});
            createChartist({!! json_encode($tasks_month_year) !!});
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
@endsection


@endsection
