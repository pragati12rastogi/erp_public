<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-10">
                <h4 class="card-title">Stock Summary</h4>
            </div>
            @if(Auth::user()->hasPermissionTo('stocks.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
              
            <div class="col-md-2 text-end" >
                <a onclick='return $("#stock_add_modal").modal("show");' class="btn btn-inverse-primary btn-sm">{{__("Add Stock")}}</a>
            </div>
            @endif
        </div>
        <div class="col-md-4 mb-3">
          <div class="input-group input-daterange">
            <input autocomplete="off" type="date" id="min-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="From:">
            <div class="input-group-text">To</div>
            <input autocomplete="off" type="date" id="max-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="To:">
          </div>
        </div>
        <div class="table-responsive">
          <table id="stock_table" class="table ">
            <thead>
              <tr>
                <th>Sr.no.</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Created By/Updated By</th>
                <th>Created At</th>
                @if(Auth::user()->hasPermissionTo('stocks.edit') || Auth::user()->hasPermissionTo('stocks.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
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