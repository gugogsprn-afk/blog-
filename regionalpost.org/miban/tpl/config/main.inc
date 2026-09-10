<div class="page-title">
    <img src="imgs/genericapp.png" />Site <span>Settings</span> <span class="last">{{alias}}</span>
    <div class="page-toolbar">
        
    </div>
</div>
<div id="page-content-wrapper" data-ddd="{{langs.0.id}} {{langs.1.id}} {{langs.2.id}}">
    {{if items}}
    <table class="fieldset">
    {{for items as item}}
    <tr>
        <th>{{item.title}}</th>
        <td>
            <a href="#{{item.id}}" title="{{item.description}}" class="edititem">{{item.valuetext}}&nbsp;</a> 
            <div class="editorwrap" style="display: none;">
                <input type="hidden" class="field_id" value="{{item.id}}" />
                <input type="hidden" class="field_value" value="{{item.value}}" />
                <input type="hidden" class="field_type" value="{{item.type}}" />
                {{if item.type(=text)}}
                <input type="text" name="field{{item.id}}" value="{{item.value}}" class="k-input itemeditor" /> 
                {{end if item.type}}
                {{if item.type(=number)}}
                <input type="text" name="field{{item.id}}" value="{{item.value}}" class="k-numeric itemeditor" /> 
                {{end if item.type}}
                {{if item.type(=textarea)}}
                <textArea name="field{{item.id}}" class="k-textbox itemeditor" rows="2" >{{item.value}}</textArea>
                {{end if item.type}}
                {{if item.type(=trans)}}
                <div>
                    <span class="k-descr">Translation ({{langs.0.id}})</span>
                    <textArea name="field{{item.id}}" class="k-textbox itemeditor lang0" style="width: 500px;" rows="3" ></textArea>
                </div>
                <div>
                    <span class="k-descr">Translation ({{langs.1.id}})</span>
                    <textArea name="field{{item.id}}" class="k-textbox itemeditor lang1" style="width: 500px;" rows="3" ></textArea>
                </div>
                <div>
                    <span class="k-descr">Translation ({{langs.2.id}})</span>
                    <textArea name="field{{item.id}}" class="k-textbox itemeditor lang2" style="width: 500px;" rows="3" ></textArea>
                </div>
                {{end if item.type}}
                {{if item.type(=select)}}
                    {{if item.cases}}
                    <select name="field{{item.id}}" class="dropselect itemeditor" style="width: 300px;">
                        {{for item.cases as case}}
                        <option value="{{case.value}}">{{case.text}}</option>
                        {{end for item.cases}}
                    </select>
                    {{end if item.cases}}
                {{end if item.type}}
                {{if item.type(=image)}}
                    {{if item.cases}}
                    <span>Image resize method</span>&nbsp;&nbsp;
                    <select name="field{{item.id}}" class="imagemodeselect itemeditor"  style="width: 300px;">
                        {{for item.cases as case}}
                        <option value="{{case.value}}">{{case.text}}</option>
                        {{end for item.cases}}
                    </select>
                    {{end if item.cases}}
                    &nbsp;&nbsp;<span>Size</span>&nbsp;&nbsp;
                    <input type="text" name="field{{id}}" value="" class="k-input imagesize itemeditor short" />
                {{end if item.type}}
                
                {{if item.type(=file)}}
                    {{if item.cases}}
                    <select name="field{{item.id}}" class="dropselect itemeditor" style="width: 400px;">
                        {{for item.cases as case}}
                        <option value="{{case.value}}">{{case.text}}</option>
                        {{end for item.cases}}
                    </select>
                    {{end if item.cases}}
                {{end if item.type}}
            </div>
        </td>
    </tr>
    {{end for items}}
    </table>
    {{end if items}}
