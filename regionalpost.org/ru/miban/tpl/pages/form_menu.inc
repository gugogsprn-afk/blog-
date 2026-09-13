<div class="page-title">
    <img src="imgs/genericapp.png" />Pages <span class="last">{{if act(=add)}}Add new page{{else act}}edit page "{{item.name}}"{{end if act}}</span>
    <div class="page-toolbar">
        <a href="j?show=pages&amp;block_id=menu" class="k-button w-button" title="Back" style="background-image:url('imgs/left.png');">&nbsp;</a>
    </div>
</div>
<div id="page-content-wrapper">
    <form method="POST" action="index.php" id="itemform" enctype="multipart/form-data">
        {{for current.queryparams as param}}
        <input type="hidden" name="{{param.key}}" value="{{param.value}}" />
        {{end for current.queryparams}}
        <input type="hidden" name="voider" id="action" value="{{act}}"/>
        <input type="hidden" name="item[pos]" value="{{item.pos}}"/>
        <input type="hidden" name="item[block_id]" value="{{block.name}}" />
        <input type="hidden" name="id" value="{{item.id}}" />
        {{if parent_id}}
        <input type="hidden" name="item[parent_id]" id="pageparent" value="{{parent_id}}" />
        {{else parent_id}}
        <input type="hidden" id="pageparent" value="1" />
        {{end if parent_id}}
        
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
                            <td><input type="text" id="pagesitem-name" name="item[name]" class="k-input" value="{{item.name}}" /></td>
                        </tr>
                        <tr>
                            <th>Alias<span class="k-descr">Only lowercase Latin letters,digits and " - " sign with no spaces</span></th>
                            <td id="pages-url-object">
                                <input type="text" id="urlcache-alias" name="url[alias]" class="k-input" value="{{url.alias}}" />
                                <input type="hidden" name="url[url]" id="urlcache-url" value="{{url.url}}" />
                                <input type="hidden" name="url[componenttype]" value="pages"/>
                            </td>
                        </tr>
                        <!--<tr>
                            <th>Description</th>
                            <td class="notthis">
                                <textarea name="item[descr]" class="k-input" rows="5" cols="50" style="width: 100%; height: 40px;" >{{item.descr}}</textarea>
                            </td>
                        </tr>-->
                        <tr class="notthis caption">
                            <th colspan="2">Contents</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[content]" class="ckeditor" style=" margin: 0 auto;">{{item.content}}</textarea>
                            </td>
                        </tr>
                        
                        {{include (metas.inc)}}
                    </table>
                </div>
                <div>
                    <table class="fieldset transblock" data-lang="{{langs.1.id}}">
                        <tr>
                            <th class="trans">Name</th>
                            <td><input type="text" id="pagesitem-name_lg1" name="item[name_lg1]" class="k-input" value="{{item.name_lg1}}" /></td>
                        </tr>
                        <!--
                        <tr>
                            <th class="trans">Description</th>
                            <td class="notthis" >
                                <textarea name="item[descr_lg1]" class="k-input" rows="5" cols="50" style="width: 100%; height: 40px;" >{{item.descr_lg1}}</textarea>
                            </td>
                        </tr>
                        -->
                        <tr class="notthis caption">
                            <th colspan="2" class="trans">Contents</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[content_lg1]" class="ckeditor" style=" margin: 0 auto;">{{item.content_lg1}}</textarea>
                            </td>
                        </tr>
                
                        {{include (metas_lg1.inc)}}
                        
                    </table>
                </div>
                <div>
                    <table class="fieldset transblock" data-lang="{{langs.2.id}}">
                        <tr>
                            <th class="trans">Name</th>
                            <td><input type="text" id="pagesitem-name_lg2" name="item[name_lg2]" class="k-input" value="{{item.name_lg2}}" /></td>
                        </tr>
                        <!--
                        <tr>
                            <th class="trans">Description</th>
                            <td class="notthis" >
                                <textarea name="item[descr_lg2]" class="k-input" rows="5" cols="50" style="width: 100%; height: 40px;" >{{item.descr_lg2}}</textarea>
                            </td>
                        </tr>
                        -->
                        <tr class="notthis caption">
                            <th colspan="2" class="trans">Contents</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[content_lg2]" class="ckeditor" style=" margin: 0 auto;">{{item.content_lg2}}</textarea>
                            </td>
                        </tr>
                        
                        {{include (metas_lg2.inc)}}
                        
                        
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
    $(document).ready(function () {
      
        $('#pagesitem-date').kendoDatePicker();
        
        
        $('#urlcache-alias').kendotranslit({
            from: '#pagesitem-name'
        });
        
        $('#urlcache-url').kendourlbuilder({
            from: '#urlcache-alias',
            parentid: '#pageparent',
            itemtype: 'pages',
            mode: '{{act}}'
        });
        
        $('#itemform').myForm();
        $('#main-tabstrip').kendoTabStrip({
            animation:  {
                open: {
                    effects: "fadeIn"
                }
            }
        });
    });
</script>