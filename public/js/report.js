$(document).ready(function() {
    opacityFuns();
    withshow();
    htmlcssFunc();
});

$(document).on('input change', '.opacity-range', function() {
    var range = $(this).val();
    $(".opacity-range").val(range);
    $("#opacity_num").text(range+"%");
    $('.diff').css('opacity',range/100);
    $(this).css('background', 'linear-gradient(to right, #0C7FF2 0%, #0C7FF2 ' + $(this).val() + '%, #fff ' + $(this).val() + '%, white 100%)');
});

function opacityFuns(){
    $(".target").hide();
    $(".tool").hide();
    $('.diff').css('opacity',$('.opacity-range').val()/100);
    $("#opacity_num").text($('.opacity-range').val()+"%");
}

function withshow(){
    var checked_compare = $( "#resultcompare" ).prop( "checked" );
    var checked_diff = $( "#resultdiff" ).prop( "checked" );

    var width = "100%";
    if(checked_compare && checked_diff){
        var width = "49%";
    }
    
    if(checked_diff){
        $("#opacity-tool").show();
        $(".diff-image").show();
        $(".diff-image").css("width",width);
    }else{
        $("#opacity-tool").hide();
        $(".diff-image").hide();
    }

    if(checked_compare){
        $(".compare-image").show();
        $(".compare-image").css("width",width);
    }else{
        $(".compare-image").hide();
    }
}

function getEventChoose(){
    withshow();
}

function htmlcssFunc(){
    $('.navbar-collapse .dropdown-menu a').click(function() {
        var id_focus = $(this).attr('href');

        // sử lý add active
        $(".navbar-nav li").removeClass('active');
        $(".tool").hide();

        if ($(id_focus).hasClass("html")) {
            $("#dropdown-html").parent().addClass('active');
        }else if($(id_focus).hasClass("css")){
            $("#dropdown-css").parent().addClass('active');
        }else if ($(id_focus).hasClass("perfect_pixel")) {
            $("#dropdown-perfect_pixel").parent().addClass('active');
            $(".tool").show();
        }

        $(".target").not(id_focus).hide();
        $(id_focus).show();

    });
}