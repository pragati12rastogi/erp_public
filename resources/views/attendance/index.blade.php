@extends('layouts.master')
@section('title', 'Attendance Summary')

@push('style')
  <style>
    .tscroll {
    width: auto;
    overflow-x: scroll;
    margin-bottom: 10px;
    }

    .tscroll table td:first-child,.tscroll table td:nth-child(2)  {
    position: sticky;
    left: 0;
    background-color: #ddd;
    }
    
    .tscroll td, .tscroll th {
    border-bottom: dashed #888 1px;
    }
  </style>
@endpush

@push('custom-scripts')
    <script>
        var selected=[];
        var col_length = <?php $a_date = date('Y-m-d'); 
        $date = new DateTime($a_date); 
        $date->modify('last day of this month'); 
        echo $last_day = $date->format('d'); ?>;
        var col_data = 'd';
        var dataTable;
        function daysdifference(firstDate, secondDate){
            var startDay = new Date(firstDate);
            var endDay = new Date(secondDate);
            var millisBetween = startDay.getTime() - endDay.getTime();
            var days = millisBetween / (1000 * 3600 * 24);
            return Math.round(Math.abs(days));
        }

        function datatablefn() {
            var str = [];
            str.push({"data":"id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                } 
            });
            str.push({"data":"name"});
            for(var i = 1 ; i <= col_length ; i++) {
                str.push({
                    "data": col_data+i,
                    "class":"center light-red",
                    "render": function(data,type,full,meta) {
                        // debugger
                        var strdata = '';
                        var date_index = data.split(",").shift();
                        var intime = data.split(",")[1];
                        var outtime = data.split(",")[2];
                        var date = data.split(",")[3];

                        var h_ind = 'h'+date_index;
                        var l_ind = 'l'+date_index;
                        
                        var holiday = full[h_ind];
                        var leave = full[l_ind];

                        if(holiday != ''){
                            strdata += '<p class="text-success">'+holiday+'</p>';
                        }else if(leave != ''){
                            strdata += '<p class="text-reddit" title="'+leave+'">L</p>';
                        }else{
                            if(intime != undefined){
                                strdata += '<p class="text-info"><b>Intime :</b>'+intime+'</p>';
                            }
                            if(outtime != undefined){
                                strdata += '<p class="text-info"><b>Outtime :</b>'+outtime+'</p>';
                            }
                            if(intime == undefined && outtime == undefined ){
                                strdata = "NA"
                            }
                            
                            
                        }
                        return strdata;
                    }
                });
            }
            
            if(dataTable){
               dataTable.destroy();
            }

            dataTable = $('#admin_table').DataTable({
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "aaSorting": [],
                "pageLength": 100,
                "responsive": false,
                "ajax": {
                    "url": "{{route('attendance.api')}}",
                    "datatype": "json",
                    "data": function (data) {
                        var user_name = $('#user_name').val();
                        data.user_name = user_name;
                        var year_month = $('#month').val();
                        data.year_month = year_month;
                    },
                },        
                "columns": str
            });
        }
        
        
        $(document).ready(function() {
            datatablefn();
            $('#month').on('change', function () {
                datatablefn();
            });
            $('#user_name').on( 'change', function () {
                datatablefn();
            });
        });
    </script>
  
@endpush

@section('content')
<div class="row">
  <div class="col-lg-12">
        @include('flash-msg')
  </div>
</div>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-10">
                <h4 class="card-title">Attendance Summary</h4>
            </div>
            
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
                <label>User Name</label>
                @if(count($users) > 1)
                    <select name="user_name" class="form-control select2 selectValidation" id="user_name">
                        <option value="">Select Users</option>
                        @foreach($users as $key => $val)
                        <option value="{{$val['id']}}">{{$val['name']}}</option>
                        @endforeach
                    </select>
                @endif
                {!! $errors->first('user_name', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-md-3 form-group" >
                <label>Select Month</label>
                <input type="month" name="month" id="month" class="form-control" max="{{date('Y-m')}}" value="{{date('Y-m')}}" autocomplete="off">
            </div>
        </div>
        <div class="table-responsive tscroll">
            <table id="admin_table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <?php 
                            $a_date = date('Y-m-d');
                            $date = new DateTime($a_date);
                            $date->modify('last day of this month');
                            $last_day=$date->format('d');
                            for ($i=1; $i <= $last_day ; $i++) { 
                                echo "<th>".$i."</th>";
                            }
                        ?>
                                          
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

@endsection