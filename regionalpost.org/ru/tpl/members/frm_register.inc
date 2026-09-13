<form method="POST" action="{{curlang}}/members/register.html" id="frm_register" class="fields">
    <label>{{dict.USERTYPE}}* 
        <select name="member[type]" class="m-input" id="member-type">
            <option value="">-- {{dict.SELECT}} --</option>
            <option value="USER">{{dict.USER}}</option>
            <option value="MEMBER">{{dict.MEMBER}}</option>
        </select>
    </label>
    <label>{{dict.USERNAME}}* <input type="text" name="member[usr_name]" class="m-input" id="member-usr_name" /></label>
    <label class="phonewrap" style="display: none;">{{dict.PHONE}}*<i>+374</i> <input type="text" name="member[phone]" class="m-input" id="member-phone" /></label>
    <label>{{dict.PASSWORD}}* <input type="password" name="member[passa]" class="m-input kp" /></label>
    <label>{{dict.PASSWORDREP}}* <input type="password" name="member[passb]" class="m-input kp" /></label>
    <div class="seccode-wrap">
        <label>{{dict.CAPTCHA}}* </label>
        <img class="seccode-img" src="captcha/sec_code.php?da=" data-src="captcha/sec_code.php?da=" /><a class="reload-captcha" href="#" rel="nofollow">&nbsp;</a>
        <div><input type="text" class="m-input sec_text" name="seccode" value="" /></div><span class="clear"></span>
    </div><span class="clear"></span>
    <div class="acceptbox"><label><input type="checkbox" value="1" id="reg-accept"/> {{accept.descr}}</label></div>
    <div class="i-block"></div>
    <input type="submit" id="reg-submit" class="flatbutton disabled" value="{{dict.REGISTER}}" disabled="disabled">
    <span class="clear"></span>
</form>
<script >
    (function(){
        
        $('#reg-accept').change(function() {
            $('#reg-submit').prop('disabled', !this.checked);
            if (this.checked) {
                $('#reg-submit').removeClass('disabled');
            } else {
                $('#reg-submit').addClass('disabled');
            }
        });
        
        var frm = $('#frm_register'),
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