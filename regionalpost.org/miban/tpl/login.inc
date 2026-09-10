    <div id="loginform-wrapper">
        <div id="loginform-center">
            <h1>Login</h1>
            <div id="loginform">
                <form action="?" method="post" id="login_form" name="login_form"  >
                    <input type="hidden" name="login" value="1" />
                    <div class="field-wrapper"><input type="text" name="usr_login" id="usr_login" class="input-field" value="" /></div>
                    <div class="field-wrapper"><input type="password" id="usr_password" name="usr_password" class="input-field" value=""  /></div>
                    {{if captcha}}
                    <div class="field-wrapper"><img style="width:100px;height: 31px;" src="captcha/sec_code.php" alt="security code" id="sec_code" /><a href="#reload" id="reload_code" class="k-button">&nbsp;</a><input type="text" name="fb_code" id="fb_code" class="input-field" value="" /></div>
                    {{end if captcha}}
                    <div class="submit-wrapper">
                        <input type="submit" name="submit" value="Login" class="k-button" />
                    </div><div class="clear"></div>
                </form>
            </div>
        </div>
    </div>
<script >
    $('#usr_login').watermark('Login    ');
    $('#usr_password').watermark('Password    ');
    {{if captcha}}
    $('#reload_code').click(function(e) {
    e.preventDefault();
            d = new Date();
            $('#sec_code').attr("src", "captcha/sec_code.php?da=" + d.getTime());
    });
    {{end if captcha}}
    
    document.login_form.usr_login.focus();
</script>
