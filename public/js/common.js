$('#category_id').on('change',function(){
    var cat_id = this.value;
    $.ajax({
        type: "GET",
        dataType: "json",
        url: admin_url+'/get/items/by/category',
        data: {
            'cat_id': cat_id
        },
        success: function (response){

            if(response.status == 'success'){

                $("#item_id").empty();
                var str = '<option value="">Select Items</option>';

                $.each(response.data,function(ind,value){
                    str += '<option value="'+value.id+'">'+value.name+'</option>';
                })

                $("#item_id").append(str);

            }

        },
        error: function(error){
            alert(error.responseText);
        }
    });
})

$("#item_id").on('change',function(){
    var item = this.value;

    if(item == ''){
        $("#item_img").attr('src',"{{asset('images/no-image.jpg')}}");
        $("#gst_id").val('').trigger('change');
        $("#hsn_id").val('').trigger('change');

    }else{
        $.ajax({
            type: "GET",
            dataType: "json",
            url: admin_url+'/get/items/details',
            data: {
                'item_id': item
            },
            success: function (response){

                if(response.status == 'success'){

                    if(response.data.item_image != ''){
                        $("#item_img").attr('src',response.data.item_image);
                    }
                    $("#gst_id").val(response.data.gst_percent_id).trigger('change');
                    $("#hsn_id").val(response.data.hsn_id).trigger('change');

                    gst_percent =  response.data.gst_percent;
                }

            },
            error: function(error){
                alert(error.responseText);
            }
        });
    }
})

  