{{if articles}}
<div class="flex-wrap">
    {{for articles as ac}}
    <div class="flex-box flex{{ac.mult}}">
        <div class="news-item" data-id="{{ac.id}}">
            <a href="{{ac.url}}" title="{{ac.name}}">
                <div class="image">
                    <img src="{{ac.filename}}" alt="{{ac.name}}" title="{{ac.name}}" />
                </div>
                <div class="acl">
                    <b>{{ac.ainfo}}</b>
                    <div class="h1">{{ac.name}}</div>
                    <div class="h2">{{ac.binfo}}</div>
                    <p>{{ac.descr}}</p>
                    <span class="date">{{ac.date_object}}</span>
                </div>
            </a>
        </div>
    </div>
    {{ac.ads}}
    {{ac.clear}}
    {{end for articles}}
    <span class="clear"></span>
</div>
{{end if articles}}
<script>
    $('.acl p').each(function() {
        if ($(this).outerHeight()> 145) {
            $(this).dotdotdot({
                height: 145
            });
        }

    });
</script>