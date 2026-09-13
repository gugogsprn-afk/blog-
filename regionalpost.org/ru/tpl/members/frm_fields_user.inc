<div id="frm-fields-wrap">
{{if isedit}}
<form method="POST" action="{{curlang}}/members/changedata.html" id="frm_fields"  class="fields">
{{else isedit}}
<form method="POST" action="{{curlang}}/members/activate.html" id="frm_fields"  class="fields">
    <input type="hidden" name="h" value="{{hash}}" />
    {{if showpass}}
    <label>{{dict.NEWPASS}} <input type="password" id="member-pass" name="passone" class="m-input" /></label>
    <label>{{dict.REPPASS}} <input type="password" name="passtwo" class="m-input" /></label>
    {{end if showpass}}
{{end if isedit}}
    <input type="hidden" name="cmd" value="1" />
    
    <div class="table fieldset">
        <div class="tr">
            <div class="td">
                <label>{{dict.COMPNAME}} *<input type="text" id="member-name" name="member[name]" class="m-input" data-bind="value: name" /></label>
            </div>
            <div class="td">
                <label>{{dict.HVHH}} *<input type="text" id="member-hvhh" name="member[hvhh]" class="m-input" data-bind="value: hvhh" /></label>
            </div>
        </div>
        <div class="tr">
            <div class="td">
                <input type="hidden" name="member[countryraw]" value="" data-bind="value: getcountryraw" />
                <label>{{dict.GCOUNTRY}} *<select id="member-country" name="member[country]" data-role="dropdownlist"  data-text-field="name" data-value-field="itemvalue" data-bind="source: sources.country, value: country" style="width: 100%;"></select></label>
            </div>
            <div class="td">
                <label>{{dict.ADDRESS}} *<input type="text" id="member-address" name="member[address]" class="m-input" data-bind="value: address"/></label>
            </div>
        </div>
        <div class="tr">
            <div class="td">
                <label class="phonewrap">{{dict.PHONE}} *<input type="text" id="member-phone" name="member[phone]" class="m-input" data-bind="value: phone" /></label>
            </div>
            <div class="td">
                <label>{{dict.LANGB}} *
                <span class="db" id="member-langs-wrap">
                    <select class="multiselect" multiple="multiple" data-rawvalue="langs" data-rawname="member[langs]" data-rawsource="langs"></select>
                </span>
            </div>
        </div>
    </div>
    
    <div class="i-block"></div>
    {{if isedit}}
    <input type="submit" class="flatbutton" value="{{dict.SAVE}}"></a>
    {{else isedit}}
    <input type="submit" class="flatbutton" value="{{dict.FINISHREG}}" ></a>
    {{end if isedit}}
</form>
</div>

<script >
    $(document).ready(function() {  
        
        var modelBase = {{model}};
        modelBase.langs = modelBase.langs || [];
        
        var model = $.extend( true, modelBase, {
            langs: 3,
            getcountryraw: function() {
                var cntr = this.get('country');
                var cntrText = '';
                for(var idx = 0;idx<modelBase.sources.country.length;idx++) {
                    if (modelBase.sources.country[idx].itemvalue == cntr) {
                        cntrText = modelBase.sources.country[idx].name;
                        break;
                   }
                }
                return cntrText;
                //var text = $.grep()
            }
        });
        var viewModel = kendo.observable(model);
        kendo.bind($('#frm-fields-wrap'), viewModel);
        
        $('#frm_fields .multiselect').each(function() {
            var curVle = modelBase[$(this).data("rawvalue")] || 0;
            var srcName= $(this).data("rawsource");
            var curVal = [];
            if (modelBase.sources[srcName]!==undefined) {
                $.each(modelBase.sources[srcName],function() {
                    if ((curVle & this.itemvalue) === this.itemvalue ) {
                        curVal.push(this.itemvalue);
                    }
                });
                $(this).kendoMultiSelect({
                    dataSource: { data: modelBase.sources[srcName] },
                    userInput: true,
                    dataValueField: 'itemvalue',
                    dataTextField: 'name',
                    value: curVal
                });
            }
        });
        
        var frm = $('#frm_fields');
        frm.myForm({
            wrapper: frm.closest('div'),
            beforeSubmit: function(arr, $form, options) { 
                frm.find('.i-block').html('');
                
                $('#frm_fields select.multiselect').each(function() {
                    var mlt = $(this).data("kendoMultiSelect");
                    var nme = $(this).data('rawname');
                    var vle = mlt.value();
                    var ravVle = 0;
                    if (vle.length>0) {
                        $.each(vle,function() {
                            ravVle = ravVle | this;
                        });
                    }
                    arr.push({name:nme, value: ravVle });
                });
                return true;
            },
            onError: function(error,responseText) { 
                if (error.invalids) {
                    $('#'+error.invalids[0]).invalid();
                    return;
                } else {
                    frm.find('.i-block').html(error.error);
                }
                
                return false;
            }
        });
    });
</script>