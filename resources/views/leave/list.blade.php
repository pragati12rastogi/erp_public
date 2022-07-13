<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-10">
                <h4 class="card-title">Leave Summary</h4>
            </div>
            @if(Auth::user()->hasPermissionTo('leave.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
            <div class="col-md-2 text-end" >
              <button onclick='return $("#add_leave_modal").modal("show");' class="btn btn-inverse-primary btn-sm">{{__("Apply for Leave")}}</button>
            </div>
            @endif
        </div>
        
        <div class="table-responsive">
          <table id="leave_table" class="table ">
            <thead>
              <tr>
                <th>Sr.no.</th>
                <th>User Name</th>
                <th>Leave Date</th>
                <th>Reason</th>
                <th>Approved</th>
                <th>Created At</th>
                @if( Auth::user()->hasPermissionTo('leave.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                <th>Action</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($leaves as $key => $h)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$h->user->name}}</td>
                    <td>{{date('d-m-Y',strtotime($h->from_date))}} - {{date('d-m-Y',strtotime($h->to_date))}}</td>
                    <td>{{$h->reason}}</td>
                    <td>{{$h->approved?'Yes':'No'}}</td>
                    
                    <td>{{date('d-m-Y',strtotime($h->created_at))}}</td>
                    
                    @if( Auth::user()->hasPermissionTo('holiday.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                
                    <td>
                      <div class="row">
                        
                          @if( Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) && strtotime($h->from_date) > strtotime(date('Y-m-d')) )
                            @if(!$h->approved)
                            <div class="col-md-6">
                            <form action="{{ url('leave/approve/'.$h->id) }}" method="POST">
                              {{csrf_field()}}
                              <button type="submit" class="btn btn-xs btn-success">Approve</button>
                            </form>
                            </div>
                            @endif
                          @endif
                        
                          @if(  (Auth::user()->hasPermissionTo('holiday.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN)) && strtotime($h->from_date) > strtotime(date('Y-m-d')) )
                          <div class="col-md-6">
                            <a onclick='delete_modal("{{$h->id}}")' class="btn btn-danger btn-sm text-white">
                                <i class=" mdi mdi-delete-forever"></i>
                            </a>
                          </div>
                          @endif
                        
                      </div>
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
