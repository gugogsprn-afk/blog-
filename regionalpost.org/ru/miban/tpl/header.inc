<div class="rp-admin-top">
    <div class="rp-admin-brand">
        <a class="rp-admin-logo" href="index.php?show=pages&amp;block_id=imageitems" title="Regional Post Admin">
            <span class="rp-admin-logo-mark">RP</span>
            <span class="rp-admin-logo-text">
                <strong>Regional Post</strong>
                <em>Admin · {{admin_lang_label}}</em>
            </span>
        </a>
    </div>

    <nav class="rp-lang-switch" aria-label="Admin language">
        <button type="button" class="rp-lang-current" aria-expanded="false" aria-haspopup="true">
            <span>{{if admin_is_en}}EN{{end if admin_is_en}}{{if admin_is_ru}}RU{{end if admin_is_ru}}{{if admin_is_fr}}FR{{end if admin_is_fr}}</span>
            <span class="rp-lang-arrow" aria-hidden="true"></span>
        </button>
        <div class="rp-lang-menu" role="menu">
            <a class="rp-lang-btn{{if admin_is_en}} is-current{{end if admin_is_en}}" href="{{admin_url_en}}" title="English admin" role="menuitem">EN</a>
            <a class="rp-lang-btn{{if admin_is_ru}} is-current{{end if admin_is_ru}}" href="{{admin_url_ru}}" title="Russian admin" role="menuitem">RU</a>
            <a class="rp-lang-btn{{if admin_is_fr}} is-current{{end if admin_is_fr}}" href="{{admin_url_fr}}" title="French admin" role="menuitem">FR</a>
        </div>
    </nav>

    <div id="Sirius-userinfo-wrapper" class="k-rtl rp-userinfo">
        <ul id="Sirius-userinfo">
            <li>
                <span id="Sirius-username">{{username}}</span>
                <ul>
                    <li><a href="#" title="Change password" id="member-passChange"><img src="imgs/passchange.png" alt="" />Change password</a></li>
                    <li><a href="?logout=1" title="logout"><img src="imgs/logout.png" alt="" />Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>

<div id="Sirius-MainMenu-wrapper" class="rp-main-nav">
    <ul id="Sirius-MainMenu">
        <li>Site<ul>
                <li>Settings<ul>
                        {{for configgroups as configgroup}}
                        <li><a href="index.php?show=config&parent_id={{configgroup.id}}">{{configgroup.name}}</a></li>
                        {{end for configgroups}}
                    </ul>
                </li>
                <li><a href="index.php?show=Banners">Banners</a></li>
                <li><a href="index.php?show=FileBrowser">File manager</a></li>
                <li><a href="index.php?show=slides">Slider</a></li>
                <li><a href="index.php?show=adminmembers">Administrators</a></li>
             </ul>
        </li>

        <li>Pages<ul>
                <li><a href="index.php?show=pages&amp;block_id=imageitems" title="Articles">Articles</a></li>
                <li class="k-separator"></li>
                <li><a href="index.php?show=pages&amp;block_id=menu" title="Menu">Menu</a></li>
                <li><a href="index.php?show=pages&amp;block_id=publicat" title="Publications">Publications</a></li>
                <li><a href="index.php?show=pages&amp;block_id=specitems" title="Podcasts">Podcasts</a></li>
                <li><a href="index.php?show=pages&amp;block_id=flatitems" title="RPB2B">RPB2B</a></li>
                <li><a href="index.php?show=pages&amp;block_id=fixed" title="Fixed pages">Fixed Pages</a></li>
                <li><a href="index.php?show=pages&amp;block_id=other" title="Other pages">Other pages</a></li>
            </ul>
        </li>
    </ul>
</div>

<nav class="rp-admin-subnav" aria-label="Quick navigation">
    <div class="rp-admin-subnav-group">
        <span class="rp-admin-subnav-label">Content</span>
        <a class="rp-subnav-link" data-rp-nav="pages:imageitems" href="index.php?show=pages&amp;block_id=imageitems">Articles</a>
        <a class="rp-subnav-link" data-rp-nav="pages:publicat" href="index.php?show=pages&amp;block_id=publicat">Publications</a>
        <a class="rp-subnav-link" data-rp-nav="pages:specitems" href="index.php?show=pages&amp;block_id=specitems">Podcasts</a>
        <a class="rp-subnav-link" data-rp-nav="pages:menu" href="index.php?show=pages&amp;block_id=menu">Menu</a>
        <a class="rp-subnav-link" data-rp-nav="pages:fixed" href="index.php?show=pages&amp;block_id=fixed">Fixed pages</a>
    </div>
    <div class="rp-admin-subnav-group">
        <span class="rp-admin-subnav-label">Site</span>
        <a class="rp-subnav-link" data-rp-nav="slides" href="index.php?show=slides">Slider</a>
        <a class="rp-subnav-link" data-rp-nav="Banners" href="index.php?show=Banners">Banners</a>
        <a class="rp-subnav-link" data-rp-nav="FileBrowser" href="index.php?show=FileBrowser">Files</a>
        <a class="rp-subnav-link" data-rp-nav="adminmembers" href="index.php?show=adminmembers">Admins</a>
    </div>
</nav>
<div class="clear"></div>
