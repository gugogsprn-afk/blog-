{{if standalone}}
<!DOCTYPE html>
<html lang="" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>{{current.title}}</title>
        <link href="js/plugins/jqueryui/flat/jquery-ui.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="plugins/elfinder/css/elfinder.min.css">
        <link rel="stylesheet" type="text/css" href="plugins/elfinder/css/theme.css">
        
        <script src="js/jquery-3.0.0.min.js"></script>
        <script src="js/jquery-migrate-3.3.2.min.js"></script>
        <script src="js/plugins/jqueryui/jquery-ui.min.js"></script>
        
        <script  src="plugins/elfinder/js/elfinder.min.js"></script>
    </head>
    <body>
{{else standalone}}
<div class="page-title">
    <img src="imgs/genericapp.png" />File manager <span class="last">{{current.title}}</span>
    <div class="page-toolbar">
    </div>
</div>
<div id="page-content-wrapper">
{{end if standalone}}

<div id="filebrowser"></div>

<script >
    {{if standalone}}
    function getUrlParam(paramName) {
        var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
        var match = window.location.search.match(reParam) ;

        return (match && match.length > 1) ? match[1] : '' ;
    }
    {{end if standalone}}
    
    $(document).ready(function() {
        {{if standalone}}
        var funcNum = getUrlParam('CKEditorFuncNum');
        {{end if standalone}}
     var elf = $('#filebrowser').elfinder({
					url : 'index.php?show=filebrowser&response=block&act=connect', //plugins/elfinder/php/connector.php 
					lang: 'en',             
					resizable:true,
                                        {{if standalone}}
                                        getFileCallback : function(file) {
                                            var url =file.url.substring(file.url.indexOf('/../')+4);
                                            window.opener.CKEDITOR.tools.callFunction(funcNum,url);
                                            window.close();
                                        },
                                        {{end if standalone}}
                                        height: {{if standalone}} 500 {{else standalone}} 700 {{end if standalone}}
				}).elfinder('instance');
            
    });
</script>
{{if standalone}}
</body>
</html>
{{else standalone}}
</div></div>
{{end if standalone}}