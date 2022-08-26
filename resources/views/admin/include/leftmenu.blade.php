 <!-- START LEFT SIDEBAR NAV-->
            <aside id="left-sidebar-nav">
                <ul id="slide-out" class="side-nav fixed leftside-navigation collapsible collapsible-accordion">
                    <li class="user-details cyan darken-2">
                        <div class="row">
                            <div class="col col s4 m4 l4">
                                <img src="{{asset('admin-asset/images/avatar.jpg')}}" alt="" class="circle responsive-img valign profile-image">
                            </div>
                            <div class="col col s8 m8 l8">
                                <ul id="profile-dropdown" class="dropdown-content">
                                    <li><a href="{{ route('admin.logout.get') }}"><i class="mdi-hardware-keyboard-tab"></i>Logout</a></li>
                                    {{-- <li><a href="#"><i class="mdi-action-settings"></i>Settings</a></li> --}}
                                </ul>
                                <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="profile-dropdown">{{auth('admin')->user()->name}}<i class="mdi-navigation-arrow-drop-down right"></i></a>
                                <p class="user-roal">
                                    @if(auth('admin')->user()->role_id =='1')
                                    Admin
                                    @endif 
                                    @if(auth('admin')->user()->role_id =='4')
                                    Developer
                                    @endif 
                                    @if(auth('admin')->user()->role_id =='5')
                                    Reception
                                    @endif 
                                    @if(auth('admin')->user()->role_id =='6')
                                    Officer
                                    @endif 
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="bold"><a href="{{url('admin-panel/dashboard')}}" class="waves-effect waves-cyan"><i class="mdi-action-dashboard"></i> Dashboard</a>
                    </li>
                     <!--sidebar nav start-->
                     
                    @php
                    
                        $menus = App\Model\Menu::whereNull('parent_id')->whereStatus(1)->whereHas('permission',function($query){
                                    $query->whereHas('permission',function($query){ 
                                        $query->where(['role_id'=>auth('admin')->user()->role_id, 'company_id'=>json_decode(Session::get('CIDATA'))->cid]);
                                        
                                    });
                                    $query->where('key','like','browse%');
                                    $query->orderBy('order','asc');
                                })->orWhere(function($query){
                                    $query->whereNull('controller');
                                    $query->whereStatus(1);
                                    $query->orderBy('order','asc');
                                })->with(['child'])->orderBy('order','asc')->get();
                                
                    @endphp
                         
                        @foreach ($menus as $menu)
                        
                            @if($menu->controller && !$menu->child->count())
                                
                                <li class="bold {{ request()->segment(2) == str_slug(strtolower($menu->table_name), '-')?'active':''}}"><a class="waves-effect waves-cyan" href="{{ route('admin.'.str_slug(strtolower($menu->table_name), '-').'.index')}}"><i class="{{ $menu->icon??'mdi-image-grid-on' }}"></i> {{ $menu->title }}</a></li>
                                
                            @endif
                            @if($menu->child->count())
                                @php
                                    $childs = App\Model\Menu::where('parent_id',$menu->id)->whereStatus(1)->whereHas('permission',function($query){
                                        $query->whereHas('permission',function($query){ 
                                            $query->where('role_id',auth('admin')->user()->role_id);
                                            $query->where('company_id',json_decode(Session::get('CIDATA'))->cid);
                                        });
                                     $query->where('key','like','browse%');
                                    })->get();
                                @endphp 
                                
                                <li class="bold {{ ($menu->child->whereIn('table_name',str_replace('-', '_', request()->segment(2)))->count())?'active':'' }}">
                                    <a href="javascript:;" class="collapsible-header waves-effect waves-cyan {{ ($menu->child->whereIn('table_name',str_replace('-', '_', request()->segment(2)))->count())?'active':'' }}"><i class="{{ $menu->icon??'mdi-image-grid-on' }}"></i>  {{ $menu->title }}</a>
                                    <div class="collapsible-body">
                                        <ul class="child-list">                                      
                                            @foreach($childs as $child)
                                                 <li class="{{ ($child->table_name == str_replace('-', '_', request()->segment(2)))?'active':'' }}"><a href="{{ route('admin.'.str_slug(strtolower($child->table_name), '-').'.index')}}"><i class="mdi-subdirectory-arrow-right"></i> {{ $child->title }}</a></li>
                                            @endforeach  
                                           
                                        </ul>
                                    </div>
                                </li>
                            @endif
                        @endforeach                    
                </ul>
                <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only darken-2"><i class="mdi-navigation-menu" ></i></a>
            </aside>
            <!-- END LEFT SIDEBAR NAV-->
