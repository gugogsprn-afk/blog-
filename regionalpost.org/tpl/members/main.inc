<div class="page">
    <div class="section-tb" id="member-mainbox">
        <!-- <a href="#" id="membermenutoggle"></a> -->
        <div class="member-block" id="memberblock-wrapp">
            
            <div class="aside memberaside" id="member-aside">
                <div class="padding-tb">
                    <ul class="none">
                        <li><a href="{{curlangp}}members/" class="{{active_main}}" title="{{dict.CURRITEMS}}">{{dict.CURRITEMS}}</a></li>
                        <li><a href="{{curlang}}/members/changedata.html" title="{{dict.CHANGEACCOUNTINFO}}" class="{{active_data}}">{{dict.CHANGEACCOUNTINFO}}</a></li>
                        {{if normalmember}}<li><a href="members/changepass.html" title="{{dict.CHANGEPASS}}" class="{{active_pass}}">{{dict.CHANGEPASS}}</a></li>{{end if normalmember}}
                        <li><a href="{{curlangp}}?logout=1" title="{{dict.LOGOUT}}">{{dict.LOGOUT}}</a></li>
                    </ul>
                </div>
            </div>
           <!-- <div id="memberpageoverlay" ></div> -->
            <div class="member-page">
                <div class="padding-inner">
                    <div class="h2">{{page.name}}</div>
                    {{if page.content}}
                    <div class="descr">{{page.content}}</div>
                    {{end if page.content}}
                    <div class="content">
                        {{formtpl}}<span class="clear"></span>
                    </div>
                </div>
                <span class="clear"></span>
            </div>
            <span class="clear"></span>
        </div>
    </div>
</div>
<span class="clear" style="height: 100px;"></span>
<script >
    /*
    var inDaMenu = false;
    var maside = $('#member-aside');
    var mainMenu = $('#main-menu');
    var memberblock = $('#memberblock-wrapp');
    function setMenu() {
        var width = $(window).width();
        console.log(width);
        if (width<=885 && inDaMenu===false) {
            inDaMenu = true;
            maside.remove().appendTo(mainMenu);
        } else if (width>885 && inDaMenu===true) {
            inDaMenu = false;
            maside.remove().prependTo(memberblock);
        }
    }

    $(window).resize(setMenu);
    setMenu();
    */
    
</script>