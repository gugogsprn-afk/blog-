<div class="page-title">
    <img src="imgs/genericapp.png" />Страницы сайта <span class="last">{{if act(=add)}}Добавление новой страницы{{else act}}Редактирование "{{item.name}}"{{end if act}}</span>
    <div class="page-toolbar">
        <a href="?show=pages&block_id=partners" class="k-button w-button" title="Назад" style="background-image:url('imgs/left.png');">&nbsp;</a>
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
                            <th>Name</th>
                            <td><input type="text" id="pagesitem-name" name="item[name]" class="k-input" value="{{item.name}}" /></td>
                        </tr>
                        <tr>
                            <th>Link</th>
                            <td class="notthis">
                                <input type="text" class="k-input" name="item[tags]" value="{{item.tags}}" />
                            </td>
                        </tr>
                        <tr>
                            <th>Logo
                                <input name="file" type="file" /></th>
                            <td>
                                <div class="View-item" style="text-align: left;">
                                    <img id="item-image" style="max-height: 200px;" src="../{{file.thumbname}}"  />
                                    <a href="index.php?{{current.pageparams}}&voider=detach" data-itemid="{{item.id}}" data-fileid="{{file.id}}" data-filetype="thumb" class="k-button w-button a-action removefile" title="delete">&nbsp;</a>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="display: none;">
                    <table class="fieldset">
                        <tr>
                            <th class="trans" data-lang="{{langs.1.id}}">Name<span class="k-descr"></span></th>
                            <td><input type="text" id="pagesitem-name_lg1" name="item[name_lg1]" class="k-input" value="{{item.name_lg1}}" /></td>
                        </tr>
                        
                        
                    </table>
                </div>
                <div>
                    <table class="fieldset">
                        <tr>
                            <th class="trans" data-lang="{{langs.2.id}}">Name<span class="k-descr"></span></th>
                            <td><input type="text" id="pagesitem-name_lg2" name="item[name_lg2]" class="k-input" value="{{item.name_lg2}}" /></td>
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
                    $('#item-image').attr("src",'');
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