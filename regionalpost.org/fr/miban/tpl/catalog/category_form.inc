<div class="page-title">
    <img src="imgs/genericapp.png" />Catalog <span class="last">{{if act(=add)}}Add{{else act}}Edit "{{item.name}}"{{end if act}}</span>
    <div class="page-toolbar">
        <a href="?show=pages&block_id=imageitems" id="apptoolbar-add" class="k-button w-button" title="Назад" style="background-image:url('imgs/left.png');">&nbsp;</a>
    </div>
</div>
<div id="page-content-wrapper">
    <form method="post" action="index.php" id="itemform">
        {{for current.queryparams as param}}
        <input type="hidden" name="{{param.key}}" value="{{param.value}}" />
        {{end for current.queryparams}}
        <input type="hidden" name="id" value="{{item.id}}" />
        <input type="hidden" name="voider" value="{{act}}"/>
        <input type="hidden" name="response" value="json" />
        <input type="hidden" name="item[pos]" value="{{item.pos}}"/>
        <input type="hidden" name="url[hardtype]" value="category"/>
        <input type="hidden" name="url[componenttype]" value="pages" />

        <input type="hidden" name="item[parent_id]" id="selectednode" value="{{parent.id}}" />
        <div>
            <div id="main-tabstrip">
                <ul>
                    <li class="k-title k-state-active">{{langs.0.name_en}}</li>
                    <li class="k-title">{{langs.1.name_en}}</li>
                    <li class="k-title">{{langs.2.name_en}}</li>
                </ul>
                <div>
                    <table class="fieldset">
                        <tr>
                            <th>Name</th>
                            <td><input type="text" id="category-name" name="item[name]" class="k-input" value="{{item.name}}" /></td>
                        </tr>
                        <tr>
                            <th>Alias<span class="k-descr">Only lowercase Latin letters,digits and " - " sign with no spaces</span></th>
                            <td id="category-url-object">
                                <input type="text" id="urlcache-alias" name="url[alias]" class="k-input" value="{{url.alias}}" />
                                <input type="hidden" name="url[url]" id="urlcache-url" value="{{url.url}}" />
                            </td>
                        </tr>
                        {{include (metas.inc)}}
                    </table>
                </div>
                <div>
                    <table class="fieldset transblock" data-lang="{{langs.1.id}}">
                        <tr>
                            <th class="trans">Name</th>
                            <td><input type="text" id="category-name_lg1" name="item[name_lg1]" class="k-input" value="{{item.name_lg1}}" /></td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table class="fieldset transblock" data-lang="{{langs.1.id}}">
                        <tr>
                            <th class="trans">Name</th>
                            <td><input type="text" id="category-name_lg2" name="item[name_lg2]" class="k-input" value="{{item.name_lg2}}" /></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="submitrow">
                <input type="submit" class="k-button submit" value="Save" />
            </div>
        </div>

    </form>

</div>

<script >

        $('#urlcache-alias').kendotranslit({
            from: '#category-name'
        });

        $('#urlcache-url').kendourlbuilder({
            from: '#urlcache-alias',
            parentid: '#selectednode',
            itemtype: 'category',
            mode: '{{act}}'
        });



    $('#itemform').myForm({
        onError: function (error, responseText) {

        },
        onSuccess: function (data) {

            return true;
        }
    });


    $('#main-tabstrip').kendoTabStrip({
            animation:  {
                open: {
                    effects: "fadeIn"
                }
            }
        });

</script>
