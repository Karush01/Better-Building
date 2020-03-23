@php
    if(Auth::user()->hasrole('tenant')):
        $building = \App\Building::where('user_id', "=", Auth::id())->first();
    endif;
@endphp

<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="user-profile">
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><span
                                class="hide-menu">{{ Auth::user()->name }}</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ route('users.edit', Auth::id())  }}">@lang('My Profile')</a></li>
                        {{--                         <li><a href="javascript:void()">My Balance</a></li>
                                                <li><a href="javascript:void()">Inbox</a></li>
                                                <li><a href="javascript:void()">Account Setting</a></li>--}}
                        <li><a href="{{ route('logout') }}">@lang('Logout')</a></li>
                    </ul>
                </li>
                <li><a class="waves-effect waves-dark" href="{{ route('home') }}" aria-expanded="false"><i
                                class="mdi mdi-gauge"></i><span class="hide-menu">@lang('Dashboard') {{--<span
                                    class="label label-rouded label-themecolor pull-right">4</span>--}}</span></a>
                </li>
                @if( Auth::user()->hasrole('director') )
                    <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                    class="mdi mdi-gauge"></i><span class="hide-menu">@lang('Settings')</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="{{ route('settings.edit', 'general')  }}">@lang('General')</a></li>
                            <li><a href="{{ route('settings.edit', 'sms')  }}">@lang('Sms')</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                    class="mdi mdi-gauge"></i><span class="hide-menu">@lang('Companies')</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="{{ route('managementcompanies.index') }}">@lang('Show All')</a></li>
                            <li><a href="{{ route('managementcompanies.create') }}">@lang('Create New')</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                    class="mdi mdi-gauge"></i><span class="hide-menu">@lang('Skus')</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="{{ route('skus.index') }}">@lang('Show All')</a></li>
                            <li><a href="{{ route('skus.create') }}">@lang('Create New')</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                    class="mdi mdi-gauge"></i><span class="hide-menu">@lang('Users')</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="{{ route('users.index') }}">@lang('Show All')</a></li>
                            <li><a href="{{ route('users.create') }}">@lang('Create Admin')</a></li>
                        </ul>
                    </li>
                @endif
                @if( Auth::user()->hasrole('management_company') || Auth::user()->hasrole('director'))
                    <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                    class="mdi mdi-gauge"></i><span class="hide-menu">@lang('Buildings')</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="{{ route('buildings.index') }}">@lang('Show All')</a></li>
                            @if ( Auth::user()->hasrole( 'director' ) )
                                <li><a href="{{ route('buildings.create') }}">@lang('Create New')</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
                <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                class="mdi mdi-gauge"></i><span class="hide-menu">@lang('Tasks')</span></a>
                    <ul aria-expanded="false" class="collapse">
                        @if( Auth::user()->hasrole('tenant'))
                            <li><a href="{{ route('buildings.show', $building->id) }}">@lang('Show All')</a></li>
                        @else
                            <li><a href="{{ route('tasks.index') }}">@lang('Show All')</a></li>
                            @if ( Auth::user()->hasrole( 'director' ) )
                                <li><a href="{{ route('tasks.create') }}">@lang('Create New')</a></li>
                            @endif
                        @endif
                    </ul>
                </li>
                <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                class="mdi mdi-gauge"></i><span class="hide-menu">@lang('Tickets')</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ route('tickets.index') }}">@lang('Show All')</a></li>
                        @if( Auth::user()->hasrole('tenant'))
                            <li><a href="{{ route('tickets.create') }}">@lang('Create New')</a></li>
                        @endif
                    </ul>
                </li>
                @if(is_dev())
                    <li><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                    class="mdi mdi-widgets"></i><span class="hide-menu">Matat</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="{{ route('logs') }}">@lang('Logs')</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>