<form method="POST" action="{{curlang}}/members/login.html" id="frm_login_simple" class="fields">
    <div class="login"><input type="text" name="email" class="m-input" placeholder="{{dict.USERNAME}}" /></div>
    <div class="pass"><input type="password" name="password" class="m-input" placeholder="{{dict.PASSWORD}}" /></div>
    <div class="ads">
        <a href="members/passreset.html" title="{{dict.RESETPASS}}">{{dict.RESETPASS}}</a>
        <!-- | <a href="members/register.html" title="{{dict.REGISTER}}">{{dict.REGISTER}}</a>-->
    </div>
    <div class="seccode-wrap {{capclass}}">
        <img class="seccode-img" src="captcha/sec_code.php?g=1&da=" data-src="captcha/sec_code.php?g=1&da=" /><a class="reload-captcha" href="#" rel="nofollow">&nbsp;</a>
        <div><input type="text" class="m-input sec_text" name="seccode" value="" /></div>
    </div>
    <div class="i-block"></div>
    <input type="submit" class="flatbutton" value="{{dict.LOGIN}}">
</form>
<span class="clear"></span>
<script >
    
    (function(){
        
        
        var frm = $('#frm_login_simple'),
            reloadCaptcha = function (e) {
                if (e) {e.preventDefault();}
                frm.find('.seccode-wrap').removeClass('hidden');
                var img = frm.find('.seccode-img');
                img.attr("src", img.data("src") + (new Date()).getTime());
                frm.find('.sec_text').val('');
            };
        frm.find('.reload-captcha').click(reloadCaptcha);
        frm.myForm({
            wrapper: frm.closest('div'),
            beforeSubmit: function() { frm.find('.i-block').html('');return true;},
            onError: function(error,responseText) { 
                reloadCaptcha();
                frm.find('.i-block').html(error.error);
                return false;
            }
        });
    })();
</script>