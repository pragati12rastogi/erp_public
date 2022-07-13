// Custom filtering function which will search data in column three between two values
$(function() {
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var min = $("#min-date").val();
            var max = $("#max-date").val();
            var date = new Date( data[table_index] );
            console.log(min, max, date);
            if(min==''){ min= null; }else{ min= new Date(min); }
            if(max== ''){ max= null; }else{ max= new Date(max); }

            if (
                ( min === null && max === null ) ||
                ( min === null && date <= max ) ||
                ( min <= date   && max === null ) ||
                ( min <= date   && date <= max )
            ) {
                return true;
            }
            return false;
        }
    );
    
});