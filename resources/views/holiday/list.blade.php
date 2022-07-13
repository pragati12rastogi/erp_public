<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-10">
                <h4 class="card-title">Holiday Summary</h4>
            </div>
            @if(Auth::user()->hasPermissionTo('holiday.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
            <div class="col-md-2 text-end" >
              <button onclick='return $("#add_holiday_modal").modal("show");' class="btn btn-inverse-primary btn-sm">{{__("Add Holiday")}}</button>
            </div>
            @endif
        </div>
        
        <div class="table-responsive">
          <table id="holiday_table" class="table ">
            <thead>
              <tr>
                <th>Sr.no.</th>
                <th>Holiday Name</th>
                <th>Holiday Date</th>
                <th>Created At</th>
                <th>Created By</th>
                @if(Auth::user()->hasPermissionTo('holiday.edit') || Auth::user()->hasPermissionTo('holiday.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                <th>Action</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($holidays as $key => $h)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$h->name}}</td>
                    <td>{{date('d-m-Y',strtotime($h->date))}}</td>
                    
                    <td>{{date('d-m-Y',strtotime($h->created_at))}}</td>
                    <td>{{!empty($h->created_by)?$h->created_by_user['name']:""}} </td>
                    
                    @if(Auth::user()->hasPermissionTo('holiday.edit') || Auth::user()->hasPermissionTo('holiday.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                
                    <td>
                      @if(Auth::user()->hasPermissionTo('holiday.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                      
                        <a onclick='edit_modal("{{$h->id}}")' class="btn btn-success btn-sm text-white">
                            <i class="mdi mdi-pen"></i>
                        </a>
                      @endif
                      @if(Auth::user()->hasPermissionTo('holiday.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                      
                        <a onclick='delete_modal("{{$h->id}}")' class="btn btn-danger btn-sm text-white">
                            <i class=" mdi mdi-delete-forever"></i>
                        </a>
                      @endif
                    </td>
                    @endif
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
