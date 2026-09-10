<div class="content-left">
    <article class="section page">
        {{if item.filename}}
        <div class="image">
            <img
                src="{{item.filename}}"
                alt="{{item.name}}"
            />
        </div>
        {{end if item.filename}}
        <div class="acl">
            <b>{{item.ainfo}}</b>
            <h1>{{item.name}}</h1>
            {{if item.binfo}}
            <div class="article-intro h2">{{item.binfo}}</div>
            {{end if item.binfo}} {{if item.cinfo}}
            <div class="article-author">{{item.cinfo}}</div>
            {{end if item.cinfo}}
            <span class="article-date">{{item.date_object}}</span>
        </div>
        {{if embed}}
        <div id="embed-block">{{embed}}</div>
        {{end if embed}}
        <div class="text-content">
            {{item.content}}
            <span
                class="clear"
                style="height: 50px;"
            ></span>
        </div>

        <div id="additional">
            {{if relitems}}
            <div class="news-box section-tb">
                <h2>{{dict.VERNAGIR}}</h2>
                <div class="relitems">
                    {{for relitems as r}}
                    <div class="flex-box flexsmall">
                        <div
                            class="news-item"
                            data-id="{{r.id}}"
                        >
                            <a
                                href="{{r.url}}"
                                title="{{r.name}}"
                            >
                                <div class="image">
                                    <img
                                        src="{{r.filename}}"
                                        alt="{{r.name}}"
                                        title="{{r.name}}"
                                    />
                                    <span class="clear"></span>
                                </div>
                                <div class="acl">
                                    <b>{{r.ainfo}}</b>
                                    <div class="h1">{{r.name}}</div>
                                    <!--                                <div class="h2">{{r.binfo}}</div>-->
                                    <p>{{r.descr}}</p>
                                </div>
                                <span class="clear"></span>
                            </a>
                        </div>
                    </div>
                    {{end for relitems}}
                </div>
                <span class="clear"></span>
            </div>
            {{end if relitems}}
        </div>

        {{if social}}
        <div class="social r m">
            {{if facebook_sdk}}
            <div
                class="fb-like"
                data-href="{{fullurl}}"
                data-layout="button"
                data-action="like"
                data-show-faces="true"
                data-share="true"
            ></div>
            {{end if facebook_sdk}} {{if google_plus_sdk}}
            <div class="gp">
                <div
                    class="g-plus"
                    data-action="share"
                    data-href="{{fullurl}}"
                    data-annotation="none"
                    data-height="20"
                    data-align="left"
                ></div>
                <div
                    class="g-plusone"
                    data-href="{{fullurl}}"
                    data-size="medium"
                    data-annotation="none"
                    data-align="left"
                ></div>
            </div>
            {{end if google_plus_sdk}} {{if twitter_sdk}}
            <a
                href="https://twitter.com/share"
                class="twitter-share-button"
                data-url="{{fullurl}}"
                data-lang="en"
                data-count="none"
            >
                Tweet
            </a>
            {{end if twitter_sdk}}

            <span class="clear"></span>
        </div>
        <span class="clear"></span>
        {{end if social}}
    </article>
</div>

<script>
    $(".text-content").find('img').each(function( index,elem ) {

        $(elem).wrap('<a class="galery_album" href="'+ $(elem).attr('src') +'" rel="galalbum"></a>');
    });


    $("a.galery_album").fancybox({
        helpers: {
          overlay: {
            locked: false
          }
        }
    });
</script>
