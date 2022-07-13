$(function() {
    
    $(".transaction_type").on('change',function(){
      var value = this.value;
      var id = $(this).attr('data-type');
      if(value == 'cheque'){
        $("#cheque_div_"+id).show();
        $("#online_div_"+id).hide();
      }else if(value == 'online'){
        $("#cheque_div_"+id).hide();
        $("#online_div_"+id).show();
      }else{
        $("#cheque_div_"+id).hide();
        $("#online_div_"+id).hide();
      }
    })
  
      
      jQuery('#payment_form').validate({ // initialize the plugin
          rules: {
              transaction_type:{
                required:true,
              },
              amount:{
                required:true,
              },
              transaction_id:{
                required:true,
              },
              cheque_no:{
                required:true,
              },
              bank_name:{
                required:true
              },
              ifsc:{
                required:true
              },
              account_name:{
                required:true
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
      });
  });
  
  $(function() {
    $("#item_table").DataTable({
        "lengthChange": false
    });
  
    jQuery('#adminpurchase_form').validate({ // initialize the plugin
        rules: {
            vendor_id:{
                required:true,
            },
            'prod':{
                required:true,
            }
  
        },
        messages:{
            prod: "Add products for distribution"
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
    });
  });
  
  
  
  $(".inc_dec_btn").on('click',function(){
    var $button = $(this);
    var $input = $button.parent().find("input");
    var oldValue = $input.val();
    var id = $input.attr('id');
    var split_id = id.split('_');
    var row = split_id[split_id.length-1];
  
    var maxValue = $button.parent().find("input").attr('max');
  
    if ($button.text() == "+") {
        if(oldValue < maxValue){
            var newVal = parseFloat(oldValue) + 1;
        }else{
            newVal = maxValue;
        }
        $("#checkbox_"+row).attr('checked',true);
    } else {
        // Don't allow decrementing below zero
        if (oldValue > 1) {
            var newVal = parseFloat(oldValue) - 1;
            $("#checkbox_"+row).attr('checked',true);
        } else {
            newVal = 0;
            $("#checkbox_"+row).attr('checked',false);
        }
    }
  
    $button.parent().find("input").val(newVal);
    calculate_all_select();
  })
  
  
  $(".multiple_item_select").on('click',function(){
    calculate_all_select();
  })
  
  
  
  function calculate_all_select(){
    var prod_price = 0;
    var prod_qty = 0;
    var prod_gst = 0;
    var final_total = 0;
    $(".multiple_item_select:checkbox:checked").each(function () {
        
        var checkbox_id = $(this).val();
  
        var price = $('#modal_prod_price_'+checkbox_id).val();
        var max_qty = $('#modal_prod_qty_'+checkbox_id).val();
        var qty = $('#item_prod_'+checkbox_id).val();
        var gst = $('#modal_prod_gst_'+checkbox_id).val();
        
        
        if(parseInt(max_qty) < parseInt(qty)){
            $("#qty_err_"+checkbox_id).text('Maximum qty is :'+max_qty);
        } else{
            $("#qty_err_"+checkbox_id).text('');
            
            var total_price=  (parseFloat(price) * parseInt(qty));
            var calculate_gst = parseFloat(total_price) * parseFloat(gst/100);

            prod_gst += parseFloat(calculate_gst);
            prod_qty += parseInt(qty);
            prod_price += parseFloat(total_price);
            final_total += parseFloat(total_price) + parseFloat(calculate_gst);
        }
  
    });
  
    $("#modal_total_price").text(prod_price.toFixed(2));
    $("#modal_total_quantity").text(prod_qty);
    $("#modal_total_gst").text(prod_gst);
    $("#modal_grand_total").text(final_total);
    
  }
  
  function modal_submit(){
  
    $("#prod-append-div").empty();
  
    var grand_total =0;
    var tr ='';
    $(".multiple_item_select:checkbox:checked").each(function () {
        
        
        var checkbox_id = $(this).val();
  
        var price = $('#modal_prod_price_'+checkbox_id).val();
        var max_qty = $('#modal_prod_qty_'+checkbox_id).val();
        var qty = $('#item_prod_'+checkbox_id).val();
        var gst = $('#modal_prod_gst_'+checkbox_id).val();
        
        
        if(parseInt(max_qty) < parseInt(qty)){
            $("#qty_err_"+checkbox_id).text('Maximum qty is :'+max_qty);
            
        }
        else{
            $("#qty_err_"+checkbox_id).text('');
            
            var total_price = (parseFloat(price) * parseInt(qty));
            var calculate_gst = parseFloat(total_price) * parseFloat(gst/100);
            var final_price = parseFloat(total_price) + parseFloat(calculate_gst);
            grand_total += parseFloat(final_price);
            tr += '<tr><td> <input type="hidden" value="'+qty+'" name="item['+checkbox_id+']">'+
                $('#modal_prod_cat_'+checkbox_id).val()+'</td>'+
                '<td>'+$('#modal_prod_name_'+checkbox_id).val()+'</td>'+
                '<td> Rs. '+price+'</td>'+
                '<td> '+qty+'</td>'+
                '<td> Rs. '+total_price.toFixed(2)+'</td>'+
                '<td> Rs. '+calculate_gst.toFixed(2)+'</td>'+
                '<td> Rs. '+final_price.toFixed(2)+' </td>'+
            '</tr>' ;
        }
        
    });
  
    tr += '<tr><td colspan="6"><b>Grand Total:</b></td><td> Rs. '+grand_total.toFixed(2)+'</td></tr>';
  
    var table = '<table class="table">'+
        '<thead>'+
            '<tr>'+
                '<th>Category</th>'+
                '<th>Item</th>'+
                '<th>Price</th>'+
                '<th>Quantity</th>'+
                '<th>Total Price</th>'+
                '<th>GST</th>'+
                '<th>Final Price</th>'+
            '</tr>'+
        '</thead>'+
        '<tbody>'+
            tr
        '</tbody>'+
    '</table>';
    
    $("#prod-append-div").append(table);
    $("#products_model").modal('hide');
    $("#prod").val(1);
  }
  
  $("#modal-submit").on("click",function(){
    modal_submit();
  })
  
  function qty_change_func(i){
    var id = $(i).attr('id');
    var value = i.value;
  
    var split_id = id.split('_');
    var row = split_id[split_id.length-1];
  
    if(value > 0){
        $("#checkbox_"+row).attr('checked',true);
    }else{
        $("#checkbox_"+row).attr('checked',false);
    }
    calculate_all_select();
  }