{{if anyslide}}
<div id="slider-wrapper">
    {{if slides}}
    <ul id="main-slider">
        {{for slides as slide}}
        <li>
            <div class="main-slide">
                <img src="{{slide.filename}}" alt="{{slider.name}}" />
                {{if slide.window}}
                <div class="video-button-wrapper"><a href="{{slide.window}}" class="fancybox.iframe hrefbox" title="{{slide.name}}"><span>&nbsp;</span></a></div>
                {{end if slide.window}}
            </div>
            
        </li>
        {{end for slides}}
    </ul>
    <script >
        $('#main-slider').bxSlider({
            auto: true,
            pager: true,
            speed: 1000,
            tickerHover:true,
            pause: {{slidespeed}},
            controls: false

          });
    </script>
    {{end if slides}}
    {{if slide}}
    <div class="main-slide">
        <img src="{{slide.filename}}" alt="{{slider.name}}" />
        {{if slide.window}}
        <div class="video-button-wrapper"><a href="{{slide.window}}" class="fancybox.iframe hrefbox" title="{{slide.name}}"><span>&nbsp;</span></a></div>
        {{end if slide.window}}
    </div>
    {{end if slide}}
</div>
{{end if anyslide}}