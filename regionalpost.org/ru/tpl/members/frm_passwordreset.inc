<form method="POST" action="{{curlang}}/members/passreset.html" id="frm_password" class="fields">
    <input type="hidden" name="cmd" value="1" />
    <label>{{dict.USERNAME}} <input type="text" name="usr_name" class="m-input" /></label>
    <div class="seccode-wrap">
        <label>{{dict.CAPTCHA}}</label>
        <img class="seccode-img" src="captcha/sec_code.php?da=" data-src="captcha/sec_code.php?da=" /><a class="reload-captcha" href="#" rel="nofollow">&nbsp;</a>
        <div><input type="text" class="m-input sec_text" name="seccode" value="" /></div>
    </div>
    <div class="i-block"></div>
    <input type="submit" class="flatbutton" value="{{dict.RECOVER}}"></a>
</form>
<script >
    (function(){
        var frm = $('#frm_password'),
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