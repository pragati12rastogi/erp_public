<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-10">
                <h4 class="card-title">Category Summary</h4>
            </div>
            @if(Auth::user()->hasPermissionTo('category.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
            <div class="col-md-2 text-end" >
              <button onclick='return $("#add_category_modal").modal("show");' class="btn btn-inverse-primary btn-sm">{{__("Add Category")}}</button>
            </div>
            @endif
        </div>
        <div class="table-responsive">
          <table id="category_table" class="table ">
            <thead>
              <tr>
                <th>Sr.no.</th>
                <th>Name</th>
                <th>Image</th>
                <th>Created At</th>
                <th>Created By/Updated By</th>
                @if(Auth::user()->hasPermissionTo('category.edit') || Auth::user()->hasPermissionTo('category.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                
                <th>Action</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($categories as $key => $cat)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$cat->name}}</td>
                    
                    <td>
                      @if($cat->image != '' && file_exists(public_path().'/uploads/category/'.$cat->image) )
                          <img class="img-thumbnail"  src="{{url('uploads/category/'.$cat->image)}}" title="{{ $cat->name }}">
                      @endif
                    </td>
                    
                    <td>{{date('d-m-Y',strtotime($cat->created_at))}}</td>
                    <td>{{!empty($cat->created_by)?$cat->created_by_user['name']:""}}  {{!empty($cat->updated_by)? '/'.$cat->updated_by_user->name:"" }}</td>
                    @if(Auth::user()->hasPermissionTo('category.edit') || Auth::user()->hasPermissionTo('category.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                
                    <td>
                      @if(Auth::user()->hasPermissionTo('category.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                        <a onclick='return $("#{{$cat->id}}_category_edit_modal").modal("show");' class="btn btn-success btn-sm text-white">
                            <i class="mdi mdi-pen"></i>
                        </a>
                      @endif
                      @if(Auth::user()->hasPermissionTo('category.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                        <a onclick='return $("#{{$cat->id}}_cat").modal("show");' class="btn btn-danger btn-sm text-white">
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
