<div class="page">
    <div class="section">
        <div>
            <div class="cols">
                {{if resetform(=1)}}
                <div class="fc">
                    <div class="h2">{{page.name}}</div>
                    {{include (members/frm_passwordreset.inc)}}
                </div>
                {{if page.content}}
                <div class="lc nm">
                    {{page.content}}<span class="clear"></span>
                </div>
                {{end if page.content}}
                {{end if resetform}}

                {{if resetform(=2)}}
                <div>
                    {{page.content}}<span class="clear"></span>
                </div>
                {{end if resetform}}

                {{if resetform(=3)}}
                <div class="fc">
                    <div class="h1">{{page.name}}</div>
                    {{include (members/frm_password.inc)}}
                </div>
                {{if page.content}}
                <div class="lc nm">
                    {{page.content}}<span class="clear"></span>
                </div>
                {{end if page.content}}
                {{end if resetform}}

                <span class="clear"></span>
            </div> 
        </div>
        <span class="clear" style="height: 100px;"></span>
    </div>
</div>