<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-9">
                <h4 class="card-title">Vendor Summary</h4>
            </div>
                
            <div class="col-md-3 text-end">
              @if(Auth::user()->hasPermissionTo('vendors.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                <a onclick='return $("#add_vendor_modal").modal("show");' class="btn btn-inverse-primary btn-sm ">{{__("Add Vendor")}}</a>
              @endif
              <div class="btn-group">
                <a href="{{route('vendors.export','excel')}}" class="btn btn-dark btn-sm">{{__("Excel")}}</a>
                <a href="{{route('vendors.export','pdf')}}" class="btn btn-dark btn-sm">{{__("PDF")}}</a>
              </div>
            </div>
        </div>
        
        <div class="table-responsive">
          <table id="vendor_table" class="table ">
            <thead>
              <tr>
                <th>Name</th>
                <th>Firm Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Created At</th>
                <th>Created By/Updated By</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($vendors as $vendor)
              <tr>
                  <td>{{$vendor->name}}</td>
                  <td>{{$vendor->firm_name}}</td>
                  <td>{{$vendor->email}}</td>
                  <td>{{$vendor->phone}}</td>
                  <td>{{date('Y-m-d',strtotime($vendor->created_at))}}</td>
                  <td>
                    
                    {{!empty($vendor->created_by)?$vendor->created_by_user->name:''}}{{!empty($vendor->updated_by)? '/'.$vendor->updated_by_user->name:'' }}</td>
                    
                  <td>
                  @if(Auth::user()->hasPermissionTo('vendors.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                    <a  onclick='edit_vendor_modal("{{$vendor->id}}")' class="btn btn-success btn-sm text-white">
                        <i class="mdi mdi-pen"></i>
                    </a>
                  @endif
                  @if(Auth::user()->hasPermissionTo('vendors.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                      
                    <a onclick='return $("#{{$vendor->id}}_vendor").modal("show");' class="btn btn-danger btn-sm text-white">
                        <i class=" mdi mdi-delete-forever"></i>
                    </a>
                  @endif
                  </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>