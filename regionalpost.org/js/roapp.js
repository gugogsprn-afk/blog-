var ppz = jstz.determine(); 
$.post("en/cook/tc.html", { response:'json', tz: ppz.name()},
function(data) {
     $.post("en/cook/tc.html", { response:'json', tc : '1'},function() {},'json');
},'json');