<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo" href="{{ url('/') }}">
      @if(!empty($general_settings) && !empty($general_settings['logo']) && file_exists(public_path().'/images/general/'.$general_settings['logo']) )
      <img src="{{ url('/images/general/'.$general_settings['logo']) }}" alt="logo" /> 
      @else
      <img src="{{url('assets/images/logo.svg')}}" alt="logo" /> 
      @endif
    </a>
    <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}">
      @if(!empty($general_settings) && !empty($general_settings['favicon']) && file_exists(public_path().'/images/general/'.$general_settings['favicon']) )
      <img src="{{ url('/images/general/'.$general_settings['favicon']) }}" alt="logo" /> 
      @else
      <img src="{{ url('assets/images/logo-mini.svg') }}" alt="logo" />
      @endif
    </a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-stretch">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="mdi mdi-menu"></span>
    </button>
    <ul class="navbar-nav navbar-nav-left header-links">
      <li class="nav-item d-none d-xl-flex">
        <a href="{{url('/')}}" class="nav-link">Dashboard 
        </a>
      </li>
    </ul>
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item ">
        <button class="nav-link btn btn-link " title="checkIn and checkOut" data-toggle="modal" data-target="#checkInOut">
        <i class="mdi mdi-history"></i>
        </button>
      </li>
      <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="nav-profile-img">
            @if(!empty(Auth::user()->profile) && file_exists(public_path().'/uploads/user_profile/'.Auth::user()->profile))
              <img src="{{url('/uploads/user_profile/'.Auth::user()->profile)}}" alt="profile image">
              <span class="availability-status online"></span>
            @else
              <img src="assets/images/faces/face1.jpg" alt="image">
              <span class="availability-status online"></span>
            @endif
          </div>
          <div class="nav-profile-text">
            <p class="mb-1 text-black">{{Auth::user()->name}}</p>
          </div>
        </a>
        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="{{url('user-profile/update')}}">
            <i class="mdi mdi-cached me-2 text-success"></i> Manage Accounts </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            <i class="mdi mdi-logout me-2 text-primary"></i> Signout </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
          <i class="mdi mdi-bell-outline"></i>
          @if(auth()->user()->unreadnotifications->where('n_type','=','product_request')->count())
          <span class="count-symbol bg-danger" id="countNoti"></span>
          @endif
          
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
        @if(auth()->user()->unreadnotifications->where('n_type','=','product_request')->count()>0)
          <h6 class="p-3 mb-0">Notifications</h6>
        @else
          <h6 class="p-3 mb-0">No Notifications</h6>
        @endif
          <div class="dropdown-divider"></div>
          @if(auth()->user()->unreadnotifications->where('n_type','=','product_request')->count()>0)
            @foreach(auth()->user()->unreadnotifications->where('n_type','=','product_request') as $notification)
            <a onclick="markread('{{ $notification->id }}')"  href="{{ url($notification->url) }}" class="dropdown-item preview-item">
              <div class="preview-thumbnail">
                <div class="preview-icon bg-success">
                  <i class="mdi mdi-calendar"></i>
                </div>
              </div>
              <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                <h6 class="preview-subject font-weight-normal mb-1">{{ $notification->data['data'] }}</h6>
                <p class="text-gray ellipsis mb-0"> {{ date('jS M y',strtotime($notification->created_at)) }} </p>
              </div>
            </a>
            <div class="dropdown-divider"></div>
            @endforeach
          @endif
        </div>
      </li>
      <li class="nav-item d-none d-lg-block full-screen-link">
        <a class="nav-link">
          <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
        </a>
      </li>
      
      
      <li class="nav-item nav-logout d-none d-lg-block">
        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="mdi mdi-power"></i>
        </a>
      </li>
      
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>
