var ppz = jstz.determine(); 
$.post("index.php?show=helpers&voider=tc", { response:'json', tz: ppz.name()},
function(data) {
     $.post("index.php?show=helpers&voider=tc", { response:'json', tc : '1'},function() {},'json');
},'json');