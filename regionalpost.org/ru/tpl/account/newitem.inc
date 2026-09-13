<div id="newitem">
    <form method="POST" action="{{curlangp}}account/newitem.html" id="newitem-form">
        <input type="hidden" name="cmd" value="1" />
        <input type="hidden" name="th" value="{{th}}" />
        <div class="fieldset table">
            <div class="tr"> 
                <div class="td" id="item-country_src">
                    <label>{{dict.COUNTRYSRC}} *</label>
                    <select name="item[country_src]" class="countrylist" style="width: 100%;"></select>
                </div>
                <div class="td" id="item-city_src">
                    <label>{{dict.CITYSRC}} *</label>
                    <input type="text" name="item[city_src]" class="m-input" />
                </div>
            </div>
            <div class="tr"> 
                <div class="td" id="item-country_dst">
                    <label>{{dict.COUNTRYDST}} *</label>
                    <select name="item[country_dst]" class="countrylist" style="width: 100%;"></select>
                </div>
                <div class="td" id="item-city_dst">
                    <label>{{dict.CITYDST}} *</label>
                    <input type="text" name="item[city_dst]" class="m-input" />
                </div>
            </div>
            <div class="tr"> 
                <div class="td" id="item-pickdate" style="vertical-align: top;">
                    <label>{{dict.PICKDATE}} *</label>
                    <input type="text" id="item-pickdate_start" class="m-datepicker" style="width: 45%;"/> -
                    <input type="text" id="item-pickdate_end" class="m-datepicker" style="width: 45%;"/>
                </div>
                <div class="td" id="item-propsa">
                    <label>{{dict.CARTYPE}} *</label>
                    <select name="item[propsa]" class="propsalist" style="width: 100%;"></select>
                    <div id="item-othercar" style="display: none;">
                        <input type="text" name="item[propsa_text]" class="m-input" style="width: 100%;" />
                    </div>
                </div>
            </div>
            <div class="tr"> 
                <div class="td" id="item-size">
                    <label>{{dict.SIZE}} ({{dict.MCUBE}}) *</label>
                    <input type="text" name="item[size]" class="m-decimal" style="width: 100%;"/>
                </div>
                <div class="td" id="item-weight">
                    <label>{{dict.WEIGHT}} ({{dict.KG}})*</label>
                    <input type="text" name="item[weight]" class="m-decimal" style="width: 100%;"/>
                </div>
            </div>
            <div class="tr"> 
                <div class="td">
                    <label>{{dict.PDESCR}} *</label>
                    <textarea name="item[descr]"  id="item-descr"  class="m-input" style="height: 80px;width: 100%;"></textarea>
                    
                </div>
                <div class="td" id="item-dateend" style="vertical-align: top;">
                    <label>{{dict.OFFERDATE}} *</label>
                    <input type="text" id="item-dateend-picker" class="m-datepicker" style="width: 100%;"/>
                </div>
            </div>
            <div class="tr"> 
                <div class="td">
                    <label>{{dict.NOTES}}</label>
                    <div class="k-descr">{{dict.PARSELEX}}</div>
                    <textarea name="item[notes]" class="m-input" style="height: 80px;width: 100%;"></textarea>
                </div>
                <div class="td submitrow" id="item-save">
                    <div id="parc-error" class="errobox">
                        
                    </div>
                    <input type="submit" class="flatbutton" value="{{dict.SAVE}}" />
                </div>
            </div>
        </div>
    </form>
</div>
<script >
    
    var srcs = {{sources}};
    
    $('.m-decimal').kendoNumericTextBox({ min: 0 });
    $('.m-integer').kendoNumericTextBox({
        format: 'n0',
        decimals: 0,
        min: 0
    });

    $('.m-datepicker').kendoDatePicker({format: "dd.MM.yyyy"});

   
    $('.countrylist').kendoDropDownList({
        dataSource: srcs.country,
        dataTextField: "name",
        dataValueField: "itemvalue",
        optionLabel: "{{dict.SELECT}}"
    });
    
    $('.propsalist').kendoDropDownList({
        dataSource: srcs.propsa,
        dataTextField: "name",
        dataValueField: "itemvalue",
        optionLabel: "{{dict.SELECT}}",
        change: function() {
            if (parseInt(this.value())==8) {
                $('#item-othercar').css('display','');
            } else {
                $('#item-othercar').css('display','none');
            }
        }
    });
    
    
    $('#newitem-form').myForm({
        onSuccess: function(data,status) { 
            console.log(data);
        },
        beforeSubmit: function(arr, form, options) {
            $('#parc-error').html('');
            
            var date_start = useTimezone($('#item-pickdate_start').val() + ' 00:00:00');
            var date_end = useTimezone($('#item-pickdate_end').val() + ' 23:59:59');
            
            //var date_start = $('#item-pickdate_start').val();
            //var date_end = $('#item-pickdate_end').val();
            
            var dateend = useTimezone($('#item-dateend-picker').val() + ' 23:59:59');
            
            
            console.log($('#item-pickdate_start').val() + ' 00:00:00');
            console.log($('#item-pickdate_end').val() + ' 23:59:59');
            console.log($('#item-dateend-picker').val() + ' 23:59:59');
            
            arr.push({name:'item[pickdate_start]', value: date_start });
            arr.push({name:'item[pickdate_end]', value: date_end });
            arr.push({name:'item[dateend]', value: dateend });
            
            console.log(arr);
        
            return true; 
        },
        onError: function(error,responseText) { 
                if (error.invalids) {
                    $('#'+error.invalids[0]).invalid();
                } else {
                    $('#parc-error').html(error.error);
                }
                return false; 
            },
        wrapper: '#newitem'
    });
    
    
</script>