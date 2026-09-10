<div class="page">
    <div class="section">
        <h1 class="tc">{{page.name}}</h1>
        {{if page.content}}
        <div>
            {{page.content}}<span class="clear"></span>
        </div>
        {{end if page.content}}
        <div class="userformwrap">
            {{formtpl}}<span class="clear"></span>
        </div>
        <span class="clear" style="height: 100px;"></span>
    </div>
</div>