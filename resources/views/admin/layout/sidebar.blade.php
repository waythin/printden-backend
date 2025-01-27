<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion position-fixed" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon">
            <img class="img-fluid" src="{{ asset('admin/printden_main.svg') }}" style="width: 80px;" />
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0 pt-3">
    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ strpos(request()->url(), 'dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <img class="img-fluid" src="{{ asset('admin/img/icons/dashboard.svg') }}" />
            <span>Dashboard</span></a>
    </li>

    <li class="nav-item {{ strpos(request()->url(), 'order') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.orders') }}">
            <img class="img-fluid" src="{{ asset('admin/img/icons/order.svg') }}" />
            <span>Orders</span></a>
    </li>
    <li class="nav-item {{ strpos(request()->url(), 'customer') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.customers') }}">
            <img class="img-fluid" src="{{ asset('admin/store.svg') }}" />
            <span>Customers Record</span></a>
    </li>

    <li class="nav-item {{ strpos(request()->url(), 'review') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.review') }}">
            <img class="img-fluid" src="{{ asset('admin/store.svg') }}" />
            <span>Review Ratings</span></a>
    </li>

    <li class="nav-item {{ strpos(request()->url(), 'offers/list') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('admin.offers')}}">
            <img class="img-fluid" src="{{ asset('admin/contact.svg') }}" />
            <span>Offers</span></a>
    </li>

    <li class="nav-item {{ strpos(request()->url(), 'contact/list') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('admin.contact.list')}}">
            <img class="img-fluid" src="{{ asset('admin/contact.svg') }}" />
            <span>Contact Us</span></a>
    </li>

    
  
  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">
    <div class="profile-icon d-flex justify-content-around justify-content-sm-between align-items-center">
        <div class="d-none d-sm-block" href="#"> 
            <div class="profile-img d-none d-sm-block">
                @if (!empty(Auth::guard('admin')->user()->image))
                    <img class="img-profile rounded-circle p-img1" src="{{ asset(Auth::guard('admin')->user()->image) }}" alt="profile" data-toggle="tooltip" data-placement="top" title="{{ Auth::guard('admin')->user()->name }}">
                @else
                    <img class="img-profile rounded-circle p-img1" src="{{ asset('dummy/no_image.png') }}" alt="profile" data-toggle="tooltip" data-placement="top" title="{{ Auth::guard('admin')->user()->name ?? null}}">
                @endif
            </div>
        </div>
        <li class="dropdown nav-item">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                
            </a>
            <!-- Dropdown - User Information -->
            
            <div class="dropdown-menu" aria-labelledby="userDropdown">
                
                {{-- {{ route('admin.settings', 'account-settings') }} --}}
                <a class="dropdown-item profile-dropdown" href="">
                    Settings
                    <img class="img-profile hover-prof" src="{{ asset('admin/img/icons/settings.svg') }}" alt="profile">
                    
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item profile-dropdown" href="#" data-toggle="modal" data-target="#logoutModal">
                    Logout
                    <img class="img-profile" src="{{ asset('admin/img/logout.svg') }}" alt="profile">
                    
                </a>
            </div>
        </li>
    </div>

    {{-- @if(Auth::guard('admin')->user()->merchant_id)
        <li class="nav-item {{ strpos(request()->url(), '') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.settings', 'company-details') }}">
                <img class="rounded-circle img-fluid pro" src="{{asset(Auth::guard('admin')->user()->merchant->logo_url)}}" alt="">
                <span>{{ Auth::guard('admin')->user()->merchant->c_name }}</span>
            </a>
        </li>
    @endif  --}}
    
</ul>
<!-- End of Sidebar -->
