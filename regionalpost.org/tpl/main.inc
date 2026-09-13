<!DOCTYPE html>
<html lang="{{curlang}}" xmlns="http://www.w3.org/1999/xhtml">

    <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <base href="{{baseaddress}}" />
        <title>{{meta.title}}</title>
        <meta name="description" content="{{meta.descr}}" />
        <meta name="keywords" content="{{meta.keys}}" />
        <meta name="robots" content="{{meta.robots}}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        {{if metalinks}}{{for metalinks as link}}<link rel="{{link.rel}}" href="{{link.href}}" />{{end for metalinks}}{{end if metalinks}}
        {{if canonical}}<link rel="canonical" href="{{canonical}}" />{{end if canonical}}
        {{if ogtags}}{{for ogtags as tag}}<meta property="{{tag.name}}" content="{{tag.value}}"/>{{end for ogtags}}{{end if ogtags}}
        <link href="css/slick.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css?v=49" rel="stylesheet" type="text/css" /><link href="js/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css" />{{for styles as style}}<link href="{{style}}" rel="stylesheet" type="text/css" />{{end for styles}}
        <script  src="js/jquery-1.10.1.min.js" ></script>
        <script  src="js/jquery.utils.min.js?r={{rand}}"></script>
        <script  src="js/fancybox/jquery.fancybox.pack.js" ></script>
        <script  src="{{curlangp}}js/app.js"></script>
        <script type="text/javascript" src="js/slick.min.js"></script>
        {{for scripts as script}}<script  src="{{script}}"></script>{{end for scripts}}
        {{if firstload}}<script  src="js/roapp.js?r={{rand}}"></script>{{end if firstload}}
    </head>
    <body>
        <div id="document-wrapper">
            <div class="hlines"></div>
            <header id="header">{{header}}</header>
            <div class='content-main'>
                {{contents}}
                <div class="banners-bottom page">
                    {{for banners.onbottom as bbot}}
                    <div  class="banner" >{{bbot.content}}<span class="clear"></span></div>
                    {{end for banners.onbottom}}
                </div>
                <span class="clear" style="height: 160px;"></span>
            </div>
            <footer>{{footer}}</footer>
            <div class="blines"><div></div></div>
        </div>
        <!-- Meta Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '230986036682642');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=230986036682642&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Meta Pixel Code -->

        {{if facebook_sdk}}
        <div id="fb-root"></div>
        <script>
            // facebook sdk
            (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
            fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        {{end if facebook_sdk}}
        {{if google_anal}}
        <script >
            // analytics
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', '{{google_anal}}', 'auto');
            ga('send', 'pageview');
        </script>
        {{end if google_anal}}
        {{if twitter_sdk}}
        <script >
            // twitter
            !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id))
            {js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}
            (document, 'script', 'twitter-wjs');
        </script>
        {{end if twitter_sdk}}

        <!-- Yandex.Metrika counter -->
        <script>
            (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
            (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

            ym(79429213, "init", {
                 clickmap:true,
                 trackLinks:true,
                 accurateTrackBounce:true
            });
         </script>
         <noscript><div><img src="https://mc.yandex.ru/watch/79429213" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika counter -->
    </body>
</html>
