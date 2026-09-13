var _dict = tryParseJson('{{worlist}}'); // {{{for worlist as dic(1,',')}}{{dic.id}}: "{{dic.value}}"{{end for worlist}}};	
function _translate(verb){
    console.warn('called translate with verb ' + verb);
    return _dict[verb];
}

function imgerror(source,target){
    source.src = "imgs/noimage.jpg";
    source.onerror = "";
    $('#'+ target).attr('src',source.src).remove();
    return true;
}

function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}

function TimerFormatStr(secs) {
    var sec_num = parseInt(secs, 10); 
    if (sec_num<=0)
        return '00:00:00';
    
    var days   = Math.floor(sec_num / 86400);
    var hours   = Math.floor((sec_num - (days * 86400)) / 3600);
    var minutes = Math.floor((sec_num - (days * 86400 + hours * 3600)) / 60);
    // var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    // if (seconds < 10) {seconds = "0"+seconds;}
    // var time    = days + ' {{dict.DAY}} '  + hours+' {{dict.HOUR}} '+minutes + ' {{dict.MINUTE}}';
    var time    = days + ' {{dict.DAY}} '  + hours+':'+minutes;
    return time;
}

$(document).ready(function () {
    
    var validator = new pageValidator();
    validator.bind();
   
   $('.toggle-dropdown').click(function(e) {
        e.preventDefault();
        $(this).closest('li').find('.submenu').toggleClass('visible');
    });
    
   
    $('textarea.maxlength').bind("change paste keyup", function(e) {
        var limit = parseInt($(this).attr('maxlength'));  
        var text = $(this).val();  
        var chars = text.length;  
        var new_text = text;
        if (chars>=limit) {
            e.preventDefault();
            e.stopPropagation();   
        }
        if(chars > limit){  
            new_text = text.substr(0, limit);  
            $(this).val(new_text);  
            chars = limit;
        }
    });  
    
   
});