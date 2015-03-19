
$(document).ready(function(){



    $("#submit").click(function(){
//        var user = $("#user").val();
        var singer = $("#singer").val();
        var from = $("#from").val();
        var to = $("#to").val();
        var lifetime_flag = $("#lifetime_flag").val();

// Returns successful data submission message when the entered information is stored in database.
        var dataString = 'singer='+ singer + '&from='+ from + '&to='+ to + '&lifetime_flag='+ lifetime_flag;
        if(singer==''||from==''||to==''||lifetime_flag=='')
        {
            alert("Please Fill All Fields");
        }
        else
        {
// AJAX Code To Submit Form.
            $.ajax({
                type: "POST",
                url: "ajaxsubmit.php",
                data: dataString,
                cache: false,
                success: function(result){
                    function refresh_joogazin(){

                        $('#joogazin').html(result);
                    }
                    refresh_joogazin();
                }
            });
        }
        return false;
    });
});