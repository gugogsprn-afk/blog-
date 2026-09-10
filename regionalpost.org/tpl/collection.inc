<div class="content-left">
    <div class="section page">
        <h1 class="slash big content-title">{{item.name}}</h1>
        <div>
            {{item.content}}
            <span class="clear"></span>
        </div>
        <div class="news-box-grid">
            {{for items as ac}}
            <div
                class="news-item"
                data-id="{{ac.id}}"
            >
                <a
                    href="{{ac.url}}"
                    title="{{ac.name}}"
                >
                    <div>
                        <img
                            src="{{ac.filename}}"
                            alt="{{ac.name}}"
                            title="{{ac.name}}"
                        />
                    </div>
                    <div class="acl">
                        <b>{{ac.ainfo}}</b>
                        <div class="h1">{{ac.name}}</div>
                        <span class="article-date">{{ac.date_object}}</span>
                        <div class="article-intro">{{ac.binfo}}</div>
                        <p>{{ac.descr}}</p>
                    </div>
                    <span class="clear"></span>
                </a>
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
