$(document).ready(function() {
    var remove_products_ids =[];
    
    if (window.File && window.FileList && window.FileReader) {

        $("#prod_model_file").on("change", function(e) {
           
            var files = e.target.files,
            filesLength = files.length;
            // $("#append_after_list").show();
            for (var i = 0; i < filesLength; i++) {
            
                var form_data = new FormData();
                var task_id = $("#upd_task_id").val();
                var f = files[i];
                form_data.append('task_id',task_id);
                form_data.append('file', f);
                
                $.ajax({
                    type:"POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content")
                    },
                    url:admin_url+'/task/upload/attachment',
                    data : form_data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success:function(result){

                        result= JSON.parse(result);

                        if(result.status){
                            var fileReader = new FileReader();
                            fileReader.fileName = f.name;
                            fileReader.type = f.type;
                            fileReader.att_id = result.att_id;
                            fileReader.att_data = result.att_data;
                            fileReader.onload = (function(e) {
                                
                                var file = e.target;
                                var split_file_type = (file.type).split('/');
                                if(split_file_type[0] == 'image'){
                                    var make_element = '<div class="col-md-2 upd_appended_attachment">'+
                                        '<div class="card prod_upload_files">'+
                                            '<div class="card-header text-small">'+
                                                '<div class="row">'+
                                                    '<div class="col-md-9">'+(file.type).toUpperCase()+'</div>'+
                                                    '<div class="col-md-3">'+
                                                        '<button type="button" class="btn-close" onclick="delete_attachment('+file.att_id+',this)" ></button>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                            '<div class="card-body text-center p-3">'+
                                                '<div class="row"><div class="col-md-12">'+
                                                '<a href="'+file.att_data.path+'" download><img src="'+file.att_data.path+'" class="img-thumbnail" title="'+file.fileName+'"></a>'+
                                                '</div></div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';
                                }else{
                                    var make_element = '<div class="col-md-2 upd_appended_attachment">'+
                                    '<div class="card prod_upload_files">'+
                                        '<div class="card-header text-small">'+
                                            '<div class="row">'+
                                                '<div class="col-md-9">'+(file.type).toUpperCase()+'</div>'+
                                                '<div class="col-md-3">'+
                                                    '<button type="button" class="btn-close" onclick="delete_attachment('+file.att_id+',this)" ></button>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="card-body text-center p-3">'+
                                            '<span class="mdi mdi-file-document-outline"></span>'+
                                            '<a href="'+file.att_data.path+'" download class="imageThumb">'+file.fileName+'</a>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                                }
                                $("#append_after_list").append(make_element);
                                
                            });
                            fileReader.readAsDataURL(f);
                        }else{
                            alert(result.msg);
                        }
                    },
                    error:function(error){
                        console.log(error.responseText);
                    }
                })
            }
            console.log(files);
        });
    } else {
        alert("Your browser doesn't support to File API")
    }

    
});

function dataURLtoFile(dataurl, filename) {
 
    var arr = dataurl.split(','),
        mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), 
        n = bstr.length, 
        u8arr = new Uint8Array(n);
        
    while(n--){
        u8arr[n] = bstr.charCodeAt(n);
    }
    
    return new File([u8arr], filename, {type:mime});
}