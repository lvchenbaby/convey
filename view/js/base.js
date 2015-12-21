var loaded_scripts=[];
var global_vars={};
var _scripts;
var _callback;
function ajax_load_scripts(scripts,callback){
    _scripts=scripts;
    _callback=callback;
    setTimeout("load_scripts(_scripts,_callback)",10);
}

function load_scripts(scripts,cbk){
    var len=scripts.length;
    var count=len;
    var callback=function(){
        $("#loadingbar").width("101%").delay(200).fadeOut(400, function () {
            $(this).remove();
        });
        $('.mask').hide();
        cbk();
    };

    if(len==0){
        callback();
    }

    for(var i=0;i<len;i++){
        if(count==0)return;
        if($.inArray(scripts[i],loaded_scripts)!=-1){
            count--;
            if(count==0){
                if(typeof callback==="function"){
                    callback();
                }
            }
        }else{
            $.ajax({url:scripts[i],async:false,dataType:"script",ifModified: true, cache: true,complete:function(res) {
                loaded_scripts.push(scripts[i]);
                count--;
                if(count==0){
                    if(typeof callback==="function"){
                        callback();
                    }
                }
            }});
        }
    }
}

function loadPage(url){

    if ($("#loadingbar").length === 0) {
        $("body").append("<div id='loadingbar'></div>")
        $("#loadingbar").addClass("waiting").append($("<dt/><dd/>"));
        $("#loadingbar").width((90 + Math.random() * 10) + "%");
    }

    $('.mask').show();
    $.get(url,function(res){
        $('#page-content-area').html("");
        $(res).appendTo("#page-content-area");
    });
}