<div class="page-title">
    <img src="imgs/genericapp.png" />Pages <span class="last">{{if act(=add)}}Add new page{{else act}}edit page "{{item.name}}"{{end if act}}</span>
    <div class="page-toolbar">
        <a href="?show=pages&amp;block_id=fixed" id="apptoolbar-add" class="k-button w-button" title="Back" style="background-image:url('imgs/left.png');">&nbsp;</a>
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
                    <li class="k-title k-state-active">{{langs.0.name_en}}</li>
                    <li class="k-title">{{langs.1.name_en}}</li>
                    <li class="k-title">{{langs.2.name_en}}</li>
                </ul>
                <div>
                    <table class="fieldset">
                        <tr>
                            <th>Title</th>
                            <td>{{item.name}}</td>
                        </tr>
                        <tr class="notthis caption">
                            <th>Slides
                                <span class="k-descr">Please fill article numbers. Left empty , to fill auto</span>
                            </th>
                            <td>
                                <input type="text" name="item[tags]" class="k-input" value="{{item.tags}}"  />
                            </td>
                        </tr>

                        {{include (metas.inc)}}

                    </table>
                </div>
                <div>
                    <table class="fieldset transblock" data-lang="{{langs.1.id}}">

                        {{include (metas_lg1.inc)}}

                    </table>
                </div>
                <div>
                    <table class="fieldset transblock" data-lang="{{langs.2.id}}">



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



        $('#itemform').myForm({
            showSuccess: true,
            onSuccess: function (data) {

                return true;
            },
            beforeSubmit: function (arr, $form, options) {


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
    });
</script>
