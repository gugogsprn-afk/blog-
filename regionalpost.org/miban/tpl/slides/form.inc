<div class="page-title">
    <img src="imgs/genericapp.png" />Slides <span class="last">{{if act(=add)}}Add new{{else act}}Edit "{{item.name}}"{{end if act}}</span>
    <div class="page-toolbar">
        <a href="?{{current.pageparams}}" id="apptoolbar-add" class="k-button w-button" title="Back" style="background-image:url('imgs/left.png');">&nbsp;</a>
    </div>
</div>
<div id="page-content-wrapper">
    <form method="post" action="index.php" id="itemform" enctype="multipart/form-data">
        {{for current.queryparams as param}}
        <input type="hidden" name="{{param.key}}" value="{{param.value}}" />
        {{end for current.queryparams}}
        <input type="hidden" name="id" id="item-id" value="{{item.id}}" />
        <input type="hidden" name="voider" id="action" value="{{act}}"/>
        <input type="hidden" name="item[pos]" value="{{item.pos}}"/>
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
                            <td><input type="text" id="slideitem-name" name="item[name]" class="k-input" value="{{item.name}}" /></td>
                        </tr>
                        <tr>
                            <th>Link</th>
                            <td><textarea name="item[descr]" class="k-input" style=" margin: 0 auto; height: 30px; width: 100%;">{{item.descr}}</textarea></td>
                        </tr>
                        <!--
                        <tr class="notthis caption">
                            <th colspan="2">Content</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[descr]" class="ckeditor" style=" margin: 0 auto;">{{item.descr}}</textarea>
                            </td>
                        </tr>
                        -->
                        <tr class="notthis caption">
                            <th colspan="2">
                                Image
                                <span class="k-descr">Image size {{imageset.w}}x{{imageset.h}}</span>
                                <input name="image" type="file" />
                            </th>
                        </tr>
                        <tr class="notthis">
                            <td colspan="2">
                                <div class="View-item" style="text-align: left;">
                                    <img width="500" id="item-image" src="../{{file.filename}}"  />
                                    <a href="index.php?{{current.pageparams}}&voider=detach" data-itemid="{{item.id}}" data-fileid="{{file.id}}" data-filetype="image" class="k-button w-button a-action removefile" title="delete">&nbsp;</a>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table class="fieldset transblock" data-lang="{{langs.1.id}}">
                        <tr>
                            <th class="trans">Name<span class="k-descr"></span></th>
                            <td><input type="text" id="slideitem-name_lg1" name="item[name_lg1]" class="k-input" value="{{item.name_lg1}}" /></td>
                        </tr>
                        <tr>
                            <th class="trans">Link</th>
                            <td><textarea name="item[descr_lg1]" class="k-input" style=" margin: 0 auto; height: 30px; width: 100%;">{{item.descr_lg1}}</textarea></td>
                        </tr>
                        <!--
                        <tr class="notthis caption">
                            <th colspan="2" class="trans" data-lang="ru">Content</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[descr_lg1]" class="ckeditor" style=" margin: 0 auto;">{{item.descr_lg1}}</textarea>
                            </td>
                        </tr>
                        -->
                    </table>
                </div>
                <div>
                    <table class="fieldset">
                        <tr>
                            <th class="trans">Name<span class="k-descr"></span></th>
                            <td><input type="text" id="slideitem-name_lg2" name="item[name_lg2]" class="k-input" value="{{item.name_lg2}}" /></td>
                        </tr>
                        <tr>
                            <th class="trans">Link</th>
                            <td><textarea name="item[descr_lg2]" class="k-input" style=" margin: 0 auto; height: 30px; width: 100%;">{{item.descr_lg2}}</textarea></td>
                        </tr>
                        <!--
                         <tr class="notthis caption">
                             <th colspan="2" class="trans" data-lang="en">Content</th>
                         </tr>
                         <tr>
                             <td class="notthis" colspan="2" style="text-align: center;">
                                 <textarea name="item[descr_lg2]" class="ckeditor" style=" margin: 0 auto;">{{item.descr_lg2}}</textarea>
                             </td>
                         </tr>
                        -->
                    </table>
                </div>
            </div>
            <div class="submitrow">
                <input type="submit" class="k-button submit" value="Save" />
            </div>
        </div>


    </form>

</div>
<div id="items-window">

</div>
<script >
    $(document).ready(function () {

        $('#itemform').myForm({
            showSuccess: true,
            onSuccess: function (data) {
                if (data.data && data.data.filename !== undefined) {
                    $('#item-image').attr("src", '../' + data.data.filename);
                }
                return true;
            },
            beforeSubmit: function () {
                return true;
            }
        });

        $('.removefile').click(function (e) {
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
                data: {response: 'json', itemid: itemid, id: fileid, type: filetype},
                onSuccess: function (data, status) {
                    $('#item-image').attr("src", '');
                }
            });
        });

        $('#main-tabstrip').kendoTabStrip({
            animation: {
                open: {
                    effects: "fadeIn"
                }
            }
        });

    });
</script>