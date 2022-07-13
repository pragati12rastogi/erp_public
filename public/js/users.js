$(function() {
            
    jQuery('#user_form').validate({ // initialize the plugin
        rules: {

            role: {
                required: true,
            },
            name:{
                required:true,
            },
            email:{
                required:true,
            },
            mobile:{
                required:true,
            },
            firm_name:{
                required:true
            },
            gst_no:{
                required:true
            },
            state_id:{
                required:true
            },
            district:{
                required:true
            },
            address:{
                required:false
            },
            bank_name:{
                required:true
            },
            name_on_passbook:{
                required:true
            },
            ifsc:{
                required:true
            },
            account_no:{
                required:true
            },
            pan_no:{
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
    
    jQuery('#user_form_upd').validate({ // initialize the plugin
        rules: {

            role: {
                required: true,
            },
            name:{
                required:true,
            },
            email:{
                required:true,
            },
            mobile:{
                required:true,
            },
            firm_name:{
                required:true
            },
            gst_no:{
                required:true
            },
            state_id:{
                required:true
            },
            district:{
                required:true
            },
            area_id:{
                required:true
            },
            address:{
                required:false
            },
            bank_name:{
                required:true
            },
            name_on_passbook:{
                required:true
            },
            ifsc:{
                required:true
            },
            account_no:{
                required:true
            },
            pan_no:{
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