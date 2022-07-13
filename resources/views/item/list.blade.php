<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-10">
                <h4 class="card-title">Item Summary</h4>
            </div>
            <div class="col-md-2 text-end" >
              @if(Auth::user()->hasPermissionTo('item.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                      
                <a onclick='return $("#item_add_modal").modal("show");' class="btn btn-inverse-primary btn-sm">{{__("Add Item")}}</a>
              @endif
            </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="input-group input-daterange">
            <input autocomplete="off" type="date" id="min-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="From:">
            <div class="input-group-text">To</div>
            <input autocomplete="off" type="date" id="max-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="To:">
          </div>
        </div>
        <div class="table-responsive">
          <table id="item_table" class="table ">
            <thead>
              <tr>
                <th>Sr.no.</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Image</th>
                <th>HSN</th>
                <th>GST</th>
                <th>Created At</th>
                <th>Created By/Updated By</th>
                @if(Auth::user()->hasPermissionTo('item.edit') || Auth::user()->hasPermissionTo('item.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                <th>Action</th>
                @endif
                
              </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>