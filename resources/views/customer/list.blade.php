<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-10">
                <h4 class="card-title">Customers Summary</h4>
            </div>
            @if(Auth::user()->hasPermissionTo('customers.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
            <div class="col-md-2 text-end" >
              <button onclick='return $("#add_modal").modal("show");' class="btn btn-inverse-primary btn-sm">{{__("Add Customer")}}</button>
            </div>
            @endif
        </div>
        <div class="table-responsive">
          <table id="customer_table" class="table ">
            <thead>
              <tr>
                <th>Sr.no.</th>
                <th>Company</th>
                <th>Name</th>
                <th>Email</th>
                <th>Primary Contact</th>
                <th>Primary Email</th>
                <th>Created At</th>
                <th>Created By</th>
                @if(Auth::user()->hasPermissionTo('customers.edit') || Auth::user()->hasPermissionTo('customers.delete') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                <th>Action</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($customers as $key => $cat)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$cat->company}}</td>
                    <td>{{$cat->name}}</td>
                    <td>{{$cat->email}}</td>
                    <td></td>
                    <td></td>
                    <td>{{date('d-m-Y',strtotime($cat->created_at))}}</td>
                    <td>{{!empty($cat->created_by)?$cat->created_by_user['name']:""}}</td>
                    @if(Auth::user()->hasPermissionTo('customers.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                
                        <td>
                        @if(Auth::user()->hasPermissionTo('customers.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                            <a onclick="edit_modal('{{$cat->id}}')" class="btn btn-success btn-sm text-white">
                                <i class="mdi mdi-pen"></i>
                            </a>
                            <a href="{{url('customer-contact/'.$cat->id)}}"  class="btn btn-warning btn-sm text-white">
                                Contact
                            </a>
                        @endif
                        
                        @if(Auth::user()->hasPermissionTo('customers.delete') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
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