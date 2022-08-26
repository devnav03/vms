
<!-- START HEADER -->
    <header id="header" class="page-topbar">
        <!-- start header nav-->
        <div class="navbar-fixed">
            <nav class="cyan">
                <div class="nav-wrapper">
                    <span class="logo-wrapper">
                    <a href="{{route('admin.login.form')}}" class="brand-logo darken-1">Visitor Management System({{json_decode(Session::get('CIDATA'))->name}})</a> <span class="logo-text"></span></span>
                    <ul class="right hide-on-med-and-down">
                        <li>
                            <a class="btn btn-primary" href="{{ route('admin.logout.get') }}">Logout</a>
                        </li>
                        
                        <li><a href="javascript:void(0);" class="waves-effect waves-block waves-light toggle-fullscreen"><i class="mdi-action-settings-overscan"></i></a>
                        </li>
                    
                    </ul>
                </div>
            </nav>
        </div>
        <!-- end header nav-->
    </header>
    <!-- END HEADER -->
