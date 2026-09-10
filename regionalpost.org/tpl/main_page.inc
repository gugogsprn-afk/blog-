<div class="main-slider slides">
    {{for slides as sitems}}
    <a
        class="main-slider-item"
        href="{{sitems.url}}"
    >
        {{if sitems.thumbname}}
        <img
            src="{{sitems.thumbname}}"
            alt="{{sitems.name}}"
        />
        {{else sitems.thumbname}}
        <img
            src="{{sitems.filename}}"
            alt="{{sitems.name}}"
        />
        {{end if sitems.thumbname}}
        <div class="main-slider-link">
            <h2 title="{{sitems.name}}">{{sitems.name}}</h2>
            <p class="main-slider-subtitle">{{sitems.binfo}}</p>
        </div>
    </a>
    {{end for slides}}
</div>
<div class="page">
    <div
        class="section main-content"
        id="unikal_section"
        data-v="{{dict.READMORE}}"
    >
        <div class="flex-wrap">
            {{for articles as ac}}
            <div class="flex-box flex{{ac.mult}}">
                <div
                    class="news-item"
                    data-id="{{ac.id}}"
                >
                    <a
                        href="{{ac.url}}"
                        title="{{ac.name}}"
                    >
                        <div class="image">
                            <img
                                src="{{ac.filename}}"
                                alt="{{ac.name}}"
                                title="{{ac.name}}"
                            />
                        </div>
                        <div class="acl">
                            <b>{{ac.ainfo}}</b>
                            <div class="h1">{{ac.name}}</div>
                            <div class="h2">{{ac.binfo}}</div>
                            <p>{{ac.descr}}</p>
                            <span class="date">{{ac.date_object}}</span>
                        </div>
                    </a>
                </div>
            </div>
            {{ac.ads}} {{end for articles}}
            <span class="clear"></span>
        </div>
    </div>
    <div id="raside">{{raside}}</div>
</div>
<div class="publicat page">
    <div class="publicat-header block-header">
        <h2 class="publicat-title">
            <a href="/publications">Regional Post Magazine</a>
        </h2>
        <p class="publicat-recent">Recent Issues</p>
        <a href="{{curlangp}}publications">{{dict.SHOWALL}}</a>
    </div>
    <div class="publicat-list slides">
        {{for pubs as pub}}
        <a
            href="{{pub.filename}}"
            title="{{pub.name}}"
            target="_blank"
            class="publicat-item"
        >
            <div class="image">
                <img
                    src="{{pub.thumbname}}"
                    alt="{{pub.name}}"
                    title="{{pub.name}}"
                />
            </div>
            <div class="acl">
                <b>{{pub.ainfo}}</b>
                <div class="h1">{{pub.name}}</div>
                <div class="h2">{{pub.binfo}}</div>
                <p>{{pub.descr}}</p>
            </div>
        </a>
        {{end for pubs}}
    </div>
</div>

<script>

    $('.main-slider').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 8000
    });

    $('.publicat-list').slick({
        infinite: true,
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 8000,
        responsive: [
            {
                breakpoint: 1400,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 1300,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 1000,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 700,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
</script>

<!-- <script>

    $('.acl p').each(function() {
        if ($(this).outerHeight()> 145) {
            $(this).dotdotdot({
                height: 145
            });
        }

    });

    var isLoading = false;
    var page = 1;

    $(window).scroll(function() {

        if (isLoading==true) {
            return;
        }
        var minHeight = 20;
        if ($(window).width()<1100) {
            minHeight = 62;
        }

        if($(window).scrollTop() + $(window).height() > $(document).height()-minHeight) {

            isLoading = true;
            $.get('{{curlangp}}?loadpart=1&response=block&page='+page,function(data) {
                var newContent = $(data);
                $('#unikal_section').append(newContent);
                isLoading = false;
                page = page + 1;

                console.log("loaded" + data);
            });
        }
    });
</script> -->
