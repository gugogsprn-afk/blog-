{{if isedit}}
<form method="post" action="{{curlang}}/members/changepass.html" id="frm_password" class="fields">
    <input type="hidden" name="cmd" value="1" />
{{else isedit}}    
<form method="POST" action="{{curlang}}/members/passreset.html" id="frm_password" class="fields">
    <input type="hidden" name="cmd" value="3" />
    <input type="hidden" name="h" value="{{hash}}" />
{{end if isedit}}
<div class="table fieldset">
    {{if isedit}}
    <div class="tr">
        <div class="td" >
            <label>{{dict.OLDPASS}} <input type="password" name="password" class="m-input kp" /></label>
        </div>
        <div class="td" >
            &nbsp;
        </div>
    </div>
    {{end if isedit}} 
    <div class="tr">
        <div class="td" >
            <label>{{dict.NEWPASSWORD}} <input type="password" name="passone" class="m-input kp" /></label>
        </div>
        <div class="td" >
            <label>{{dict.PASSWORDREP}} <input type="password" name="passtwo" class="m-input kp" /></label>
        </div>
    </div>
</div>

    <div class="i-block"></div>
    <input type="submit" class="flatbutton" value="{{dict.SAVE}}"></a>
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