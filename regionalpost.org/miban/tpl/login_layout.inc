<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>login</title>
        <link href="css/bodyliq.css" rel="stylesheet" type="text/css" />
        <link href="css/main.css" rel="stylesheet" type="text/css" />
        <link href="css/login.css" rel="stylesheet" type="text/css" />
        <link href="css/elements.css" rel="stylesheet" type="text/css" />
        <link href="js/plugins/kendo/styles/kendo.common.min.css" rel="stylesheet" type="text/css" />
        <link href="js/plugins/kendo/styles/kendo.flat2.css" rel="stylesheet" type="text/css" />
        <script  src="js/jquery-1.10.1.min.js"></script>
        <script  src="js/jquery.watermark.min.js"></script>
    </head>
    <body class="AllCont"  onload="document.login_form.usr_login.focus();">
        <div id="container">
            <div id="header">
                <div class="clear"></div>
            </div>
            <div id="content">
                <div id="page-wrapper">
                    {{if message}}
                    <div id="message-wrapper">&nbsp;{{message}}</div>
                    {{end if message}}
                    
                    <div class="clear"></div>
                </div>
            </div>
            {{contents}}
            <div class="push"></div>
        </div>
        <div class="footer" id="footer"> 
            {{footer}}
        </div>
    </body>
</html>