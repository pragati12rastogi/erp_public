<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-10">
                <h4 class="card-title">Contract Type Summary</h4>
            </div>
            @if(Auth::user()->hasPermissionTo('contract-type.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
            <div class="col-md-2 text-end" >
              <button onclick='return $("#add_modal").modal("show");' class="btn btn-inverse-primary btn-sm">{{__("Add Contract Type")}}</button>
            </div>
            @endif
        </div>
        <div class="table-responsive">
          <table id="contracts_table" class="table ">
            <thead>
              <tr>
                <th>Sr.no.</th>
                <th>Name</th>
                @if(Auth::user()->hasPermissionTo('contract-type.edit') || Auth::user()->hasPermissionTo('contract-type.delete') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                
                <th>Action</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($tyypes as $key => $cat)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$cat->name}}</td>
                    @if(Auth::user()->hasPermissionTo('contract-type.edit') || Auth::user()->hasPermissionTo('contract-type.delete') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                
                        <td>
                        @if(Auth::user()->hasPermissionTo('contract-type.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                            <a onclick="edit_modal('{{$cat->id}}')" class="btn btn-success btn-sm text-white">
                                <i class="mdi mdi-pen"></i>
                            </a>
                        @endif
                        @if(Auth::user()->hasPermissionTo('contract-type.delete') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
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