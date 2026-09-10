<div class="content-left">
    <article class="section">
        <div class="withtool">
            <div class="toolbox">
                <a href="{{curview}}?view=list#tolist" rel="nofollow" class="btnview btn-listview">&nbsp;</a> 
                <a href="{{curview}}?view=grid#tolist" rel="nofollow" class="btnview btn-gridview">&nbsp;</a> 
            </div>
            <h1 id="tolist" >{{item.name}}</h1>
        </div>
        <div class="text-content">
            {{item.content}}<span class="clear"></span>
        </div>
        <div class="hr" ></div>
        
        <div class="news-box section-tb {{displaystyle}}-view annuar">
            {{for items as n}}
            <div>
                <div class="news-item staff-item">
                    <div class="image">
                        <a href="{{n.url}}" title="{{n.name}}" ><img src="{{n.filename}}" alt="{{n.name}}" title="{{n.name}}" /></a>
                    </div>
                    <a href="{{n.url}}" title="{{n.name}}">
                        <span class="b">{{n.ainfo}}</span>
                        <span class="p">{{n.name}}</span>
                    </a>
                    <div class="foot">
                        <a href="{{n.url}}" title="{{n.name}}" class="r">{{dict.READMORE}}</a>
                        <span class="clear"></span>
                    </div>
                </div>
            </div>
            {{end for items}}
            <span class="clear"></span>
        </div>
        {{if pagelist}}
        <div class="pagelist">
            <div>{{pagelist}}</div>
        </div>
        {{end if pagelist}}
    </article>
</div>
<script >
    $(".text-content").find('img').each(function( index,elem ) {
        
        $(elem).wrap('<a class="galery_album" href="'+ $(elem).attr('src') +'" rel="galalbum"></a>');
    });
    
    
    $("a.galery_album").fancybox();
    
</script>