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
                <label>{{dict.GCOUNTRY}} *<select id="member-country" name="member[country]" data-role="dropdownlist" data-text-field="name" data-value-field="itemvalue" data-bind="source: sources.country, value: country" style="width: 100%;"></select></label>
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
        <div class="tr">
            <div class="td">
                <label>{{dict.WORKHOURS}} *</label>
                <textarea name="member[hours]" class="m-input" style="height: 80px;width: 100%;" data-bind="value: hours"></textarea>
            </div>
            <div class="td" style="vertical-align: top;">
                <label>{{dict.MCARS}} *
                <span class="db" id="member-propsa">
                    <select class="multiselect" multiple="multiple" data-rawvalue="propsa" data-rawname="member[propsa]" data-rawsource="propsa"></select>
                </span>
            </div>
        </div>
        <div class="tr">
            <div class="td">
                <label>{{dict.CONTACTMAIL}} *<input type="text" id="member-email" name="member[email]" class="m-input" data-bind="value: email"/></label>
            </div>
            <div class="td">
                
            </div>
        </div>
    </div>
    <div id="country-list-wrap">
        <label>{{dict.MDIR}} *
            <a href="#" class="flatbutton" id="country-list-add" data-bind="events: {click: addItem}">&nbsp;</a>
            <span class="clear"></span>
        </label>
        <div id="country-list" data-template="direct-template" data-bind="source: directions">

        </div>
    </div>
    <div class="i-block"></div>
    {{if isedit}}
    <input type="submit" class="flatbutton" value="{{dict.SAVE}}"></a>
    {{else isedit}}
    <input type="submit" class="flatbutton" value="{{dict.FINISHREG}}"></a>
    {{end if isedit}}
</form>
</div>
<script id="direct-template" type="text/x-kendo-template">
    <div>
        <div>
            <select data-role="dropdownlist" data-text-field="name" data-value-field="itemvalue" data-bind="source: sources.countryadv, value: dir1" style="width: 188px;"></select>
            - <select data-role="dropdownlist" data-text-field="name" data-value-field="itemvalue" data-bind="source: sources.countryadv, value: dir2" style="width: 188px;"></select>
            <a href="\#" title="{{dict.REMOVE}}" class="flatbutton btn-delete" data-bind="events: {click: removeItem}">&nbsp;</a>
        </div>
    </div>
</script>
<script >
    $(document).ready(function() {  
        
        var modelBase = {{model}};
        modelBase.langs = modelBase.langs || [];
        modelBase.sources.countryadv = modelBase.sources.country.slice();
        modelBase.sources.countryadv.unshift({ name: '-- {{dict.ANY}} --', itemvalue: '1000' });
        var model = $.extend( true, modelBase, {
            addItem: function(e) {
                e.preventDefault();
                this.get("directions").push({
                    dir1: '1000',
                    dir2: '1000'
                });
            },
            removeItem: function(e) {
                e.preventDefault();
                var its = this.get("directions");
                var index = its.indexOf(e.data);
                its.splice(index, 1);
            },
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
                var dirs = viewModel.get("directions") || [];
                for(var idx=0;idx<dirs.length;idx++) {
                    arr.push({name:'member[directions]['+ idx +']', value: dirs[idx].dir1 + '-' + dirs[idx].dir2 });
                }
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