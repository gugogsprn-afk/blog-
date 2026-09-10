<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="ru" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>
        <meta name="robots" content="noindex,nofollow" />

        <link href="css/bodyliq.css" rel="stylesheet" type="text/css" />
        <link href="css/main.css?da={{rand}}" rel="stylesheet" type="text/css" />
        <link href="css/elements.css" rel="stylesheet" type="text/css" />
        <link href="js/plugins/jqueryui/flat/jquery-ui.css" rel="stylesheet" type="text/css" />
        <link href="js/plugins/kendo/styles/kendo.common.min.css" rel="stylesheet" type="text/css" />
        <link href="js/plugins/kendo/styles/kendo.flat2.css" rel="stylesheet" type="text/css" />
        <link href="plugins/elfinder/css/elfinder.min.css" rel="stylesheet" type="text/css" />
        <link href="plugins/elfinder/css/theme.css" rel="stylesheet" type="text/css" />
        {{for styles as style}}
        <link href="{{style}}?da={{rand}}" rel="stylesheet" type="text/css" />
        {{end for styles}}
        
<!--        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>-->
        <script src="js/jquery-3.0.0.min.js"></script>
        <script src="js/jquery-migrate-3.3.2.min.js"></script>
        <script src="js/plugins/jqueryui/jquery-ui.min.js"></script>
        <script src="js/plugins/kendo/js/kendo.web.min.js"></script>
        <script src="js/plugins/kendo/js/cultures/kendo.culture.en-US.min.js"></script>
        <script src="js/plugins/kendo/js/kendo.menu.ex.js" ></script>
        <script src="js/plugins/kendo/js/kendo.multiselectEx.min.js" ></script>
        <script src="js/plugins/kendo/js/kendo.translit.js" ></script>
        <script src="js/plugins/kendo/js/kendo.urlbuilder.js?v=2" ></script>
        <script src="plugins/elfinder/js/elfinder.min.js"></script>
        <script src="plugins/ckeditor/ckeditor.js" ></script>
        <script src="js/jquery.utils.min.js?da={{rand}}" ></script>
        <script src="js/app.js"></script>
        {{if firstload}}<script src="js/roapp.js?r={{rand}}"></script>{{end if firstload}}
    </head>
    <body class="AllCont">
        <div id="container">
            <div id="header">
                {{header}}
            </div>
            <div id="content">
                <div id="popup_tree"></div>
                <div id="popup_urls"></div>
                <div id="popup_products"></div>
                <div id="popupForm"></div>
                {{contents}}
                <div class="clear"></div>
            </div>
            <div class="push"></div>
        </div>
        <div class="footer" id="footer"> 
            {{footer}}
        </div> 
        {{if error}}
        <div id="info-page-wrapper" class="error">
            {{error}}
        </div>
        {{end if error}}
    </body>
</html>
