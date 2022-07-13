@extends('layouts.master')
@push('style')

<style>
    input#prod_model_file {
        display: inline-block;
        width: 100%;
        padding: 100px 0 0 0;
        height: 30px;
        overflow: hidden;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        background: url('{{asset("images/698394.png")}}') center center no-repeat #e4e4e4;
        border-radius: 20px;
        background-size: 60px 60px;
    }
    .prod_upload_files{
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        background:#e4e4e4;
        border-radius: 5px;
    }
</style>
@endpush
@push('custom-scripts')
    @include('dashboard_js')

@endpush
@section('content')
<div class="page-header">
    <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
        <i class="mdi mdi-home"></i>
    </span> Dashboard
    </h3>
    
</div>
<div class="row">
    @foreach($announcement as $ain =>$adetail)
    <div class="col-md-12 stretch-card grid-margin">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-11">
                        <h4>Announcement!</h4>
                        <small>Date posted: {{date('d-m-Y H:i:s',strtotime($adetail['created_at']))}}</small>
                    </div>
                    <div class="col-md-1 m-auto text-center">
                        <a href="{{url('dismiss/announcement/'.$adetail['id'])}}" class="btn-close"></a>
                    </div>
                </div>
                
            </div>
            <div class="card-body">
                <h4><b>{{$adetail['name']}}</b></h4>
                {!!$adetail['message']!!}
            </div>
        </div>
    </div>
    @endforeach
    @if(Auth::user()->hasPermissionTo('category.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
            <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
            <h4 class="font-weight-normal mb-3">Total Categories <i class="mdi mdi-arrange-send-to-back mdi-24px float-right"></i>
            </h4>
            <h2 class="mb-3">{{$total_category}}</h2>
            
            </div>
        </div>
    </div>
    @endif

    @if(Auth::user()->hasPermissionTo('item.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
            <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
            <h4 class="font-weight-normal mb-3">Total Items <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
            </h4>
            <h2 class="mb-3">{{$total_items}}</h2>
            </div>
        </div>
    </div>
    @endif

    @if(Auth::user()->hasPermissionTo('vendors.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
            <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
            <h4 class="font-weight-normal mb-3">Total Vendors <i class="mdi mdi-diamond mdi-24px float-right"></i>
            </h4>
            <h2 class="mb-3">{{$total_vendor}}</h2>
            </div>
        </div>
    </div>
    @endif
    @if(Auth::user()->hasPermissionTo('users.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-gradient-warning card-img-holder text-white">
            <div class="card-body">
            <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
            <h4 class="font-weight-normal mb-3">Total Users <i class="mdi mdi-account mdi-24px float-right"></i>
            </h4>
            <h2 class="mb-3">{{$total_user}}</h2>
            </div>
        </div>
    </div>
    @endif
    @if(Auth::user()->hasPermissionTo('hsn.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-black card-img-holder text-white">
            <div class="card-body">
            <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
            <h4 class="font-weight-normal mb-3">Total HSN <i class="mdi mdi-vector-polyline mdi-24px float-right"></i>
            </h4>
            <h2 class="mb-3">{{$total_hsn}}</h2>
            </div>
        </div>
    </div>
    @endif
    @if(Auth::user()->hasPermissionTo('users-stock.list') )
    <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-orange-gradient card-img-holder text-white">
            <div class="card-body">
            <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
            <h4 class="font-weight-normal mb-3">Total Users Stock Item <i class="mdi mdi-vector-polyline mdi-24px float-right"></i>
            </h4>
            <h2 class="mb-3">{{$total_user_items}}</h2>
            </div>
        </div>
    </div>
    @endif
    @if(Auth::user()->hasPermissionTo('expenses.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <div class="col-md-3 stretch-card grid-margin">
        <div class="card bg-gradient-primary card-img-holder text-white">
            <div class="card-body">
            <img src="{{asset('assets/images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
            <h4 class="font-weight-normal mb-3">Total Expense <i class="mdi mdi-airballoon mdi-24px float-right"></i>
            </h4>
            <h2 class="mb-3">{{$expense}}</h2>
            </div>
        </div>
    </div>
    @endif
    @if(Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <div class="col-md-6 stretch-card grid-margin">
        <div class="card  card-img-holder text-white">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6">
                        <img src="{{asset('images/dashboard_img.jpg')}}" class="" alt="circle-image" style="height: 177px;width: 100%;"/>
                    </div>
                    <div class="col-md-6">
                        <img src="{{asset('images/dashboard_img2.jpg')}}" class="" alt="circle-image" style="height: 177px;width: 100%;"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<div class="row">
    @if(Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) || Auth::user()->hasPermissionTo('mytasks.index') )
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
            <h4 class="card-title float-left">My Tasks</h4>
            <hr>
                <div class="table-responsive">
                    <table id="task_table" style="width:100%" class="table-sm table table-bordered table-responsive table-striped">
                        <thead>
                            <tr>
                                <th>Sr.no.</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>Due Date</th>
                                
                                <th>Priority</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $status_array = ['In Progress','Not Started','Testing','Awaiting Feedback','Complete'];
                            @endphp
                            @foreach($tasks as $key => $h)
                                @php
                                    $assigned_name = App\Models\User::whereIn('id',$h->assigned_to)->select(DB::raw('GROUP_CONCAT(name) as name'))->first()->name;
                                @endphp 
                            <tr>
                                <td>{{$key+1}}</td>
                                <td><a onclick='view_modal("{{$h->id}}")' href="javascript:void(0);">{{$h->name}}</a></td>
                                <td>
                                    <select onchange="updateTaskStatus(this,'{{$h->id}}')" class="form-control select2">
                                        @foreach($status_array as $sind => $st)
                                        <option value="{{$st}}" {{ ($h->status==$st) ? 'selected' : '' }}>{{$st}}</option>
                                        @endforeach
                                    </select> 
                                </td>
                                
                                <td>{{date('d-m-Y',strtotime($h->start_at))}}</td>
                                <td>
                                    
                                    @if(!empty($h->end_at))
                                        @if(strtotime($h->end_at) < strtotime(date('Y-m-d')))
                                            <span class="text-danger">{{date('d-m-Y',strtotime($h->end_at))}}</span>
                                        @else
                                            {{date('d-m-Y',strtotime($h->end_at))}}
                                        @endif
                                    @endif
                                </td>
                                
                                <td>{{$h->priority}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
    
</div>
<div class="row">
    
    @if(Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
            <div class="clearfix">
                <h4 class="card-title float-left">Purchase Order Statistics</h4>
                <div id="visit-sale-chart-legend" class="rounded-legend legend-horizontal legend-top-right float-right"></div>
            </div>
            <canvas id="visit-sale-chart" class="mt-4"></canvas>
            </div>
        </div>
    </div>
    @endif
    @if(Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
            <h4 class="card-title">Stock Distribution</h4>
            <canvas id="traffic-chart"></canvas>
            <div id="traffic-chart-legend" class="rounded-legend legend-horizontal legend-bottom-left pt-4"></div>
            </div>
        </div>
    </div>
    @endif
    @if(Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) || Auth::user()->hasPermissionTo('leads.index'))
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body ">
            <h4 class="card-title mb-5">Leads</h4>
            <br>
            <canvas id="barChart" style="height:250px"></canvas>
            </div>
        </div>
    </div>
    @endif
    @if(Auth::user()->hasPermissionTo('local-stock-distribution.index'))
    <div class="col-lg-6 grid-margin">
        <div class="card">
            <div class="card-body">
            <h4 class="card-title">Local Stock Distribution</h4>
            <canvas id="pieChart2" style="height:250px"></canvas>
            </div>
        </div>
    </div>
    @endif
    
</div>
@include('mytask.view')         
@endsection
