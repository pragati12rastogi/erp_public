<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-10">
                <h4 class="card-title">Hsn Summary</h4>
            </div>
            @if(Auth::user()->hasPermissionTo('hsn.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
            <div class="col-md-2 text-end" >
              <button onclick='return $("#add_hsn_modal").modal("show");' class="btn btn-inverse-primary btn-sm">{{__("Add HSN")}}</button>
            </div>
            @endif
        </div>
        
        <div class="table-responsive">
          <table id="hsn_table" class="table ">
            <thead>
              <tr>
                <th>Sr.no.</th>
                <th>Hsn No</th>
                <th>Created At</th>
                <th>Created By/Updated BY</th>
                @if(Auth::user()->hasPermissionTo('hsn.edit') || Auth::user()->hasPermissionTo('hsn.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                <th>Action</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($hsns as $key => $h)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$h->hsn_no}}</td>
                    
                    <td>{{date('d-m-Y',strtotime($h->created_at))}}</td>
                    <td>{{!empty($h->created_by)?$h->created_by_user['name']:""}}  {{!empty($h->updated_by)? '/'.$h->updated_by_user->name : ""}}</td>
                    
                    @if(Auth::user()->hasPermissionTo('hsn.edit') || Auth::user()->hasPermissionTo('hsn.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                
                    <td>
                      @if(Auth::user()->hasPermissionTo('hsn.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                      
                        <a onclick='edit_hsn_modal("{{$h->id}}")' class="btn btn-success btn-sm text-white">
                            <i class="mdi mdi-pen"></i>
                        </a>
                      @endif
                      @if(Auth::user()->hasPermissionTo('hsn.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                      
                        <a onclick='return $("#{{$h->id}}_hsn").modal("show");' class="btn btn-danger btn-sm text-white">
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
