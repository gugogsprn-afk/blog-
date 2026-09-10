<div class="content-left">
    <div class="section">
        <div class="h1"><a href="{{parent.url}}" title="{{parent.name}}">{{parent.name}}</a></div>
        <div class="hr" style="margin-bottom: 30px;"></div>
        
        <article class="withimage" >
            <div class="l">
                <div class="imagebox">
                    <img src="{{item.filename}}" alt="{{item.name}}" />
                </div>
                {{if item.thumbname}}<a href="{{item.thumbname}}" target="_blank" title="{{item.name}}" class="btn-cv">&nbsp;</a>{{end if item.thumbname}}
                <strong>{{item.ainfo}}</strong>
                <h1>{{item.name}}</h1>
                <pre>{{item.descr}}</pre>
            </div>
            <div>
                <div class="text-content">
                {{item.content}}
                </div>
                {{if social}}
                <div class="social r m">
                    {{if facebook_sdk}}
                    <div class="fb-like" data-href="{{fullurl}}" data-layout="button" data-action="recommend" data-show-faces="true" data-share="true"></div>
                    {{end if facebook_sdk}}
                    {{if google_plus_sdk}}
                    <div class="gp">
                        <div class="g-plus" data-action="share" data-href="{{fullurl}}" data-annotation="none"  data-height="20" data-align="left" ></div>
                        <div class="g-plusone" data-href="{{fullurl}}" data-size="medium" data-annotation="none" data-align="left" ></div>    
                    </div>
                    {{end if google_plus_sdk}}
                    {{if twitter_sdk}}
                    <a href="https://twitter.com/share" class="twitter-share-button" data-url="{{fullurl}}" data-lang="en" data-count="none">Tweet</a>
                    {{end if twitter_sdk}}
                    <span class="clear"></span>
                </div>
                {{end if social}}
            </div>
            
            <span class="clear"></span>
        </article>
    </div>
</div>
<script >
    $(".text-content").find('img').each(function( index,elem ) {
        
        $(elem).wrap('<a class="galery_album" href="'+ $(elem).attr('src') +'" rel="galalbum"></a>');
    });
    
    
    $("a.galery_album").fancybox();
    
</script>