</div>
<script >
    $(document).ready(function() {
        
    
    $('.dropselect').kendoDropDownList();
    $('.k-numeric').kendoNumericTextBox();
            
    $('.imagemodeselect').kendoDropDownList({
        template : '<div><img src="imgs/${ data.value }.png" style="float:left;margin-right:5px;" />&nbsp;${ data.text }</div><div class="clear"></div>'
    });
    
    var grid = $("table.fieldset");
    
    var PageWrapper = $('#page-content-wrapper');
    
    var btn_save = $('<input type="button" id="fieldsave" value="Save" class="k-button" />').click( function(e) {
        e.preventDefault();
        PageWrapper.block({message: '<img src="imgs/loading.gif" />'});
        var cont = $(this).parent('.editorwrap');
        var c_td = cont.parent('td');
        var newVal =cont.find('.itemeditor').val();
        var ItemType = c_td.find(".field_type").val();
        var dropdownlist = null;
        if(ItemType==='image' || ItemType==='select'  || ItemType==='file' ) {
            dropdownlist = cont.find("select.itemeditor").data("kendoDropDownList");
            newVal = dropdownlist.value();
            if (ItemType==='image') {
                newVal =newVal + ';' + cont.find('.imagesize').val();
            }
        }
        if(ItemType==='number') {
            dropdownlist = $(cont.find("input.itemeditor")[1]).data("kendoNumericTextBox");
            newVal = dropdownlist.value();
        }
        if(ItemType==='trans') {
            newVal = cont.find(".itemeditor").map(function(){return $(this).val();}).get().join("|");
        }
        
        var fid = cont.find('.field_id').val();
        $('.error').remove();  
        $.post("index.php?show=config&parent_id={{parent_id}}&response=json", { act:"save", id: fid, value: newVal },
           function(data) {
                PageWrapper.unblock();
                if (data.error) {
                   showError('Error',data.error); 
                   return;
                } 
                cont.find('.field_value').val(newVal);
                if(ItemType=="select" || ItemType=="file") {
                    c_td.find(".edititem").html(dropdownlist.text()).css("display","block");
                } else if(ItemType=="image") {
                    var hvals = newVal.toString().split(';');
                    c_td.find(".edititem").html(dropdownlist.text() + "&nbsp;&nbsp;&nbsp; " + hvals[1] + " px").css("display","block");
                } else if(ItemType=="trans") {
                    var hvals = newVal.split('|').join('<br/>');
                    c_td.find(".edititem").html(hvals).css("display","block");
                } else {
                    c_td.find(".edititem").html(newVal).css("display","block");
                }
                cont.css("display","none"); 
           },'json').error(
           function(xhr, status, error)  { 
                PageWrapper.unblock();
                showError('Error',error + '<div style="color: black !important;">' + xhr.responseText + '</div>');
           });
    });
    
    var btn_cancel = $('<input type="button" id="fieldcancel" value="Cancel" class="k-button" />').click(function(e) {
        e.preventDefault();
        var cont = $(this).parent('.editorwrap');
        var c_td = cont.parent('td');
        c_td.find(".edititem").css("display","block");
        cont.css("display","none");
    });
    
    $('a.edititem').click(function(e) {
        e.preventDefault();
        grid.find(".editorwrap").css("display","none");
        grid.find(".edititem").css("display","block");
        var cont = $(this).parent('td').find(".editorwrap").css("display","block");
        cont.append(btn_save);
        cont.append('&nbsp;');
        cont.append(btn_cancel);
        if(cont.find(".field_type").val()=='select' || cont.find(".field_type").val()=='file') {
            var dropdownlist = cont.find("select.itemeditor").data("kendoDropDownList");
            dropdownlist.value(cont.find(".field_value").val());
            
        } else if(cont.find(".field_type").val()=='image') {
            var dropdownlist = cont.find("select.itemeditor").data("kendoDropDownList");
            var vals = cont.find(".field_value").val().toString().split(";");
            dropdownlist.value(vals[0]);
            cont.find('input.itemeditor').val(vals[1]);
        } else if(cont.find(".field_type").val()=='trans') {
            var vals = cont.find(".field_value").val().toString().split("|");
            cont.find(".itemeditor.lang0").val(vals[0]);
            cont.find(".itemeditor.lang1").val(vals[1]);
            cont.find(".itemeditor.lang2").val(vals[2]);
        } else {
            cont.find(".itemeditor").val(cont.find(".field_value").val()); 
        }
        $(this).css("display","none");
    });
    });
</script>