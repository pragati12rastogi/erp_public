<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-9">
                <h4 class="card-title">User Summary</h4>
            </div>
            
            <div class="col-md-3 text-end">
              @if(Auth::user()->hasPermissionTo('users.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                <button onclick='return $("#add_user_modal").modal("show");' class="btn btn-inverse-primary btn-sm">{{__("Add User")}}</button>
              @endif
              <div class="btn-group">
                <a href="{{route('users.export','excel')}}" class="btn btn-dark btn-sm">{{__("Excel")}}</a>
                <a href="{{route('users.export','pdf')}}" class="btn btn-dark btn-sm">{{__("PDF")}}</a>
              </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
          <div class="input-group input-daterange">
            <input autocomplete="off" type="date" id="min-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="From:">
            <div class="input-group-text">To</div>
            <input autocomplete="off" type="date" id="max-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="To:">
          </div>
        </div>
        <div class="table-responsive">
          <table id="user_table" class="table ">
            <thead>
              <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Firm Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Created At</th>
                <th>Created By/Updated By</th>
                <th>Status</th>
                @if(Auth::user()->hasPermissionTo('users.edit') || Auth::user()->hasPermissionTo('users.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                <th>Action</th>
                @endif
              </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
