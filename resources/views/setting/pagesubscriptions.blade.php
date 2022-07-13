@extends('layouts.master')
@section('title', 'Facebook Page Subscriptions')

@push('style')

@endpush

@push('custom-scripts')
    <script>
      var dataTable;
        $(function() {
            if(dataTable){
                dataTable.destroy();
            }
            dataTable =  $('#subscription_table').DataTable({
                    "serverSide": true,
                    "aaSorting": [],
                    "responsive": true,
                    "order": [[0, "asc"]],
                    "ajax": "{{url('leads/fb_page_subscription_api')}}",
                    "columns": [
                    { "data": "subscribed_page_name" },
                    { "data": "category" },
                    {
                        data:function(data,type,full,meta){
                          
                            return '<a onclick="delete_modal('+data.fb_id+')" class="btn btn-danger btn-sm text-white"><i class=" mdi mdi-delete-forever"></i></a>' ;
                        }
                    }
                ]
                
            });
            
        });

        window.fbAsyncInit = function() {
            FB.init({
            appId      : "{{env('FACEBOOK_APP_ID')}}",
            xfbml      : true,
            version    : "{{env('FACEBOOK_GRAPH_VERSION')}}"
            });
        };
        
        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        function subscribeApp(page_id, page_access_token, full_data) {
            console.log('Subscribing page to app! ' + page_id);
            FB.api(
            '/' + page_id + '/subscribed_apps',
            'post',
            {access_token: page_access_token, subscribed_fields: ['leadgen']},
            function(response) { 
                console.log('Successfully subscribed page', response);
                if(response.success){
                    $.ajax({
                        method:'POST',
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content")
                        },
                        url:"{{url('leads/save_fb_page_subscription')}}",
                        type:'Json',
                        data:{page:full_data},
                        success:function(return_data)
                        {
                            return_data = JSON.parse(return_data);
                            if(return_data.status == "success"){
                                dataTable.draw(); 
                            }
                        }
                    });
                }
            });
        }

        // Only works after `FB.init` is called
        function myFacebookLogin() {
            FB.login(function(response){
            console.log('Successfully logged in', response);
            FB.api('/oauth/access_token','get',{grant_type:"fb_exchange_token",
            client_id: "{{env('FACEBOOK_APP_ID')}}",
            client_secret:"{{env('FACEBOOK_APP_SECRET')}}",
            fb_exchange_token:response.authResponse.accessToken},function(response){
                console.log('Successfully Created Long-lived User Access Token', response);
                save_token_globally(response.expires_in);

                FB.api('/me/accounts', function(response) {
                console.log('Successfully retrieved pages', response);
                $("#fetch_subs").empty();
                var pages = response.data;
                var ul = document.getElementById('fetch_subs');
                var str = document.createElement('div');
                str.className="row";
                for (var i = 0, len = pages.length; i < len; i++) {
                    var page = pages[i];
                    var a = document.createElement('a');
                    a.className="col-md-3 col-xs-12";
                    a.href = "#";
                    a.onclick = subscribeApp.bind(this, page.id, page.access_token, page);
                    a.innerHTML = '<div class="card card-rounded card-inverse-success"><div class="card-body p-3 text-center"><h3 class="text-success ">'+page.name+'</h3><span class="text-success">'+page.category+'</span></div></div>';
                    
                    str.appendChild(a);
                    ul.appendChild(str);
                }
                $("#subs-list").show();
            });
            });
            }, {scope: ['pages_show_list','pages_read_engagement','pages_manage_metadata','leads_retrieval','pages_manage_ads']});
        }

        function save_token_globally(seconds){
            $.ajax({
                method:'GET',
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content")
                },
                url:"{{url('fb/save_token_globally')}}",
                type:'Json',
                data:{seconds:seconds},
                success:function(return_data)
                {
                  	console.log('testing',return_data);
                    document.getElementById("fb_user_token_exp_time").innerHTML  = return_data;
                }
            });
        }

        function delete_modal(row_id){
            var delete_url = "{{url('fb-page-subscription/destroy')}}/"+row_id;
            $("#delete_form").attr('action',delete_url);
            $("#delete_modal").modal('show');
        }
    </script>
@endpush

@section('content')
<div class="row">

    <div class="col-lg-12 grid-margin ">
        
        <div class="card">
            @include('flash-msg')
        
            <div class="card-body">
                <div class="border-bottom mb-3 row">
                    <div class="col-md-8">
                        <h4 class="card-title">Facebook Page Subscriptions</h4>
                    </div>
                    <div class="col-md-4 text-end" >
                        <button onclick="myFacebookLogin()" class="btn btn-info btn-xs" title="Login with facebook to subscribe pages">Login with Facebook</button>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="">
                            Token Get Expire On: <span id="fb_user_token_exp_time">{{env('FB_LOGIN_TOKEN_EXPIRE_ON')}}</span>
                        </div>
                        <div id="subs-list" style="display:none">
                            <hr />
                            <div id="fetch_subs">
                                
                            </div>
                        </div>
                    </div>
                </div>
                    
            </div>

        </div>
        <br><br>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12" >
                        
                        <!-- if expenseid found in url -->
                        <table id="subscription_table" class="table  ">
                            <thead>
                                <tr>
                                    <th>Subscription Name</th>
                                    <th>Category</th>
                                    <th>Action </th>
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
</div>
<div id="delete_modal" class="delete-modal modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="btn-close" data-dismiss="modal"></button>
            <div class="delete-icon"></div>
        </div>
        <div class="modal-body text-center">
            <h4 class="modal-heading">Are You Sure ?</h4>
            <p>Do you really want to delete this? This process cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <form method="get" id="delete_form" action="" class="pull-right">
            {{csrf_field()}}
            
    
            <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
            <button type="submit" class="btn btn-danger">Yes</button>
            </form>
        </div>
        </div>
    </div>
</div>
@endsection