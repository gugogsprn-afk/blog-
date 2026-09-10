<div id="Sirius-MainMenu-wrapper">
    <ul id="Sirius-MainMenu">
    <li>Site<ul>
            <li>Settings<ul> <!-- moving the UL to the next line will cause an IE7 problem -->
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
            <li><a href="index.php?show=pages&amp;block_id=publicat" title="publicat">Publications</a></li>
            <li><a href="index.php?show=pages&amp;block_id=specitems" title="Podcasts">Podcasts</a></li>
            <li><a href="index.php?show=pages&amp;block_id=flatitems" title="RPB2B">RPB2B</a></li>
            <li><a href="index.php?show=pages&amp;block_id=fixed" title="Fixed pages">Fixed Pages</a></li>
            <li><a href="index.php?show=pages&amp;block_id=other" title="Other pages">Other pages</a></li>
        </ul>
    </li>


</ul>
</div>
<div id="Sirius-userinfo-wrapper" class="k-rtl">
    <ul id="Sirius-userinfo">
        <li>
            <span id="Sirius-username">{{username}}</span>
            <ul>
                <li><a href="#" title="Change password" id="member-passChange"  ><img src="imgs/passchange.png" />Change password</a></li>
                <li><a href="?logout=1" title="logout" ><img src="imgs/logout.png" />logout</a></li>
            </ul>
        </li>
    </ul>
</div>
<div class="clear"></div>
