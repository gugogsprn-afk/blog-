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
                <div class="podcast-item" data-id="{{ac.id}}">
                    <div class="podcast__flex">
                        <div class="podcast__hero">
                            <img src="{{ac.filename}}" alt="" class="face">
                        </div>
                        <div class="acl podcast">
                            <b>{{ac.date_object}}</b>
                            <div class="h1">{{ac.name}}</div>
                            <p>{{ac.descr}}</p>
                        </div>
                    </div>
                    <div class="iframe">
                        {{ac.tags}}
                    </div>
                    <span class="clear"></span>
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
			//$(this).css('overflow','hidden').dotdotdot({
			//height	: 33});
		//}
            });
    //};
   
</script>