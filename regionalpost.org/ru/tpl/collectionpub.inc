<div class="content-left">
    <div class="section">
        <div>
            <h1 class="slash big">{{item.name}}</h1>
        </div>
        <div>
            {{item.content}}<span class="clear"></span>
        </div>
        <div class="news-box section-tb">
            {{for items as ac}}
            <div class="flex-box flexsmall">
                <div class="news-item" data-id="{{ac.id}}">
                    <a href="{{ac.filename}}" title="{{ac.name}}" target="_blank">
                        <div class="image">
                            <img src="{{ac.thumbname}}" alt="{{ac.name}}" title="{{ac.name}}" />
                            <span class="clear"></span>
                        </div>
                        <div class="acl">
                            <b>{{ac.ainfo}}</b>
                            <div class="h1">{{ac.name}}</div>
                            <div class="h2">{{ac.binfo}}</div>
                            <p>{{ac.descr}}</p>
                        </div>
                        <span class="clear"></span>
                    </a>
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
    </div>
</div>
<script >
    
    //console.log($(document).width());
    //if ($(document).width()>680) {
            $('.news-box p').each(function() {
		//if ($((this).closest('div')).outerHeight()>180) {
			$(this).css('overflow','hidden').dotdotdot({
			height	: 33});
		//}
            });
    //};
   
</script>