<div class="page-title">
    <img src="imgs/genericapp.png" />Roles and permissions <span class="last">{{if act(=add)}}add role{{else act}}edit "{{item.name}}"{{end if act}}</span>
    <div class="page-toolbar">
        <a href="?{{current.pageparams}}" id="apptoolbar-add" class="k-button w-button" title="Back" style="background-image:url('imgs/left.png');">&nbsp;</a>
    </div>
</div>
<div id="page-content-wrapper">
    <form method="post" action="?" id="itemform">
        <input type="hidden" name="show" value="roles" />
        <input type="hidden" name="voider" value="{{act}}" />
        <input type="hidden" name="id" id="role-id" value="{{item.id}}" />
        <table class="fieldset">
            <tr>
                <th style="width: 400px;">Name</th>
                <td><input type="text" id="role-name" name="item[name]" class="k-input" value="{{item.name}}" style="width: 300px;" /></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><textarea name="item[descr]" rows="2" cols="30" style="width:100%;height:50px" class="k-input">{{item.descr}}</textarea></td>
            </tr>
            <tr>
                <td class="notthis" colspan="2" style="padding: 0;">
                    <table class="fieldset permlist" id="perm-table">
                        <tr>
                            <th></th>
                            <td>Read</td>
                            <td>Add</td>
                            <td>Edit</td>
                            <td>Delete</td>
                            <td>&nbsp;</td>
                        </tr>
                        {{for perms as group}}
                        <tr class="notthis caption">
                            <th colspan="6">{{group.parent}}</th>
                        </tr>
                        {{for group.items as item}}
                        <tr>
                            <th>{{item.name}}</th>
                            <td>{{if item.ar}}<input name="perm[{{item.id}}][1]" type="checkbox" value="1" {{item.arc}} />{{end if item.ar}}</td>
                            <td>{{if item.aw}}<input name="perm[{{item.id}}][2]" type="checkbox" value="2" {{item.awc}} />{{end if item.aw}}</td>
                            <td>{{if item.ae}}<input name="perm[{{item.id}}][4]" type="checkbox" value="4" {{item.aec}} />{{end if item.ae}}</td>
                            <td>{{if item.ad}}<input name="perm[{{item.id}}][8]" type="checkbox" value="8" {{item.adc}} />{{end if item.ad}}</td>
                            <td>&nbsp;</td>
                        </tr>
                        {{end for group.items}}
                        {{end for perms}}
                        <!--
                        <tr class="notthis caption" id="rolelist-selector">
                            <th style="vertical-align: middle;">Category roles</th>
                            <td colspan="5" style="text-align: left !important;">
                                <select id="categories" style="width: 300px;">
                                    <option value="0">--//--</option>
                                    {{for cats as cat}}
                                    <option value="{{cat.id}}">{{cat.name}}</option>
                                    {{end for cats}}
                                </select>
                                <a href="#" class="k-button" id="addCatButton" title="Add" style="background-image:url('imgs/simple_plus.png');">&nbsp;</a>
                            </td>
                        </tr>
                        [[for cperms as cperm]]
                        <tr>
                            <th id="cat_{{cperm.id}}">{{cperm.name}}<div class="k-descr">Subcategories, items and files</div><input type="hidden" name="cerm[29][id][]" value="{{cperm.id}}" /></th>
                            <td><input name="cerm[29][{{cperm.id}}][1]" type="checkbox" value="1" {{cperm.arc}} /></td>
                            <td><input name="cerm[29][{{cperm.id}}][2]" type="checkbox" value="2" {{cperm.awc}} /></td>
                            <td><input name="cerm[29][{{cperm.id}}][4]" type="checkbox" value="4" {{cperm.aec}} /></td>
                            <td><input name="cerm[29][{{cperm.id}}][8]" type="checkbox" value="8" {{cperm.adc}} /></td>
                            <td><a href="#" title="Delete" class="remove-composite k-button w-button b-remove" style="padding: 3px 11px !important;">&nbsp;</a></td>
                        </tr>
                        [[end for cperms]]-->
                        <tr  id="lastRow" class="notthis">
                            <td colspan="5"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            
            <tr class="notthis">
                <td colspan="2" class="submitrow">
                    <input type="submit" class="k-button submit" value="Save" />
                </td>
            </tr>
        </table>
    </form>
    
</div>
<div id="items-window">
    
</div>
<script >
    $(document).ready(function() {
        
       
        $('#categories').kendoDropDownList();
        var catSelect = $('#categories').data("kendoDropDownList");
        
        
        $('#itemform').myForm({
            showSuccess: true
            
        });
        
        $('#addCatButton').click(function(e) {
            e.preventDefault();
            var sel = catSelect.dataItem();
            var selID = sel.get("value");
            if (selID==0) {
                $('#rolelist-selector td').invalid();
                return;
            }
            if ($('#perm-table').find('#cat_' + selID).length>0) {
                $('#cat_' + selID).invalid();
                return;
            }
            // prevent same category to add
            var templ = $('<tr>' +
                            '<th id="cat_'+ selID +'">'+ sel.get("text") +'<div class="k-descr">subcategories, items and files</div><input type="hidden" name="cerm[29][id][]" value="' + sel.get("value") + '" /></th>'+
                            '<td><input name="cerm[29]['+ selID +'][1]" type="checkbox" value="1" /></td>'+
                            '<td><input name="cerm[29]['+ selID +'][2]" type="checkbox" value="2" /></td>'+
                            '<td><input name="cerm[29]['+ selID +'][4]" type="checkbox" value="4" /></td>'+
                            '<td><input name="cerm[29]['+ selID +'][8]" type="checkbox" value="8" /></td>'+
                            '<td><a href="#" title="Delete" class="remove-composite k-button w-button b-remove" style="padding: 3px 11px !important;" >&nbsp;</a></td>' + 
                        '</tr>');
            templ.insertBefore('#lastRow');    
        });
        
        
        $("#perm-table").on("click","a.remove-composite",function(e) {
            e.preventDefault();
            if (!confirm('Delete?'))
                return;
            $(this).closest('tr').remove();
        });
    });
</script>