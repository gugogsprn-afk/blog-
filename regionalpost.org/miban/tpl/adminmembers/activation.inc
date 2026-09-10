    <div id="loginform-wrapper">
        <div id="loginform-center">
            <h1>Account activation</h1>
            <div id="loginform" class="activation">
                <form action="?" method="post" id="login_form" name="login_form"  >
                    <input type="hidden" name="show" value="activate" />
                    <input type="hidden" name="h" value="{{hash}}" />
                    <input type="hidden" name="cmd" value="1" />
                    <div class="field-wrapper loginwrapper">
                        <span style="line-height: normal;">Login: </span><strong>{{member.usr_name}}</strong><i></i>
                    </div>
                    <div class="field-wrapper">
                        <span>Name Surname:</span><input type="text" id="aname" name="aname" class="input-field" value="{{input.name}}" autocomplete="off" /><i></i>
                    </div>
                    <div class="field-wrapper">
                        <span>Password:</span><input type="password" name="passa" class="input-field" value="{{input.passa}}" autocomplete="off"/><i></i>
                    </div>
                    <div class="field-wrapper">
                        <span>Change password:</span><input type="password" name="passb" class="input-field" value="{{input.passb}}" autocomplete="off"/><i></i>
                    </div>
                    <div class="field-wrapper captcha">
                        <img style="width:100px;height: 31px;" src="captcha/sec_code.php" alt="security code" id="sec_code" />
                        <a href="#reload" id="reload_code" class="k-button">&nbsp;</a>
                        <input type="text" name="fb_code" id="fb_code" class="input-field" value="" />
                        <i></i>
                    </div>
                    
                    <div class="submit-wrapper">
                        <input type="submit" name="submit" value="Register" class="k-button" />
                    </div>
                    <div class="clear"></div>
                </form>
            </div>
        </div>
    </div>
<script >
    $('#usr_login').watermark('Login    ');
    $('#usr_password').watermark('Password    ');
    
    $('#reload_code').click(function(e) {
    e.preventDefault();
            d = new Date();
            $('#sec_code').attr("src", "captcha/sec_code.php?da=" + d.getTime());
    });
    
    $(document).ready(function() {
        document.login_form.aname.focus();
    });
    
</script>
