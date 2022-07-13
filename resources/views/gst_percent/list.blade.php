<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-10">
                <h4 class="card-title">GST Summary</h4>
            </div>
            <div class="col-md-2 text-end" >
              @if(Auth::user()->hasPermissionTo('gst.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                      
                <a onclick='return $("#gst_add_modal").modal("show");' class="btn btn-inverse-primary btn-sm">{{__("Add GST")}}</a>
              @endif
            </div>
        </div>
        
        <div class="table-responsive">
          <table id="gst_table" class="table ">
            <thead>
              <tr>
                <th>Sr.no.</th>
                <th>Percentage</th>
                <th>Created At</th>
                <th>Created By/Updated By</th>
                @if(Auth::user()->hasPermissionTo('gst.edit') || Auth::user()->hasPermissionTo('gst.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                <th>Action</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($gst as $key => $h)
                <tr>
                    <td>{{$key+1}}</td>
                    
                    <td>{{$h->percent}}</td>
                    
                    <td>{{date('d-m-Y',strtotime($h->created_at))}}</td>
                    <td>{{!empty($h->created_by)?$h->created_by_user['name']:""}}  {{!empty($h->updated_by)?'/'.$h->updated_by_user->name :""}}</td>
                    
                    @if(Auth::user()->hasPermissionTo('gst.edit') || Auth::user()->hasPermissionTo('gst.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                
                    <td>
                      @if(Auth::user()->hasPermissionTo('gst.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                      
                        <a onclick='edit_gst_modal("{{$h->id}}")' class="btn btn-success btn-sm text-white">
                            <i class="mdi mdi-pen"></i>
                        </a>
                      @endif
                      @if(Auth::user()->hasPermissionTo('gst.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                      
                        <a onclick='return $("#{{$h->id}}_gst").modal("show");' class="btn btn-danger btn-sm text-white">
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