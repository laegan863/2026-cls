@php
    use App\Models\Module;
    
    // Get dynamic sidebar based on user's role
    $user = auth()->user();
    $sidebarModules = Module::getSidebarMenu($user);
@endphp
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="fas fa-bolt"></i>
        </div>
        <span class="sidebar-brand-text">Blue<span>side</span></span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-title">Main Menu</div>
        @foreach($sidebarModules as $module)
            @php
                $isActive = false;
                $isDisabled = $module->is_coming_soon || !$module->route;
                
                // Check if current route matches (handle both direct and .index routes)
                if ($module->route) {
                    $routeBase = $module->route;
                    if (\Route::has($routeBase) || \Route::has($routeBase . '.index')) {
                        $isActive = request()->routeIs($routeBase) || request()->routeIs($routeBase . '.*');
                    }
                }
                
                // Check if any child is active
                if ($module->children && $module->children->count() > 0) {
                    foreach ($module->children as $child) {
                        if ($child->route) {
                            $childBase = $child->route;
                            if ((\Route::has($childBase) || \Route::has($childBase . '.index')) && request()->routeIs($childBase . '*')) {
                                $isActive = true;
                                break;
                            }
                        }
                    }
                }
            @endphp
            
            @if($module->children && $module->children->count() > 0)
                {{-- Module with children - collapsible --}}
                <div class="nav-item-group">
                    <a href="#collapse-{{ $module->slug }}" 
                       class="nav-link {{ $isActive ? 'active' : '' }}" 
                       data-bs-toggle="collapse" 
                       aria-expanded="{{ $isActive ? 'true' : 'false' }}">
                        <i class="{{ $module->icon }}"></i>
                        <span>{{ $module->name }}</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse {{ $isActive ? 'show' : '' }}" id="collapse-{{ $module->slug }}">
                        <div class="nav-submenu">
                            @foreach($module->children as $child)
                                @php
                                    $childRouteName = $child->route;
                                    $childRouteExists = $childRouteName && \Route::has($childRouteName);
                                    
                                    // If route doesn't exist, try with .index suffix (for resource routes)
                                    if (!$childRouteExists && $childRouteName && \Route::has($childRouteName . '.index')) {
                                        $childRouteName = $childRouteName . '.index';
                                        $childRouteExists = true;
                                    }
                                    
                                    $childActive = $childRouteExists && request()->routeIs($child->route . '*');
                                    $childDisabled = $child->is_coming_soon || !$childRouteExists;
                                @endphp
                                <a href="{{ $childRouteExists && !$childDisabled ? route($childRouteName) : '#' }}" 
                                   class="nav-link nav-link-sub {{ $childActive ? 'active' : '' }} {{ $childDisabled ? 'disabled' : '' }}">
                                    <i class="{{ $child->icon ?? 'bi bi-circle' }}"></i>
                                    <span>
                                        {{ $child->name }}
                                        @if($child->is_coming_soon)
                                            <span style="font-size: 0.7rem" class="text-danger">(Coming Soon)</span>
                                        @endif
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                {{-- Single module item --}}
                @php
                    // Handle resource routes - check for .index suffix
                    $routeName = $module->route;
                    $routeExists = $routeName && \Route::has($routeName);
                    
                    // If route doesn't exist, try with .index suffix (for resource routes)
                    if (!$routeExists && $routeName && \Route::has($routeName . '.index')) {
                        $routeName = $routeName . '.index';
                        $routeExists = true;
                    }
                @endphp
                <a href="{{ $routeExists ? route($routeName) : '#' }}" 
                   class="nav-link {{ $isActive ? 'active' : '' }} {{ $isDisabled ? 'disabled' : '' }}">
                    <i class="{{ $module->icon }}"></i>
                    <span>
                        {{ $module->name }}
                        @if($module->is_coming_soon)
                            <span style="font-size: 0.7rem" class="text-danger">(Coming Soon)</span>
                        @endif
                    </span>
                </a>
            @endif
        @endforeach
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="sidebar-footer-user">
            <div class="sidebar-footer-avatar">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'Guest') }}&background=d4a94c&color=1a2b4a&bold=true"
                    alt="{{ $user->name ?? 'Guest' }}">
            </div>
            <div class="sidebar-footer-info">
                <span class="sidebar-footer-name">{{ $user->name ?? 'Guest' }}</span>
                <span class="sidebar-footer-role">{{ $user->role->name ?? 'User' }}</span>
            </div>
            <div class="sidebar-footer-actions">
                <a href="{{ route('admin.settings') }}" class="sidebar-footer-btn" title="Settings">
                    <i class="fas fa-cog"></i>
                </a>
                <form action="{{ route('auth.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="sidebar-footer-btn" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>