$(document).ready(function(){
    var tb = $("#amount").datebox('textbox');
    tb.bind('blur', function(){
        var amount = tb.val();
        var invoice_type = $("#invoice_type").val();
        var untx = $("#untax").datebox('textbox');
        var tx = $("#tax").datebox('textbox');
        if(invoice_type == 1){
            var untax = Math.round(amount/1.05);
            var tax = amount - untax;
            untx.val(untax);
            tx.val(tax);
        }else{
            untx.val(amount);
            tx.val("0");
        }
    });

    $("#invoice_type").combobox({
        onChange: function(newValue, oldValue){
            var amount = tb.val();
            var untx = $("#untax").datebox('textbox');
            var tx = $("#tax").datebox('textbox');
            if(amount != ''){
                if(newValue == 1){
                    var untax = Math.round(amount/1.05);
                    var tax = amount - untax;
                    untx.val(untax);
                    tx.val(tax);
                }else{
                    untx.val(amount);
                    tx.val("0");
                }
            }
        }
    });

    $("#submit").on('click', function(e){
        $("#countform").submit();
    });
    $("#clearform").on('click', function(e){
        //alert('clear');
        alert($("[name=_token]").val());
    });
});