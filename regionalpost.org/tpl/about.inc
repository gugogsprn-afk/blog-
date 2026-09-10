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
            <div class="box-top">
                <h1 class="slash big content-title">{{item.name}}</h1>
                {{if item.ainfo}}
                <p>{{item.ainfo}}</p>
                {{end if item.ainfo}}
            </div>
        </div>
        <div class="text-content">{{item.content}}</div>

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
        {{end if social}} {{if media_kit}}
        <a
            href="{{media_kit}}"
            target="_blank"
            title="Media Kit"
        >
            Media Kit
        </a>
        {{end if media_kit}}
    </article>
</div>
