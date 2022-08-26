<div class="sidebar-left">
            <!--responsive view logo start-->
            <div class="logo dark-logo-bg visible-xs-* visible-sm-*">
                <a href="#">
                    <h4>T<span>radex</span></h4>
                    <!--<img width="50%" src="{{ asset('assets/image/icons/T-20-icon.png')}}" alt="">-->
                    <!--<i class="fa fa-maxcdn"></i>-->
                  <!--  <span class="brand-name">Kweda</span> -->
                </a>
            </div>
            <!--responsive view logo end-->

           <div class="sidebar-left-info">
                <!-- visible small devices start-->
                <div class=" search-field">  </div>
                <!-- visible small devices end-->

                <!--sidebar nav start-->
                <ul class="nav nav-pills nav-stacked side-navigation">
                @php
                    $menus = App\Model\Menu::whereNull('parent_id')->whereStatus(1)->whereHas('permission',function($query){
                                $query->whereHas('permission',function($query){ 
                                    $query->where('role_id',auth('admin')->user()->role_id);
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
                            <li class="{{ request()->segment(2) == str_slug(strtolower($menu->table_name), '-')?'active':''}}"><a href="{{ route('admin.'.str_slug(strtolower($menu->table_name), '-').'.index')}}"><i class="{{ $menu->icon??'fa fa-arrow-right' }}"></i> <span>{{ $menu->title }}</span></a></li>
                        @endif
                        @if($menu->child->count())
                            @php
                                $childs = App\Model\Menu::where('parent_id',$menu->id)->whereStatus(1)->whereHas('permission',function($query){
                                    $query->whereHas('permission',function($query){ 
                                        $query->where('role_id',auth('admin')->user()->role_id);
                                    });
                                 $query->where('key','like','browse%');
                                })->get();
                            @endphp 
                            <li class="menu-list {{ ($menu->child->whereIn('table_name',str_replace('-', '_', request()->segment(2)))->count())?'nav-active':'' }}">
                                <a href="javascript:;"><i class="{{ $menu->icon??'fa fa-list' }}"></i>  <span>{{ $menu->title }}</span></a>
                                <ul class="child-list">
                                    @foreach($childs as $child)
                                         <li class="{{ ($child->table_name == str_replace('-', '_', request()->segment(2)))?'active':'' }}"><a href="{{ route('admin.'.str_slug(strtolower($child->table_name), '-').'.index')}}"> {{ $child->title }}</a></li>
                                    @endforeach  
                                   
                                </ul>
                            </li>
                        @endif
                    @endforeach


                </ul>
                <!--sidebar nav end-->

               

            </div>
        </div>