<form method="POST" action="{{curlang}}/members/login.html" id="frm_login" class="fields">
    <label>{{dict.USERNAME}} <input type="text" name="email" class="m-input" /></label>
    <label>{{dict.PASSWORD}} <input type="password" name="password" class="m-input" /></label>
    <div class="ads">
        <a href="members/passreset.html" title="{{dict.RESETPASS}}">{{dict.RESETPASS}}</a><!-- | 
        <a href="members/register.html" title="{{dict.REGISTER}}">{{dict.REGISTER}}</a>-->
    </div>
    <div class="seccode-wrap {{capclass}}">
        <label>{{dict.CAPTCHA}}</label>
        <img class="seccode-img" src="captcha/sec_code.php?g=1&da=" data-src="captcha/sec_code.php?g=1&da=" /><a class="reload-captcha" href="#" rel="nofollow">&nbsp;</a>
        <div><input type="text" class="m-input sec_text" name="seccode" value="" /></div><span class="clear"></span>
    </div><span class="clear"></span>
    <div class="i-block"></div>
    <input type="submit" class="flatbutton" value="{{dict.LOGIN}}">
</form>
<span class="clear"></span>
<!--
<div class="hr"></div>
<div class="h2" id="soc-log-h">{{dict.AUTHSOC}}</div>
<div id="soc-conn">
    <div class="info-row"></div><span class="clear"></span>
    <div class="fb-login-button" data-max-rows="1" data-size="large" data-scope="email,public_profile,user_birthday" onlogin="onLoginFB" data-show-faces="false" data-auto-logout-link="false"></div>
    <button id="signinButtonGoogle" class="flatbutton"><span></span> Sign in with Google</button>
    <div id="vk_api_transport"></div><div id="vk_auth">Login with VK</div> 
    
</div>    
-->
<script>
    /*
    window.vkAsyncInit = function() {
        console.log('VK init');
        VK.init({apiId: 5139424});
        
        VK.Widgets.Auth("vk_auth", {width: "200px", onAuth: function(data) {
            console.log('login');
            console.log(data);
            makeRequest({
                    url: 'members/social_auth.html',
                    data: { response: 'json', p: 'vk', auth: data },
                    onError: function(error,responseText) { 
                        console.log(error);
                        return false; 
                    },
                    wrapper: null
                });
            first_name: "Kavalski"
            hash: "553c8367a0dc762444b7ccbb2c4a4583"
            last_name: "Smirnoff"
            photo: "/images/camera_200.png"
            photo_rec: "/images/camera_50.png"
            uid: 303569614
            session: Object
                expire: 1446990181
                mid: 303569614
                secret: "b19f66a587"
                sid: "88968da3b483ccbe3e218978033ff1787ea4c31f14b043627242c5ce437f650500d18b9b04d89d95beff1"
                sig: "2965005639a7af0a839d7720de8e3ca8"
        }});

    };
    setTimeout(function() {
    var el = document.createElement("script");
        el.type = "text/javascript";
        el.src = "//vk.com/js/api/openapi.js?120";
        el.async = true;
        document.getElementById('vk_api_transport').appendChild(el);
    },0);
    */
</script>
<script>
    /*
    function googleAsyncInit() {
        var auth2;
        console.log('google.load');
        gapi.load('auth2',function() {
            var infoBlock = $('#soc-conn > .info-row');
            auth2 = gapi.auth2.init({
                client_id: '{{g_clientid}}',
                scope: 'profile email'
            });
            auth2.attachClickHandler(document.getElementById('signinButtonGoogle') , {},
                function(googleUser) {
                    var TokenData = googleUser.getAuthResponse();
                    var wrapper = $('#frm_login').closest('div');
                    wrapper.block({message: '<img src="imgs/loading.gif" />'});
                    infoBlock.html('');
                    makeRequest({
                        url: '{{curlang}}/members/social_auth.html',
                        data: { response: 'json', p: 'google', auth: TokenData },
                        onError: function(error,responseText) { 
                            wrapper.unblock();
                            infoBlock.html(error.error);
                            return false; 
                        },
                        wrapper: wrapper
                    });
                }, function(error) {
                    console.log(error);
                }
            );
        });
    }
    */
</script>
<script >
    
    (function(){
        
        
        


        var frm = $('#frm_login'),
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
<script >    
    /*
    window.fbAsyncInit = function() {
        console.log('fb init');
        FB.init({
          appId: '{{fbapp}}',
          xfbml: false,
          version: 'v2.5',
          cookie: true
        });
    };
    
    function onLoginFB() {
        var infoBlock = $('#soc-conn > .info-row');
        var wrapper = $('#frm_login').closest('div');
        wrapper.block({message: '<img src="imgs/loading.gif" />'});
        infoBlock.html('');
        FB.getLoginStatus(function(response) {
            console.log('getLoginStatus');
            console.log(response);
            if (response.status === 'connected') {
                wrapper.unblock();
                makeRequest({
                    url: '{{curlang}}/members/social_auth.html',
                    data: { response: 'json', p: 'fb', auth: response.authResponse },
                    onError: function(error,responseText) { 
                        wrapper.unblock();
                        infoBlock.html(error.error);
                        return false; 
                    },
                    wrapper: wrapper
                });
            } else if (response.status === 'not_authorized') {
                wrapper.unblock(); // The person is logged into Facebook, but not your app.
            }else {
                wrapper.unblock();
                // The person is not logged into Facebook, so we're not sure if
                // they are logged into this app or not.
            }
        });
    }
    */
</script>