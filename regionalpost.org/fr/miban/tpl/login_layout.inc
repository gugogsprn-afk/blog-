<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="{{admin_lang}}" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Regional Post Admin · Login</title>
        <meta name="robots" content="noindex,nofollow" />
        <link href="css/bodyliq.css" rel="stylesheet" type="text/css" />
        <link href="css/main.css" rel="stylesheet" type="text/css" />
        <link href="css/login.css" rel="stylesheet" type="text/css" />
        <link href="css/elements.css" rel="stylesheet" type="text/css" />
        <link href="js/plugins/kendo/styles/kendo.common.min.css" rel="stylesheet" type="text/css" />
        <link href="js/plugins/kendo/styles/kendo.flat2.css" rel="stylesheet" type="text/css" />
        <script  src="js/jquery-1.10.1.min.js"></script>
        <script  src="js/jquery.watermark.min.js"></script>
    </head>
    <body class="AllCont rp-admin rp-login admin-lang-{{admin_lang}}"  onload="document.login_form.usr_login.focus();">
        <div id="container">
            <div id="header" class="rp-login-header">
                <div class="rp-admin-top rp-login-top">
                    <div class="rp-admin-brand">
                        <span class="rp-admin-logo">
                            <span class="rp-admin-logo-mark">RP</span>
                            <span class="rp-admin-logo-text">
                                <strong>Regional Post</strong>
                                <em>Admin · {{admin_lang_label}}</em>
                            </span>
                        </span>
                    </div>
                    <nav class="rp-lang-switch" aria-label="Admin language">
                        <button type="button" class="rp-lang-current" aria-expanded="false" aria-haspopup="true">
                            <span>{{if admin_is_en}}EN{{end if admin_is_en}}{{if admin_is_ru}}RU{{end if admin_is_ru}}{{if admin_is_fr}}FR{{end if admin_is_fr}}</span>
                            <span class="rp-lang-arrow" aria-hidden="true"></span>
                        </button>
                        <div class="rp-lang-menu" role="menu">
                            <a class="rp-lang-btn{{if admin_is_en}} is-current{{end if admin_is_en}}" href="{{admin_url_en}}" title="English admin" role="menuitem">EN</a>
                            <a class="rp-lang-btn{{if admin_is_ru}} is-current{{end if admin_is_ru}}" href="{{admin_url_ru}}" title="Russian admin" role="menuitem">RU</a>
                            <a class="rp-lang-btn{{if admin_is_fr}} is-current{{end if admin_is_fr}}" href="{{admin_url_fr}}" title="French admin" role="menuitem">FR</a>
                        </div>
                    </nav>
                </div>
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
        <script>
            (function () {
                var switchEl = document.querySelector('.rp-lang-switch');
                if (!switchEl) return;
                var btn = switchEl.querySelector('.rp-lang-current');
                if (!btn) return;
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    switchEl.classList.toggle('is-open');
                    btn.setAttribute('aria-expanded', switchEl.classList.contains('is-open') ? 'true' : 'false');
                });
                document.addEventListener('click', function () {
                    switchEl.classList.remove('is-open');
                    btn.setAttribute('aria-expanded', 'false');
                });
            })();
        </script>
    </body>
</html>
