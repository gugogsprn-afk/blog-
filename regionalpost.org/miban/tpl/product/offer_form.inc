<form method="post" action="index.php" id="offerform" enctype="multipart/form-data">
    {{for current.queryparams as param}}
    <input type="hidden" name="{{param.key}}" value="{{param.value}}" />
    {{end for current.queryparams}}
    <input type="hidden" name="id" value="{{item.id}}" />
    <input type="hidden" name="voider" id="action" value="{{act}}"/>
    <input type="hidden" name="item[pos]" value="{{item.pos}}"/>
    <input type="hidden" name="item[parent_id]" value="{{parent.id}}" />
    <input type="hidden" name="item[item_id]" value="{{parent.parent_id}}" />
    <table class="fieldset">
        <tr>
            <th>Дата предложения</th>
            <td>
                <input type="date" name="item[date_from]" value="{{item.date_from}}" class="k-datepicker" /> -
                <input type="date" name="item[date_to]" value="{{item.date_to}}" class="k-datepicker" />
            </td>
        </tr>
        <tr>
            <th>Количество гостей</th>
            <td><input type="number" name="item[people]" value="{{item.people}}" class="k-numeric"  /></td>
        </tr>
        <tr>
            <th>Условия</th>
            <td>
                <select id="propsb" multiple="multiple" name="propsb[]">{{propsb}}</select>
            </td>
        </tr>
        <tr>
            <th>Цена</th>
            <td>
                <input type="number" name="item[oprice]" class="k-numeric" value="{{item.oprice}}" />
            </td>
        </tr>
        <tr>
            <th>Новая цена</th>
            <td>
                <input type="number" name="item[nprice]" class="k-numeric" value="{{item.nprice}}" />
            </td>
        </tr>
        <tr>
            <td colspan="2" class="submitrow">
                <input type="submit" class="k-button submit" value="Сохранить" />
            </td>
        </tr>
    </table>
</form>
<script >
    $("#propsb").kendoMultiSelect({
            value: [{{propsbvalues}}]
        });
    $('.k-datepicker').kendoDatePicker({format: "dd.MM.yyyy"});
    $('.k-numeric').kendoNumericTextBox();
    
    
</script>