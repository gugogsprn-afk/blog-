<div class="page-title">
    <img src="imgs/genericapp.png" />Administrators <span class="last">{{if act(=add)}}Add new administrator{{else act}}Password change "{{item.name}}"{{end if act}}</span>
    <div class="page-toolbar">
        <a href="?show=adminmembers" id="apptoolbar-add" class="k-button w-button" title="Back" style="background-image:url('imgs/left.png');">&nbsp;</a>
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
        <input type="hidden" name="item[role_id]" value="1" />
        <table class="fieldset">
            {{if act(=add)}}
            <tr>
                <th>E-mail</th>
                <td id="member-usr_name" ><input type="text" name="item[usr_name]" class="k-input" value="{{item.usr_name}}" style="width: 300px;" /></td>
            </tr>
            {{else act}}
            <tr>
                <th>E-mail</th>
                <td>{{item.usr_name}}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td><input type="text" name="item[name]" class="k-input" value="{{item.name}}" style="width: 300px;" /></td>
            </tr>
            {{end if act}}
            <tr>
                <th>Description</th>
                <td><textarea name="item[descr]" rows="2" cols="30" style="width:100%;height:30px" class="k-input">{{item.descr}}</textarea></td>
            </tr>
            <!--
            <tr>
                <th>Role</th>
                <td id="member-role_id">
                    <select  name="item[role_id]" class="k-select">
                        <option value="0">--Select--</option>
                        {{for roles as role}}
                        <option value="{{role.id}}" {{role.selected}}>{{role.name}}</option>
                        {{end for roles}}
                    </select>
                </td>
            </tr>
            -->
            <tr>
                <th>Temporary access</th>
                <td>
                    <input type="checkbox" id="validDateEnable" name="item[temporary]" value="1" {{if item.validdate}} checked="checked" {{end if item.validdate}} />
                    <span id="validDateWrap"><input type="text" name="item[validdate]" id="member-validdate" class="k-datepicker" value="{{item.validdate}}" /></span>
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
<div id="window"></div>
<div id="TreeWindow"></div>
<script >
    
     
    $('.k-select').kendoDropDownList();
     $('#member-validdate').kendoDatePicker();
     var validDate = $('#member-validdate').data('kendoDatePicker');
     validDate.enable($('#validDateEnable')[0].checked);
     
     $('#validDateEnable').change(function(){
         validDate.enable(this.checked);
     });
    
    $('#itemform').myForm({
            showSuccess: true
            
        });
        
   
</script>