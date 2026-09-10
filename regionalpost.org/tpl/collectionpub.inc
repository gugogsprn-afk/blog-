<div class="content-left">
    <div class="section page">
        <div class="block-header">
            <h1 class="slash big">{{item.name}}</h1>
        </div>
        <div class="publicat-list">
            {{for items as ac}}
            <div>
                <div
                    class="news-item"
                    data-id="{{ac.id}}"
                >
                    <a
                        href="{{ac.filename}}"
                        title="{{ac.name}}"
                        target="_blank"
                    >
                        <div>
                            <img
                                src="{{ac.thumbname}}"
                                alt="{{ac.name}}"
                                title="{{ac.name}}"
                            />
                            <span class="clear"></span>
                        </div>
                        <div class="acl">
                            <b>{{ac.ainfo}}</b>
                            <div class="h1">{{ac.name}}</div>
                            <div class="article-intro">{{ac.binfo}}</div>
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
<script>

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
