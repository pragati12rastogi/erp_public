<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-10">
                <h4 class="card-title">Contract Summary</h4>
            </div>
            @if(Auth::user()->hasPermissionTo('contract.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
            <div class="col-md-2 text-end" >
              <button onclick='return $("#add_modal").modal("show");' class="btn btn-inverse-primary btn-sm">{{__("Add Contract")}}</button>
            </div>
            @endif
        </div>
        <div class="table-responsive">
          <table id="contracts_table" class="table ">
            <thead>
              <tr>
                <th>Sr.no.</th>
                <th>Subject</th>
                <th>Customer</th>
                <th>Contract Type</th>
                <th>Contract Value</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Created At</th>
                <th>Created By/Updated By</th>
                @if(Auth::user()->hasPermissionTo('contract.edit') || Auth::user()->hasPermissionTo('contract.delete') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                
                <th>Action</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($contracts as $key => $cat)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$cat->subject}}</td>
                    <td>{{$cat->customer->name}}</td>
                    <td>{{$cat->type}}</td>
                    <td>{{$cat->value}}</td>
                    
                    <td>{{date('d-m-Y',strtotime($cat->start_date))}}</td>
                    <td>{{date('d-m-Y',strtotime($cat->end_date))}}</td>
                    <td>{{date('d-m-Y',strtotime($cat->created_at))}}</td>
                    <td>{{!empty($cat->created_by)?$cat->created_by_user['name']:""}}  {{!empty($cat->updated_by)? '/'.$cat->updated_by_user->name:"" }}</td>
                    @if(Auth::user()->hasPermissionTo('contract.edit') || Auth::user()->hasPermissionTo('contract.delete') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                
                        <td>
                        @if(Auth::user()->hasPermissionTo('contract.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                            <a onclick="edit_modal('{{$cat->id}}')" class="btn btn-success btn-sm text-white">
                                <i class="mdi mdi-pen"></i>
                            </a>
                            <a onclick="renew_modal('{{$cat->id}}')" class="btn btn-warning btn-sm text-white">
                                <i class="mdi mdi-refresh"></i>
                            </a>
                        @endif
                        @if(Auth::user()->hasPermissionTo('contract.delete') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                            <a onclick="delete_modal('{{$cat->id}}')" class="btn btn-danger btn-sm text-white">
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