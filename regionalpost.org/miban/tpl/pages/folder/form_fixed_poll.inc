<div class="page-title">
    <img src="imgs/genericapp.png" />Pages <span class="last">{{if act(=add)}}Add new page{{else act}}edit page "{{item.name}}"{{end if act}}</span>
    <div class="page-toolbar">
        <a href="javascript:window.history.go(-1);" id="apptoolbar-add" class="k-button w-button" title="Back" style="background-image:url('imgs/left.png');">&nbsp;</a>
    </div>
</div>
<div id="page-content-wrapper">
    <form method="POST" action="index.php" id="itemform" enctype="multipart/form-data">
        {{for current.queryparams as param}}
        <input type="hidden" name="{{param.key}}" value="{{param.value}}" />
        {{end for current.queryparams}}
        <input type="hidden" name="id" id="item-id" value="{{item.id}}" />
        <input type="hidden" name="item[block_id]" id="parentid" value="{{block.name}}" />
        <input type="hidden" name="voider" id="action" value="{{act}}"/>
        <input type="hidden" name="item[pos]" value="{{item.pos}}"/>
        <div>
            <div id="main-tabstrip">
                <ul>
                    <li class="k-title k-state-active">Armenian</li>
                    <li class="k-title" style="display: none;">Arabic</li>
                    <li class="k-title">English</li>
                </ul>
                <div>
                    <table class="fieldset">
                        <tr>
                            <th>Title</th>
                            <td><input type="text" id="pagesitem-name" name="item[name]" class="k-input" value="{{item.name}}" /></td>
                        </tr>
                        
                        <tr>
                            <th>Description</th>
                            <td class="notthis">
                                 <textarea name="item[descr]" class="k-input" rows="5" cols="50" style="width: 100%; height: 40px;" >{{item.descr}}</textarea>
                            </td>
                        </tr>
                        <tr class="notthis">
                            <th></th>
                            <td>
                                <div id="polllist" style="width: 400px;">
                                    {{for ps as p}}
                                    <a href="#" id="pll-{{p.id}}" data-id="{{p.id}}" class="{{p.class}}">{{p.inner}}<span>{{p.name}}</span></a>
                                    {{end for ps}}
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="display: none;">
                    <table class="fieldset">
                        <tr>
                            <th class="trans" data-lang="{{langs.1.id}}">Title<span class="k-descr"></span></th>
                            <td><input type="text" id="pagesitem-name_lg1" name="item[name_lg1]" class="k-input" value="{{item.name_lg1}}" /></td>
                        </tr>
                        <tr>
                            <th class="trans" data-lang="{{langs.1.id}}">Heading</th>
                            <td><input type="text" name="item[ainfo_lg1]" class="k-input" value="{{item.ainfo_lg1}}" /></td>
                        </tr>
                        <tr>
                            <th class="trans" data-lang="{{langs.1.id}}">Description</th>
                            <td class="notthis" >
                                <textarea name="item[descr_lg1]" class="k-input" rows="5" cols="50" style="width: 100%; height: 40px;" >{{item.descr_lg1}}</textarea>
                            </td>
                        </tr>
                        <tr class="notthis caption">
                            <th colspan="2" class="trans" data-lang="{{langs.1.id}}">Contents</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[content_lg1]" class="ckeditor" style=" margin: 0 auto;">{{item.content_lg1}}</textarea>
                            </td>
                        </tr>
                        
                        <tr>
                            <th class="trans" data-lang="{{langs.1.id}}">Banner Text</th>
                            <td class="notthis">
                                <textarea name="item[binfo_lg1]" class="k-input" rows="5" cols="50" style="width: 100%; height: 120px;" >{{item.binfo_lg1}}</textarea>
                            </td>
                        </tr>
                        
                        <tr class="notthis caption">
                            <th colspan="2">SEO</th>
                        </tr>
                        <tr>
                            <th class="trans" data-lang="{{langs.1.id}}">Title</th>
                            <td><input type="text" name="url[meta_title_lg1]" class="k-input" value="{{url.meta_title_lg1}}" /></td>
                        </tr>
                        <tr>
                            <th class="trans" data-lang="{{langs.1.id}}">Keywords<span class="k-descr">Maximum 1000 chars (separator ",")</span></th>
                            <td>
                                <textarea name="url[meta_keys_lg1]" maxlength="1000" rows="4" cols="30" class="k-input maxlength" style="width:100%;">{{url.meta_keys_lg1}}</textarea>
                                <span class="chars_remainer k-descr">Left <strong></strong> /1000</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="trans" data-lang="{{langs.1.id}}">Page description<span class="k-descr">Maximum 200 chars</span></th>
                            <td>
                                <textarea name="url[meta_descr_lg1]" maxlength="200" rows="3" cols="30" class="k-input maxlength" style="width:100%;">{{url.meta_descr_lg1}}</textarea>
                                <span class="chars_remainer k-descr">Left <strong></strong> /200</span>
                            </td>
                        </tr>  
                    </table>
                </div>
                <div>
                    <table class="fieldset">
                        <tr>
                            <th class="trans" data-lang="{{langs.2.id}}">Title</th>
                            <td><input type="text" id="pagesitem-name_lg2" name="item[name_lg2]" class="k-input" value="{{item.name_lg2}}" /></td>
                        </tr>
                        
                        <tr>
                            <th class="trans" data-lang="{{langs.2.id}}">Description</th>
                            <td class="notthis">
                                <textarea name="item[descr_lg2]" class="k-input" rows="5" cols="50" style="width: 100%; height: 40px;" >{{item.descr_lg2}}</textarea>
                            </td>
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
    $(document).ready(function () {

        
        $('#itemform').myForm({
            showSuccess: true,
            onSuccess: function (data) {
                if (data.data && data.data.filename!==undefined) {
                    $('#item-image').attr("src",'../' + data.data.filename);
                }
                if (data.data && data.data.thumbname!==undefined) {
                    $('#item-thumb').attr("src",'../' + data.data.thumbname);
                }
                return true;
            },
            beforeSubmit: function () {
                return true;
            }
        });
        
        $('.removefile').click(function(e) {
            e.preventDefault();
            if (!confirm('Delete?'))
                return;
            var url = $(this).attr('href');
            var itemid = $(this).data("itemid");
            var fileid = $(this).data("fileid");
            var filetype = $(this).data("filetype");
            
            makeRequest({
                url: url,
                method: 'POST',
                dataType: 'json',
                data: { response: 'json', itemid: itemid, id: fileid, type: filetype },
                onSuccess: function(data,status) { 
                    $('#item-' + filetype).attr("src",'');
                }
            });
        });
        
        $('#main-tabstrip').kendoTabStrip({
            animation:  {
                open: {
                    effects: "fadeIn"
                }
            }
        });
    });
</script>