<nav class="sidebar d-print-none">
    <div class="sidebar-header">
        <a href="{{ url('/home') }}" class="sidebar-brand">
            Chief <span>Drug</span>
        </a>
        <div class="sidebar-toggler {{ request()->is('purchases/create') || request()->is('purchases/*/edit') || (request()->is('sales/create') && pos_setting() !== 'list_design') ? 'active' : 'not-active' }}">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
                <li class="nav-item nav-category">Main</li>
            @foreach($sidebar as $key => $menu)
                @if(array_key_exists('subMenu', $menu) && count($menu['subMenu']))
                    @if($menu['permission'])
                        <li class="nav-item {{ active_class(collect($menu['subMenu'])->pluck('path')->toArray()) }}">
                            <a class="nav-link" data-toggle="collapse"
                               href="#{{ $menu['id'] }}" role="button"
                               aria-expanded="{{ is_active_route(collect($menu['subMenu'])->pluck('path')->toArray()) }}" aria-controls="email">
                                <i class="link-icon" data-feather="{{ $menu['icon'] }}"></i>
                                <span class="link-title">{{ $menu['name'] }}</span>
                                <i class="link-arrow" data-feather="chevron-down"></i>
                            </a>
                            <div class="collapse {{ show_class(collect($menu['subMenu'])->pluck('path')->toArray()) }}" id="{{ $menu['id'] }}">
                                <ul class="nav sub-menu">
                                    @foreach($menu['subMenu'] as $submenu)
                                        @if($submenu['permission'])
                                            <li class="nav-item">
                                                <a href="{{ $submenu['url'] }}"
                                                   class="nav-link {{ active_class([$submenu['path']]) }}">{{ $submenu['name'] }}</a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @endif
                @else
                    @if($menu['permission'])
                        <li class="nav-item {{ active_class([$menu['path']]) }}">
                            <a href="{{ $menu['url'] }}" class="nav-link">
                                <i class="link-icon" data-feather="{{ $menu['icon'] }}"></i>
                                <span class="link-title">{{ $menu['name'] }}</span>
                            </a>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
    </div>
</nav>
