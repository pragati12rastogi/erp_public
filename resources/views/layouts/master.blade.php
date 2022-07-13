<!DOCTYPE html>
<html lang="en">
<head>
  <title>@yield('title') | {{env('APP_NAME')}}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <!-- CSRF Token -->
  <meta name="_token" content="{{ csrf_token() }}">
  @if(!empty($general_settings) && !empty($general_settings['favicon']) && file_exists(public_path().'/images/general/'.$general_settings['favicon']) )
    <link rel="shortcut icon" href="{{ asset('/images/general/'.$general_settings['favicon']) }}">
  @else
    <link rel="shortcut icon" href="{{ asset('/assets/images/favicon.ico') }}">
  @endif
  
    <!-- plugin css -->
    <link rel="stylesheet" href="{{asset('assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/css/vendor.bundle.base.css')}}">
    <!-- end plugin css -->
  
  
  {!! Html::style('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')!!}
  {!! Html::style('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')!!}
  {!! Html::style('plugins/datatables-responsive/css/responsive.bootstrap4.css')!!}
  {!! Html::style('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')!!}
  {!! Html::style('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.min.css')!!}    
  {!! Html::style('plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css')!!}

  {!! Html::style('/plugins/daterangepicker/daterangepicker.css')!!}
  

  <!-- Select2 -->
  {!! Html::style('/plugins/select2/css/select2.min.css')!!}
  {!! Html::style('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')!!}

  {!! Html::style('plugins/bootstrap-tagsinput-latest/src/bootstrap-tagsinput.css')!!}

  {!! Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css') !!}

  @stack('plugin-styles')

  {!! Html::style('css/clock.css') !!}
  {!! Html::style('assets/css/style.css') !!}
  <!-- common css -->
  
  <!-- end common css -->

  @stack('style')
</head>
  <body>
    <div class="container-scroller">
      
      <!-- partial:partials/_navbar.html -->
      @include('layouts.header')
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <div id="checkInOut" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Check In / Check Out</h4>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
                
              </div>
              <div class="modal-body">
                <div class="col-md-12">
                  <div class="clock">
                    <div>
                      <div class="clock-info clock-date"></div>
                      <div class="clock-info clock-day"></div>
                    </div>
                    <div class="dot"></div>
                    <div>
                      <div class="hour-hand"></div>
                      <div class="minute-hand"></div>
                      <div class="second-hand"></div>
                    </div>
                    <div>
                      <span class="clock-span clock-h3">3</span>
                      <span class="clock-span clock-h6">6</span>
                      <span class="clock-span clock-h9">9</span>
                      <span class="clock-span clock-h12">12</span>
                    </div>
                    <div class="diallines"></div>
                  </div>
                </div>
                <div class="col-md-12">
                    <div id="checkin-checkout-div-button" class="mt-5 text-center"></div>
                </div>
                <div class="col-md-12 mt-5">
                  <div id="checkin-checkout-div-list" ></div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>

          </div>
        </div>
        @include('layouts.sidebar')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            @yield('content')
            
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          @include('layouts.footer')
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
     <!-- jQuery -->
  {!! Html::script('plugins/jquery/jquery.min.js') !!}
  <!-- JQuery -->
  
  <!-- jQuery UI 1.11.4 -->
  {!! Html::script('plugins/jquery-ui/jquery-ui.min.js') !!}
  
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>

  <!-- Bootstrap 4 -->
  {!! Html::script('/plugins/bootstrap/js/bootstrap.bundle.min.js') !!}
  
  <!-- DataTables  & Plugins -->
  {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
  {!! Html::script('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') !!}
  <!-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> -->

  {!! Html::script('/plugins/datatables-buttons/js/dataTables.buttons.min.js') !!}
  {!! Html::script('/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') !!}
  {!! Html::script('/plugins/jszip/jszip.min.js') !!}
  {!! Html::script('/plugins/pdfmake/pdfmake.min.js') !!}
  {!! Html::script('/plugins/pdfmake/vfs_fonts.js') !!}
  {!! Html::script('/plugins/datatables-buttons/js/buttons.html5.min.js') !!}
  {!! Html::script('/plugins/datatables-buttons/js/buttons.print.min.js') !!}
  {!! Html::script('/plugins/datatables-buttons/js/buttons.colVis.min.js') !!}
  <!-- Select2 -->
  {!! Html::script('/plugins/select2/js/select2.full.min.js') !!}

  
  <!-- daterangepicker -->
  {!! Html::script('/plugins/moment/moment.min.js') !!}
  {!! Html::script('/plugins/daterangepicker/daterangepicker.js') !!}

  <!-- datepicker -->
  {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js') !!}
  <!-- Tempusdominus Bootstrap 4 -->
  {!! Html::script('/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') !!}
  <script src="http://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>

  {!! Html::script('plugins/bootstrap-tagsinput-latest/src/bootstrap-tagsinput.js') !!}
  <!-- base js -->
  <!-- {!! Html::script('js/app.js') !!} -->
  
  <!-- end base js -->

  <!-- plugin js -->
  @stack('plugin-scripts')
  <!-- end plugin js -->
  <!-- Plugin js for this page -->
  <script src="{{asset('assets/vendors/js/vendor.bundle.base.js')}}"></script>
  
  <script src="{{asset('assets/vendors/chart.js/Chart.min.js')}}"></script>
  <script src="{{asset('assets/js/jquery.cookie.js')}}" type="text/javascript"></script>
  <!-- End plugin js for this page -->
  
  <!-- common js -->
  {!! Html::script('assets/js/off-canvas.js') !!}
  {!! Html::script('assets/js/hoverable-collapse.js') !!}
  <!-- {!! Html::script('assets/js/misc.js') !!} -->
  {!! Html::script('assets/js/settings.js') !!}

  {!! Html::script('assets/js/todolist.js') !!}
  {!! Html::script('js/clock.js') !!}
  <!-- end common js -->
  

  <!-- jquery-validation -->
  {!! Html::script('/plugins/jquery-validation/jquery.validate.min.js') !!}
  {!! Html::script('/plugins/jquery-validation/additional-methods.min.js') !!}
  
  
    
    <script>
      
    var admin_url = '{{url("/")}}';
    $(function() {
      $.noConflict();
      
      $('.select2').select2();

      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })

      $('.daterangepicker').daterangepicker({
        format: 'L'
      });
      
      $('.datepicker').datepicker({
        format: 'dd-mm-yyyy'
      });
      checkInOut();
    });
    
    function markread(id) {
      var a = $('#countNoti').text();
      $.ajax({
        type:"Get",
        url:"{{route('unreadsinglenotification')}}",
        data:{'id':id},
        dataType:'JSON',
        success:function(response) {
          if(a > 0) {
            var b = a - 1;
            if(b > 0) {
              $('#countNoti').text(b);
              $('#' + id).css('background', 'white');
            } else {
              $('#countNoti').hide('fast');
            }
          }
        },
        error: function(error){
          console.log(error.responseText);
        }
      });
    }

    function checkInOut(){
      $.ajax({
        type:"Get",
        url:"{{route('checkinout.data')}}",
        dataType:'JSON',
        success:function(response) {
          console.log(response);
          if(response.status){
            if(response.data != null){
              $checkin = '<div class="alert alert-success">Checked In at: '+response.data.date+' '+response.data.intime+'</div>';
              $checkout = '<div class="alert alert-danger">Checked Out at: '+response.data.date+' '+response.data.outtime+'</div>';
              var str = $checkin;
              if(response.data.outtime != '' && response.data.outtime != null){
                str += $checkout;
              }else{
                $("#checkin-checkout-div-button").empty();
                var checkOutButton = '{{route("checkout")}}'
                $("#checkin-checkout-div-button").append('<form method="post" action="'+checkOutButton+'" class="pull-right">'+
                '{{csrf_field()}}'+
                '<button type="submit" class="btn btn-warning">Check Out</button>'+
                '</form>');
              }

              $("#checkin-checkout-div-list").append(str);
              
            }
          }else{

            if(response.data == ''){
              $("#checkin-checkout-div-button").empty();
              var checkInButton = '{{route("checkin")}}'
              $("#checkin-checkout-div-button").append('<form method="post" action="'+checkInButton+'" class="pull-right">'+
              '{{csrf_field()}}'+
              '<button type="submit" class="btn btn-info">Check In</button>'+
              '</form>');
            }else{
              $("#checkin-checkout-div-button").empty();
              $("#checkin-checkout-div-button").append('<p class="text-center">'+response.data+'</p>');
            }
            
          }
        }
      })
    }

  </script>
  @stack('custom-scripts')

  </body>

</html>