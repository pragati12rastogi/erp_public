$("#prod_quantity, #prod_price, #total_price").on('keyup',function(){
    calculate_product();
})

$("#prod_quantity_upd, #prod_price_upd, #total_price_upd").on('keyup',function(){
    calculate_product_upd();
    calculate_final_price_upd();
    user_percent_calculation_upd();
})

function calculate_product(){
    var prod_quantity = $("#prod_quantity").val();
    var prod_price = $("#prod_price").val();

    var calculate_gst = (prod_price * gst_percent)/100;
    prod_price = parseFloat(prod_price) + parseFloat(calculate_gst);

    var calc_total = parseInt(prod_quantity)*parseFloat(prod_price);
    
    $("#total_price").val(calc_total.toFixed(2));

    $('#total_price_span').empty();
    $('#total_price_span').text("Note: gst of "+gst_percent+"% is added.")
}

function calculate_product_upd(){
    var prod_quantity = $("#prod_quantity_upd").val();
    var prod_price = $("#prod_price_upd").val();

    var calculate_gst = (prod_price * gst_percent_upd)/100;
    prod_price = parseFloat(prod_price) + parseFloat(calculate_gst);

    var calc_total = parseInt(prod_quantity)*parseFloat(prod_price);
    
    $("#total_price_upd").val(calc_total.toFixed(2));

    $('#total_price_span_upd').empty();
    $('#total_price_span_upd').text("Note: gst of "+gst_percent_upd+"% is added.")
}

$("#per_freight_price").change(function(){
    calculate_final_price();
    user_percent_calculation();
    
})

$("#per_freight_price_upd").change(function(){
    calculate_final_price_upd();
    user_percent_calculation_upd();
    
})
function calculate_final_price_upd (){
    var per_freight_price = $("#per_freight_price_upd").val();
    var prod_quantity = $("#prod_quantity_upd").val();
    var total_price = $("#total_price_upd").val();
    
    var calc_total = (parseInt(per_freight_price)*parseInt(prod_quantity))+parseInt(total_price);
    
    var final_price = $("#final_price_upd").val(calc_total.toFixed(2));

}

function calculate_final_price (){
    var per_freight_price = $("#per_freight_price").val();
    var prod_quantity = $("#prod_quantity").val();
    var total_price = $("#total_price").val();
    
    var calc_total = (parseInt(per_freight_price)*parseInt(prod_quantity))+parseInt(total_price);
    
    var final_price = $("#final_price").val(calc_total.toFixed(2));

}

$("#user_percent").change(function(){
    user_percent_calculation()
})

$("#user_percent_upd").change(function(){
    user_percent_calculation_upd()
})

function user_percent_calculation(){
    var prod_price = $("#prod_price").val();
    var calculate_gst = (prod_price * gst_percent)/100;
    prod_price = parseFloat(prod_price) + parseFloat(calculate_gst);

    var per_freight_price = $("#per_freight_price").val();
    var user_percent = $("#user_percent").val();

    prod_price = parseFloat(prod_price) + parseFloat(per_freight_price);
    var get_percent = parseFloat(prod_price)*(user_percent/100);

    var user_price = (parseFloat(prod_price)+parseFloat(get_percent)).toFixed(2);
    $("#price_for_user").val(user_price);
}

function user_percent_calculation_upd(){
    var prod_price = $("#prod_price_upd").val();
    var calculate_gst = (prod_price * gst_percent_upd)/100;
    prod_price = parseFloat(prod_price) + parseFloat(calculate_gst);

    var per_freight_price = $("#per_freight_price_upd").val();
    var user_percent = $("#user_percent_upd").val();

    prod_price = parseFloat(prod_price) + parseFloat(per_freight_price);
    var get_percent = parseFloat(prod_price)*(user_percent/100);

    var user_price = (parseFloat(prod_price)+parseFloat(get_percent)).toFixed(2);
    $("#price_for_user_upd").val(user_price);
}