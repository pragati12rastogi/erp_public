
function add_more_attachment(type=""){
    $("#append-more-attachment"+type).append(
        '<div class="appended-attachment-content form-group">'+
            '<label class="control-label col-md-12" for="attachment">Attachment :</label>'+
            '<div class="input-group">'+
                '<input type="file" name="task_attachment[]" class="form-control">'+
                '<div class="input-group-prepend">'+
                    '<button type="button" class="input-group-text mdi mdi-minus text-white bg-danger remove_attachment" >'+
                    '</button>'+
                '</div>'+
            '</div>'+
        '</div>'
    );
}

$(document).on('click','.remove_attachment',function(e){
    $(this).parents(".appended-attachment-content").remove();
});

const add_checklist = (type="") =>{
    $("#append-more-checklist"+type).append(
        '<div class="appended-checklist-content form-check">'+
            '<div class="input-group">'+
                '<input type="text" name="checklist[]" class="form-control">'+
                '<div class="input-group-prepend">'+
                    '<button type="button" class="input-group-text mdi mdi-close text-white bg-danger remove_checklist" >'+
                    '</button>'+
                '</div>'+
            '</div>'+
        '</div>'
    )
}

$(document).on('click','.remove_checklist',function(e){
    $(this).parents(".appended-checklist-content").remove();
});

$(function(){
    
    $("#task_table").DataTable({
        dom: 'Blfrtip',
        buttons: [
            {
            extend:'excelHtml5',
            className: 'btn-sm mb-4',
            exportOptions: {
                columns: [ 0, 1, 2,3,4,5,6 ] 
            }
            },
            {
            extend:'pdfHtml5',
            className: 'btn-sm mb-4',
                exportOptions: {
                    columns: [ 0, 1, 2,3,4,5,6  ] //Your Column value those you want
                }
            }
        ]
    });

    
    $('form.validateForm').each(function(key, form) {
        jQuery(form).validate({
            rules: {            
                name:{
                    required:true,
                },
                start_at:{
                    required:true,
                },
                priority:{
                    required:true,
                },
                'assigned_to[]':{
                    required:true,
                },
                'checklist[]':{
                    required:true,
                }
            },
            errorPlacement: function(error,element)
            {
                if($(element).attr('type') == 'radio')
                {
                    error.insertAfter(element.parent());
                }
                else if($(element).is('select'))
                {
                    error.insertAfter(element.parent());
                }
                else{
                    error.insertAfter(element);
                }
                    
            }
        })
    });
})

function mark_checklist(checklist_id){
    $.ajax({
        type: 'GET',
        dataType:'JSON',
        url: admin_url+"/mark/task/checklist/"+checklist_id,
        success:function(result){
            if(result.status){
                console.log(result);
            }else{
                alert(result.msg);
            }
        },
        error:function(error){
            console.log(error.responseText);
        }
    });
}

function updateTaskStatus(input_value,row_id){
    $.ajax({
        type: 'GET',
        dataType:'JSON',
        url: admin_url+"/task/status/update",
        data: {'status':input_value.value,'id':row_id},
        success:function(result){
            if(result.status){

                $("#alert-success").show().find("strong").empty().append(result.msg);
                setTimeout(() => {
                    $("#alert-success").hide();
                }, 10000);
            }else{
                alert(result.msg);
            }
        },
        error:function(error){
            console.log(error.responseText);
        }
    });

}

function delete_attachment(attachment_id, selected_ele){
    $(selected_ele).parents(".upd_appended_attachment").remove();
    $.ajax({
        type: 'GET',
        dataType:'JSON',
        url: admin_url+"/task/attachment/delete/"+attachment_id,
        success:function(result){
            console.log(result);
        },
        error:function(error){
            console.log(error.responseText);
        }
    });
}