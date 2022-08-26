

<div class="header-section bg-danger light-color">

                <!--logo and logo icon start-->
                <div class="logo theme-logo-bg hidden-xs hidden-sm">
                    <a href="{{route('web.home')}}">
                        <span>VMS</span>
                    </a>
                </div>

                <div class="icon-logo dark-logo-bg hidden-xs hidden-sm">
                    <a href="{{route('web.home')}}">VMS
                    </a>
                </div>
                <!--logo and logo icon end-->

                <!--toggle button start-->
                <a class="toggle-btn"><i class="fa fa-outdent"></i></a>
                <!--toggle button end-->


                <div class="notification-wrap">
                <!--left notification start-->
                <div class="left-notification">
                <ul class="notification-menu">
                <!--mail info start-->
                
                <!--mail info end-->

                </ul>
                </div>
                <!--left notification end-->


                <!--right notification start-->
                <div class="right-notification">
                    <ul class="notification-menu">
                        <li>
                            <a href="javascript:;" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ asset('admin-asset/img/avatar-mini.jpg')}}" alt="">
                                <span class=" fa fa-angle-down"></span> {{auth('admin')->user()->name}}
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu purple pull-right">
                              
                                
                                <li><a href="{{ route('admin.logout.get') }}"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                            </ul>
                        </li>
                       

                    </ul>
                </div>
                <!--right notification end-->
                </div>

            </div>