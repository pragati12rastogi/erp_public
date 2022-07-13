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

  jQuery('#admindistributer_form').validate({ // initialize the plugin
      rules: {
          role_id:{
              required:true,
          },
          user_id:{
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

  $("#role_id").on('change',function(){
    var value= this.value;

    if(value != ''){
        $.ajax({
            type:'GET',
            dataType: 'json',
            url: admin_url+'/get/users/by/role',
            data: {'role_id':value},
            success: function(result){
                $("#user_id").empty();
                var str = '<option value="">Select User</option>';

                $.each(result.data,function(ind,value){
                    str += '<option value="'+value.id+'">'+value.name+'</option>';
                })

                $("#user_id").append(str);
            },
            error: function(error){
                alert(error.responseText);
            }
        })
    }
  })

  $(".inc_dec_btn").on('click',function(){
    var $button = $(this);
    var $input = $button.parent().find("input");
    var oldValue = $input.val();
    var id = $input.attr('id');
    var split_id = id.split('_');
    var row = split_id[split_id.length-1];

    if ($button.text() == "+") {
        var newVal = parseFloat(oldValue) + 1;
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

  $("#modal-submit").on("click",function(){
    modal_submit();
  })
  
});


function calculate_all_select(){
  var prod_price = 0;
  var prod_charge = 0;
  var prod_qty = 0;
  var prod_discount = 0;
  $(".multiple_item_select:checkbox:checked").each(function () {
      
      var checkbox_id = $(this).val();

      var price = $('#modal_prod_price_'+checkbox_id).val();
      var max_qty = $('#modal_prod_qty_'+checkbox_id).val();
      var qty = $('#item_prod_'+checkbox_id).val();
      var charge = $('#modal_prod_charge_'+checkbox_id).val();
      var discount = $('#modal_prod_discount_'+checkbox_id).val();
      
      var total_price=  (parseFloat(price) * parseInt(qty));
      var total_charge=  (parseFloat(charge) * parseInt(qty));
      var after_discount = parseFloat(total_price + total_charge) - parseFloat(discount);
      
      if(parseInt(max_qty) < parseInt(qty)){
          $("#qty_err_"+checkbox_id).text('Maximum qty is :'+max_qty);
      }else if(discount<0){
        $("#dicount_err_"+checkbox_id).text('Minimum value can be of 0');
      }else if(after_discount < 0){
          $("#dicount_err_"+checkbox_id).text('Maximum discount can be of :'+ parseFloat(total_price + total_charge).toFixed(2));
      }else{
          $("#qty_err_"+checkbox_id).text('');
          $("#dicount_err_"+checkbox_id).text('');
      }
      prod_price += total_price;
      prod_charge += total_charge;
      prod_qty += parseInt(qty);
      prod_discount += parseFloat(discount);

  });

  var total_before_discount = (parseFloat(prod_price) + parseFloat(prod_charge));
  var final_total = (parseFloat(total_before_discount) - parseFloat(prod_discount));
  $("#modal_total_price").text(prod_price.toFixed(2));
  $("#modal_total_charge").text(prod_charge.toFixed(2));
  $("#modal_sub_total").text(total_before_discount.toFixed(2));
  $("#modal_total_discount").text(prod_discount.toFixed(2));
  $("#modal_final_price").text(final_total.toFixed(2));
  $("#modal_total_quantity").text(prod_qty);
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
      var charge = $('#modal_prod_charge_'+checkbox_id).val();
      var discount = $('#modal_prod_discount_'+checkbox_id).val();

      var total_price = (parseFloat(price) * parseInt(qty));
      var prod_charge = (parseFloat(charge) * parseInt(qty));
      var before_discount = parseFloat(total_price) + parseFloat(prod_charge);
      var check_discount = parseFloat(before_discount) - parseFloat(discount);

      if(parseInt(max_qty) < parseInt(qty) ){
          $("#qty_err_"+checkbox_id).text('Maximum qty is :'+max_qty);
      
      }else if(check_discount < 0) {
          $("#dicount_err_"+checkbox_id).text('Maximum discount can be of :'+before_discount);
      }else{
          $("#qty_err_"+checkbox_id).text('');
          $("#dicount_err_"+checkbox_id).text('');
          
           
          var final_row_price = parseFloat(before_discount) - parseFloat(discount);
          grand_total += final_row_price;

          tr += '<tr><td> <input type="hidden" value="'+qty+'" name="item['+checkbox_id+']">'+
              '<input type="hidden" value="'+discount+'" name="discount['+checkbox_id+']">'+
              $('#modal_prod_cat_'+checkbox_id).val()+'</td>'+
              '<td>'+$('#modal_prod_name_'+checkbox_id).val()+'</td>'+
              '<td> Rs. '+price+'</td>'+
              '<td> Rs. '+charge+'</td>'+
              '<td> Rs. '+discount+'</td>'+
              '<td> '+qty+'</td>'+
              '<td> Rs. '+final_row_price.toFixed(2)+'</td>'+
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
              '<th>Charge</th>'+
              '<th>Discount</th>'+
              '<th>Quantity</th>'+
              '<th>Total</th>'+
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

function get_charge_of_item(){
  var user_id = $("#user_id").val();
  // removing qty from model after user change
  $("#products_model").find("input[type=number]")
    .val(0)
    .end();
  $("#prod").val('');
  $("#modal_total_price").text('');
  $("#modal_total_charge").text('');
  $("#modal_total_quantity").text('');
  $("#modal_final_price").text('');
  $("#modal_total_discount").text('');
  $("#prod-append-div").empty();

  $.ajax({
    url:admin_url+'/get/item/charge/api',
    dataType:'JSON',
    data:{'user_id':user_id},
    success:function(response){

      $.each(response.data,function(ind,value){
        if(value.charges == null){
          $("#text_prod_charge_"+value.id).text(0);
          $("#modal_prod_charge_"+value.id).val(0);
        }else{
          $("#text_prod_charge_"+value.id).text(value.charges);
          $("#modal_prod_charge_"+value.id).val(value.charges);
        }
      })

    }
  })
}

function copyToClipboard(text,ele,order_id) {
  var amount = $("#amount_"+order_id).val();
  var sampleTextarea = document.createElement("textarea");
  document.body.appendChild(sampleTextarea);
  sampleTextarea.value = text+'?amount='+amount; //save main text in it
  sampleTextarea.select(); //select textarea contenrs
  document.execCommand("copy");
  document.body.removeChild(sampleTextarea);
  $(ele).text('Copied');
  setTimeout(() => {
    $(ele).text('Copy payment Url');
  }, 10000);
}