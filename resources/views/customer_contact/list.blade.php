<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-9">
                <h4 class="card-title">Contact Summary</h4>
            </div>
            <div class="col-md-3 text-end" >
              <button onclick='return $("#add_modal").modal("show");' class="btn btn-inverse-primary btn-sm">{{__("Add Contact")}}</button>
              <button href='{{url("/customers")}}' class="btn btn-inverse-primary btn-sm">{{__("Back")}}</button>

            </div>
        </div>
        <div class="table-responsive">
          <table id="contact_table" class="table ">
            <thead>
              <tr>
                <th>Sr.no.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Position</th>
                <th>Created At</th>
                <th>Created By</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach($contacts as $key => $cat)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$cat->name}}</td>
                        <td>{{$cat->email}}</td>
                        <td>{{$cat->phone}}</td>
                        <td>{{$cat->position}}</td>
                        <td>{{date('d-m-Y',strtotime($cat->created_at))}}</td>
                        <td>{{!empty($cat->created_by)?$cat->created_by_user['name']:""}}</td>
                        <td>
                            <a onclick="edit_modal('{{$cat->id}}')" class="btn btn-success btn-sm text-white">
                                <i class="mdi mdi-pen"></i>
                            </a>
                            <a onclick="delete_modal('{{$cat->id}}')" class="btn btn-danger btn-sm text-white">
                                <i class=" mdi mdi-delete-forever"></i>
                            </a>
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