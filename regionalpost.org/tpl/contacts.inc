<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false{{if mapkey}}&amp;key={{mapkey}}{{end if mapkey}}"></script>
<div class="content-left page page-small">
    <h1 class='slash big content-title'>{{item.name}}</h1>
{{if geo}}
<div id="map-wrapper" class="map-wrapper">
    <img src="imgs/loading.gif" style=" position: absolute; top: 50%; left: 50%; margin-top: -24px; margin-left: -24px; " alt="loading..." />
</div>
{{end if geo}}
    <article class="section contact-section">

        <div>
            <div id="contact-content-wrap">
                <div id="contact-content">
                    {{item.content}}
                </div>
            </div>
            {{if sendmail}}
            <div id="mailform">
                <form method="post" action="{{item.url}}" id="fb_form">
                    <input type="hidden" name="cmd" value="1" />
                    <div class="fieldset">
                        <div><i id="fb_form-name"></i><input type="text" name="member[name]" value="{{membername}}" class="m-input" id="ml-name" placeholder="{{dict.NAME_SURNAME}} *" /></div>
                        <div><i id="fb_form-mail"></i><input type="text" name="member[mail]" value="{{membermail}}" class="m-input"  id="ml-mail" placeholder="{{dict.EMAIL}} *" /></div>
                        <div><i id="fb_form-message"></i><textarea name="message[content]" cols="20" rows="10"  class="m-input" id="ml-message" placeholder="{{dict.MESSAGE}} *"></textarea></div>
                        <div class="image seccode-wrap">
                            <img class="sec_code" src="captcha/sec_code.php" alt="captcha" /><a class="reload-captcha" href="#" >&nbsp;</a><div><input type="text" class="m-input sec_text" name="seccode" value="" style="width: 130px;" /></div>
                            <span class="clear"></span>
                        </div>
                        <span class="clear"></span>
                        <div id="infowrap"></div>
                        <div class="submit-wrapper">
                            <input type="submit" class="flatbutton submit" name="submit" value="{{dict.SEND}}" />
                        </div>
                    </div>
                </form>
            </div>
            {{end if sendmail}}
            <span class="clear"></span>
        </div>
        <span class="clear" style="height: 60px;"></span>
    </article>
</div>

<script>
    $(document).ready(function() {

        {{if geo}}
        function initializeGeo() {
          var loc = new google.maps.LatLng({{geo.lat}}, {{geo.long}});
          var mapOptions = {
            zoom: {{geo.zoom}},
            center: loc,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false
          };
          var map = new google.maps.Map(document.getElementById('map-wrapper'),
              mapOptions);

          var marker = new google.maps.Marker({
            position: loc,
            map: map
          });
        }
        google.maps.event.addDomListener(window, 'load', initializeGeo);
        {{end if geo}}


        var reloadCaptcha = function () {
            d = new Date();
            $('#fb_form').find('.sec_code').attr("src","captcha/sec_code.php?da=" + d.getTime());
            $('#fb_form').find('.sec_text').val('');
        }

        $('#fb_form').find('.reload-captcha').click(function(e) {
            e.preventDefault();
            reloadCaptcha();
        });

        $('#fb_form').myForm({
            wrapper: '#mailform',
            beforeSubmit: function(arr, form, options) {
                $('#infowrap').removeClass('error').removeClass('info').html('');
                return true;
            },
            onSuccess: function(data,status) {
                $('#infowrap').removeClass('error').addClass('info');
                $('#infowrap').html(data.info);
                $('#fb_form').find('input[type="text"]').val('');
                $('#fb_form').find('textarea').html('');
                $('#fb_form').find('textarea').val('');
                reloadCaptcha();
                return false;
            },
            onError : function(data,responseText) {
                if (data.invalids) {
                    $('#'+data.invalids[0]).invalid();
                } else {
                    $('#infowrap').removeClass('info').addClass('error');
                    $('#infowrap').html(data.error);
                }
                reloadCaptcha();
                return false;
            }
        });


    });

</script>
