<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          @if(!empty(Auth::user()->profile) && file_exists(public_path().'/uploads/user_profile/'.Auth::user()->profile))
            <img src="{{url('/uploads/user_profile/'.Auth::user()->profile)}}" alt="profile image">
            <span class="login-status online"></span>
          @else
            <img src="assets/images/faces/face1.jpg" alt="profile">
            <span class="login-status online"></span>
          @endif
          
          <!--change to offline or busy as needed-->
        </div>
        <div class="nav-profile-text d-flex flex-column">
          <span class="font-weight-bold mb-2">{{Auth::user()->name}}</span>
          <span class="text-secondary text-small">{{Auth::user()->role->name}}</span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>
    <li class="nav-item {{ active_class(['/']) }}">
      <a class="nav-link" href="{{ url('/') }}">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>
    @if(Auth::user()->hasPermissionTo('users.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <li class="nav-item {{ active_class(['users','users/*']) }}">
      <a class="nav-link" href="{{ url('/users') }}">
        <span class="menu-title">Users</span>
        <i class=" mdi mdi-human menu-icon"></i>
      </a>
    </li>
    @endif
    @if(Auth::user()->hasPermissionTo('roles.index') || Auth::user()->hasPermissionTo('category.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) || Auth::user()->hasPermissionTo('hsn.index') || Auth::user()->hasPermissionTo('gst.index') || Auth::user()->hasPermissionTo('vendors.index') || Auth::user()->hasPermissionTo('invoice.master') || Auth::user()->hasPermissionTo('states.index') || Auth::user()->hasPermissionTo('districts.index') || Auth::user()->hasPermissionTo('areas.index') || Auth::user()->hasPermissionTo('email-master.index') || Auth::user()->hasPermissionTo('sms.master') || Auth::user()->hasPermissionTo('general.master') || Auth::user()->hasPermissionTo('expense-master.index') || Auth::user()->hasPermissionTo('payment-gateway.master') )
    <li class="nav-item {{ active_class(['roles','roles/*','category','category/*','hsn','hsn/*','gst','gst/*','vendors','vendors/*','invoice/setting','states','states/*','districts','districts/*','areas','areas/*','email/setting','sms/setting','general/setting','expense-master','expense-master/*','payment-gateway/setting']) }}">
      <a class="nav-link" data-toggle="collapse" href="#basic-ui" aria-expanded="{{ is_active_route(['roles','roles/*','category','category/*','hsn','hsn/*','gst','gst/*','vendors','vendors/*','invoice/setting','states','states/*','districts','districts/*','areas','areas/*','email/setting','sms/setting','general/setting','expense-master','expense-master/*','payment-gateway/setting']) }}" aria-controls="basic-ui">
        
        <span class="menu-title">Master</span>
        <i class="menu-arrow"></i><i class="menu-icon mdi mdi-widgets"></i>
      </a>
      <div class="collapse {{ show_class(['roles','roles/*','category','category/*','hsn','hsn/*','gst','gst/*','vendors','vendors/*','invoice/setting','states','states/*','districts','districts/*','areas','areas/*','email/setting','sms/setting','general/setting','expense-master','expense-master/*','payment-gateway/setting']) }}" id="basic-ui">
        <ul class="nav flex-column sub-menu">
          @if(Auth::user()->hasPermissionTo('roles.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['roles','roles/*']) }}">
            <a class="nav-link" href="{{ url('/roles') }}">
              Roles
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('category.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['category','category/*']) }}">
            <a class="nav-link" href="{{ url('/category') }}">
              Category
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('hsn.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['hsn','hsn/*']) }}">
            <a class="nav-link" href="{{ url('/hsn') }}">
              Hsn
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('gst.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['gst','gst/*']) }}">
            <a class="nav-link" href="{{ url('/gst') }}">
              GST
            </a>
          </li>
          @endif
          
          @if(Auth::user()->hasPermissionTo('states.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['states','states/*']) }}">
            <a class="nav-link" href="{{ url('/states') }}">
              State
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('districts.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['districts','districts/*']) }}">
            <a class="nav-link" href="{{ url('/districts') }}">
              District
            </a>
          </li>
          @endif

          @if(Auth::user()->hasPermissionTo('areas.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['areas','areas/*']) }}">
            <a class="nav-link" href="{{ url('/areas') }}">
              Area
            </a>
          </li>
          @endif

          @if(Auth::user()->hasPermissionTo('vendors.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['vendors','vendors/*']) }}">
            <a class="nav-link" href="{{ url('/vendors') }}">
              Vendors
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('invoice.master') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['invoice/setting']) }}">
            <a class="nav-link" href="{{ url('invoice/setting') }}">
              Invoice Master
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('billing.master') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['billing/setting']) }}">
            <a class="nav-link" href="{{ url('billing/setting') }}">
              Billing Address Master
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('email-master.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['email/setting']) }}">
            <a class="nav-link" href="{{ url('email/setting') }}">
              Email Setting
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('sms.master') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['sms/setting']) }}">
            <a class="nav-link" href="{{ url('sms/setting') }}">
              SMS Setting
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('general.master') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['general/setting']) }}">
            <a class="nav-link" href="{{ url('general/setting') }}">
              General Setting
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('expense-master.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['expense-master','expense-master/*']) }}">
            <a class="nav-link" href="{{ url('expense-master') }}">
              Expense Master
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('payment-gateway.master') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['payment-gateway/setting']) }}">
            <a class="nav-link" href="{{ url('payment-gateway/setting') }}">
              Payment Gateway
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('lead-status.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['lead-status']) }}">
            <a class="nav-link" href="{{ url('lead-status') }}">
              Lead Status
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('lead-sources.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['lead-sources']) }}">
            <a class="nav-link" href="{{ url('lead-sources') }}">
              Lead Sources
            </a>
          </li>
          @endif
          
        </ul>
      </div>
    </li>
    @endif
    @if(Auth::user()->hasPermissionTo('item.index') || Auth::user()->hasPermissionTo('stocks.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN)  || Auth::user()->hasPermissionTo('stock-distributions.index') || Auth::user()->hasPermissionTo('users-stock.list') || Auth::user()->hasPermissionTo('expenses.index') || Auth::user()->hasPermissionTo('profit-chart.index') || Auth::user()->hasPermissionTo('product_charge.index') || Auth::user()->hasPermissionTo('purchase-order.index'))
    <li class="nav-item {{ active_class(['item','item/*','stocks','stocks/*','stock-distributions','stock-distributions/*','local-stock-distribution','local-stock-distribution/*','users-stock/list','expenses','expenses/*','profit-chart']) }}">
      <a class="nav-link" data-toggle="collapse" href="#basic-ui2" aria-expanded="{{ is_active_route(['item','item/*','stocks','stocks/*','stock-distributions','stock-distributions/*','local-stock-distribution','local-stock-distribution/*','users-stock/list','expenses','expenses/*','profit-chart','product_charge','purchase-order','purchase-order/*']) }}" aria-controls="basic-ui2">
        
        <span class="menu-title">Inventory</span>
        <i class="menu-arrow"></i><i class="menu-icon mdi mdi-city"></i>
      </a>
      <div class="collapse {{ show_class(['item','item/*','stocks','stocks/*','stock-distributions','stock-distributions/*','local-stock-distribution','local-stock-distribution/*','users-stock/list','expenses','expenses/*','profit-chart','product_charge','purchase-order','purchase-order/*']) }}" id="basic-ui2">
        <ul class="nav flex-column sub-menu">
          @if(Auth::user()->hasPermissionTo('item.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['item','item/*']) }}">
            <a class="nav-link" href="{{ url('/item') }}">
              Item
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('stocks.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['stocks','stocks/*']) }}">
            <a class="nav-link" href="{{ url('/stocks') }}">
              Stocks
            </a>
          </li>
          @endif

          @if(Auth::user()->hasPermissionTo('purchase-order.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['purchase-order','purchase-order/*']) }}">
            <a class="nav-link" href="{{ url('purchase-order') }}">
              Purchase Order
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('proposal-orders.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['proposal-orders','proposal-orders/*']) }}">
            <a class="nav-link" href="{{ url('proposal-orders') }}">
              Proposal Order
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('stock-distributions.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['stock-distributions','stock-distributions/*']) }}">
            <a class="nav-link" href="{{ url('stock-distributions') }}">
              Stock Distribution
            </a>
          </li>
          @endif

          @if(Auth::user()->hasPermissionTo('users-stock.list') )
          <li class="nav-item {{ active_class(['users-stock/list']) }}">
            <a class="nav-link" href="{{ url('users-stock/list') }}">
              User Stock List
            </a>
          </li>
          @endif

          @if(Auth::user()->hasPermissionTo('local-stock-distribution.index') )
          <li class="nav-item {{ active_class(['local-stock-distribution','local-stock-distribution/*']) }}">
            <a class="nav-link" href="{{ url('local-stock-distribution') }}">
              Local Stock Distribution
            </a>
          </li>
          @endif

          @if(Auth::user()->hasPermissionTo('expenses.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['expenses','expenses/*']) }}">
            <a class="nav-link" href="{{ url('/expenses') }}">
              <span class="menu-title">Expenses</span>
            </a>
          </li>
          @endif

          @if(Auth::user()->hasPermissionTo('profit-chart.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['profit-chart']) }}">
            <a class="nav-link" href="{{ url('/profit-chart') }}">
              <span class="menu-title">View Chart</span>
            </a>
          </li>
          @endif

          @if(Auth::user()->hasPermissionTo('product_charge.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['product_charge']) }}">
            <a class="nav-link" href="{{ url('/product_charge') }}">
              <span class="menu-title">Product Charge</span>
            </a>
          </li>
          @endif

        </ul>
      </div>
    </li>
    @endif
    @if(Auth::user()->hasPermissionTo('tasks.index') || Auth::user()->hasPermissionTo('mytasks.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <li class="nav-item {{ active_class(['tasks','tasks/*','mytasks','mytasks/*']) }}">
      <a class="nav-link" data-toggle="collapse" href="#task_dropdown" aria-expanded="{{ is_active_route(['tasks','tasks/*','mytasks','mytasks/*']) }}" aria-controls="task_dropdown">
        <span class="menu-title">Tasks</span>
        <i class="menu-arrow"></i><i class="menu-icon mdi mdi-star-circle"></i>
      </a>
      <div class="collapse {{ show_class(['tasks','tasks/*','mytasks','mytasks/*']) }}" id="task_dropdown">
        <ul class="nav flex-column sub-menu">
          @if(Auth::user()->hasPermissionTo('tasks.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['tasks','tasks/*']) }}">
            <a class="nav-link" href="{{ url('/tasks') }}">
              Tasks
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('mytasks.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['mytasks','mytasks/*']) }}">
            <a class="nav-link" href="{{ url('/mytasks') }}">
              My Tasks
            </a>
          </li>
          @endif
        </ul>
      </div>
    </li>
    @endif

    @if(Auth::user()->hasPermissionTo('facebook-page-subscription.master') || Auth::user()->hasPermissionTo('leads.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <li class="nav-item {{ active_class(['facebook-page-subscription/setting','leads/index']) }}">
      <a class="nav-link" data-toggle="collapse" href="#leads_dropdown" aria-expanded="{{ is_active_route(['facebook-page-subscription/setting','leads/index']) }}" aria-controls="leads_dropdown">
        <span class="menu-title">Leads</span>
        <i class="menu-arrow"></i><i class="menu-icon mdi mdi-facebook-box"></i>
      </a>
      <div class="collapse {{ show_class(['facebook-page-subscription/setting','leads/index']) }}" id="leads_dropdown">
        <ul class="nav flex-column sub-menu">
          @if(Auth::user()->hasPermissionTo('facebook-page-subscription.master') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['facebook-page-subscription/setting']) }}">
            <a class="nav-link" href="{{ url('facebook-page-subscription/setting') }}">
              FB Page Subscriptions
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('leads.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['leads']) }}">
            <a class="nav-link" href="{{ url('/leads') }}">
              Leads
            </a>
          </li>
          @endif
        </ul>
      </div>
    </li>
    @endif

    @if(Auth::user()->hasPermissionTo('holiday.index') || Auth::user()->hasPermissionTo('leave.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <li class="nav-item {{ active_class(['holiday','holiday/*','leave','leave/*']) }}">
      <a class="nav-link" data-toggle="collapse" href="#attendance_dropdown" aria-expanded="{{ is_active_route(['holiday','holiday/*','leave','leave/*']) }}" aria-controls="leads_dropdown">
        <span class="menu-title">Attendance</span>
        <i class="menu-arrow"></i><i class="menu-icon mdi mdi-calendar-clock"></i>
      </a>
      <div class="collapse {{ show_class(['holiday','holiday/*','leave','leave/*']) }}" id="attendance_dropdown">
        <ul class="nav flex-column sub-menu">
          @if(Auth::user()->hasPermissionTo('holiday.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['holiday','holiday/*']) }}">
            <a class="nav-link" href="{{ url('holiday') }}">
              Holiday Master
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('leave.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['leave','leave/*']) }}">
            <a class="nav-link" href="{{ url('/leave') }}">
              Leaves
            </a>
          </li>
          @endif
          @if(Auth::user()->hasPermissionTo('attendance.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
          <li class="nav-item {{ active_class(['/attendance']) }}">
            <a class="nav-link" href="{{ url('/attendance') }}">
              Attendance
            </a>
          </li>
          @endif
        </ul>
      </div>
    </li>
    @endif

    @if(Auth::user()->hasPermissionTo('calendar.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <li class="nav-item {{ active_class(['calendar','calendar/*']) }}">
      <a class="nav-link" href="{{ url('/calendar') }}">
        <span class="menu-title">Calendar</span>
        <i class=" mdi mdi-calendar menu-icon"></i>
      </a>
    </li>
    @endif

    

    @if(Auth::user()->hasPermissionTo('contract.index') || Auth::user()->hasPermissionTo('contract-type.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <li class="nav-item {{ active_class(['contract','contract/*','contract-type','contract-type.*']) }}">
      <a class="nav-link" data-toggle="collapse" href="#contract_dropdown" aria-expanded="{{ is_active_route(['contract','contract/*','contract-type','contract-type.*']) }}" aria-controls="contract_dropdown">
        <span class="menu-title">Contract</span>
        <i class="menu-arrow"></i><i class="menu-icon mdi mdi-android-messages"></i>
      </a>
      <div class="collapse {{ show_class(['contract','contract/*','contract-type','contract-type.*']) }}" id="contract_dropdown">
        <ul class="nav flex-column sub-menu">
          @if(Auth::user()->hasPermissionTo('contract-type.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
            <li class="nav-item {{ active_class(['contract-type','contract-type/*']) }}">
              <a class="nav-link" href="{{ url('contract-type') }}">
                Contract Type
              </a>
            </li>
          @endif
          @if(Auth::user()->hasPermissionTo('contract.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
            <li class="nav-item {{ active_class(['contract','contract/*']) }}">
              <a class="nav-link" href="{{ url('/contract') }}">
              Contracts
              </a>
            </li>
          @endif
        </ul>
      </div>
    </li>
    @endif

    @if(Auth::user()->hasPermissionTo('customers.index') || Auth::user()->hasPermissionTo('customers.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <li class="nav-item {{ active_class(['customers','customers/*']) }}">
      <a class="nav-link" href="{{ url('/customers') }}">
        <span class="menu-title">Customers</span>
        <i class=" mdi mdi-shield-account-outline menu-icon"></i>
      </a>
    </li>
    @endif
    @if(Auth::user()->hasPermissionTo('announcements.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <li class="nav-item {{ active_class(['announcements','announcements/*','backup','backup/*']) }}">
      <a class="nav-link" data-toggle="collapse" href="#utilities_dropdown" aria-expanded="{{ is_active_route(['announcements','announcements/*','backup','backup/*']) }}" aria-controls="contract_dropdown">
        <span class="menu-title">Utilities</span>
        <i class="menu-arrow"></i><i class="menu-icon mdi mdi-android-messages"></i>
      </a>
      <div class="collapse {{ show_class(['announcements','announcements/*','backup','backup/*']) }}" id="utilities_dropdown">
        <ul class="nav flex-column sub-menu">
          @if(Auth::user()->hasPermissionTo('announcements.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
            <li class="nav-item {{ active_class(['announcements','announcements/*']) }}">
              <a class="nav-link" href="{{ url('announcements') }}">
                Announcements
              </a>
            </li>
          @endif
          @if(Auth::user()->hasPermissionTo('backup.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
            <li class="nav-item {{ active_class(['backup','backup/*']) }}">
              <a class="nav-link" href="{{ url('backup') }}">
                Database Backup
              </a>
            </li>
          @endif
        </ul>
      </div>
    </li>
    @endif

    <li class="nav-item {{ active_class(['reports/sales']) }}">
      <a class="nav-link" data-toggle="collapse" href="#reports_dropdown" aria-expanded="{{ is_active_route(['reports/sales']) }}" aria-controls="contract_dropdown">
        <span class="menu-title">Reports</span>
        <i class="menu-arrow"></i><i class="menu-icon mdi mdi-android-messages"></i>
      </a>
      <div class="collapse {{ show_class(['reports/sales']) }}" id="reports_dropdown">
        <ul class="nav flex-column sub-menu">
          @if(Auth::user()->hasPermissionTo('reports.sales.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
            <li class="nav-item {{ active_class(['reports/sales']) }}">
              <a class="nav-link" href="{{ url('reports/sales') }}">
                Sales
              </a>
            </li>
          @endif
        </ul>
      </div>
    </li>
  </ul>

  
</nav>
