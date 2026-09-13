<div class="content-left">
    <div class="section page">
        <div class='box-top'>
            <h1 class="slash big gray">{{dict.SEARCHING}}</h1>
            <span class="h2 black">{{word}}</span>
        </div>

        {{if items}}
        <div class="news-box section-tb list-view search-results">
            {{for items as ac}}
            <div class="flex-box flexsmall">
                <div class="news-item search-item" data-id="{{ac.id}}">
                    <a href="{{ac.url}}" title="{{ac.name}}">
                        {{if ac.filename}}<img src="{{ac.filename}}" alt="{{ac.name}}" />{{end if ac.filename}}

                        <div class="acl">
                            <div class="h1">{{ac.name}}</div>
                            <div class="h2">{{ac.binfo}}</div>
                            <p>{{ac.descr}}</p>
                            <span class="date">{{ac.date_object}}</span>
                        </div>
                    </a>
                </div>
            </div>
            {{end for items}}
            <span class="clear"></span>
        </div>
        {{else items}}
        <div class="h2">{{dict.NOTHINGFOUND}}</div>
        {{end if items}}
        {{if pagelist}}
        <div class="pagelist">
            <div>{{pagelist}}</div>
        </div>
        {{end if pagelist}}
    </div>
</div>
