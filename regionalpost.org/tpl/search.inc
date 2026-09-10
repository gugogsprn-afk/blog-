<div class="page">
    <div class="section">
        <h1 class="tc finter"><div><span class="icon ic-current lines"></span>{{dict.CURRITEMS}}</div></h1>
        <div>
            {{item.content}}<span class="clear"></span>
        </div>
        <div id="items-list" class="itemlistview columns">
        
        </div><span class="clear"></span>
        <div id="items-pager" class="paging">
            
        </div>
        <span class="clear" style="height: 100px;"></span>
    </div>
</div>
<script id="item-template" type="text/x-kendo-template">
    <div>
        <div class="bitem visible">
            <div class="ohead">
                <i>#: id #</i><span class="itext location">#: tcountry_src# - #: tcountry_dst #</span>
            </div>
            <div class="obody">
                <div class="descr-box">
                    <p><span>#: tcountry_src#,</span> #: city_src #</p>
                    <p><span>#: tcountry_dst#,</span> #: city_dst #</p>
                    <p><span>{{dict.PARCEL}}:</span> #: size # {{dict.MCUBE}}, #: weight # {{dict.KG}}</p>
                    <p><span>{{dict.DELDATE}}:</span> #: pickdate_start_tz#-#: pickdate_end_tz#</p>
                    <p><span>{{dict.CARTYPE}}:</span> #: propsa_text#</p>
                    <p class="timer">#= getTimer(remain) #</p>
                    <span class="clear" style="height: 20px;"></span>
                </div>
                <div class="offer-box">
                    <table>
                        <tbody>#= renderRows(rows,status,remain) #</tbody>
                    </table>
                </div>
                <span class="clear"></span>
            </div>
        </div>
    </div>
</script>
<script >
    
    function getTimer(remain) {
        return remain>0?'<span>{{dict.DEADLINE}}:</span> '+ TimerFormatStr(remain):'{{dict.ERR_OFFERELAPSED}}';
    }
    
    function renderRows(rows,status,remain) {
        var ht = '', i= 0;
        for(i;i<rows.length;i++) {
            ht = ht + '<tr data-id="'+ rows[i].id +'" class="'+ rows[i].marked +'"><th>{{dict.OFFER}} ' + rows[i].rn + '</th><td class="tr">' + rows[i].pricef + ' {{dict.AMD}}</td><td class="ratecell"><span class="rate" title="{{dict.RATE}}">'+ rows[i].avgrate +'</span><span class="cr" title="{{dict.CR}}">'+ rows[i].avgcr +'%</span></td></tr>';
        }
        return ht;
    }
    
    $(function() {
        var dataSource = new kendo.data.DataSource({
            transport: {
                read: {
                    url: "{{curlangp}}search/",
                    dataType: "json",
                    type: "POST",
                    data: {
                       
                    }
                },
                parameterMap: function (data, type) {
                    if (type == "read") {
                        return data;
                    }
                }
            },
            requestStart: function(e) {
                $('#items-list').block({message: '<img src="imgs/loading.gif" />'});
            },
            requestEnd: function(e) {
                $('#items-list').unblock();
            },   
            schema: {
                data: "items",
                errors: "error",
                total: "total",
                model: {
                    id: "id",
                    fields: {}
                }
            },
            pageSize: 14,
            serverPaging: true,
            serverFiltering: false
        });
        
        $('#items-pager').html('').kendoPager({
            info: false,
            previousNext: false,
            dataSource: dataSource,
            selectTemplate: '<li class="curpagenum"><span>#=text#</span></li>',
            linkTemplate: '<li class="pagenum"><a href="{{curlangp}}search?page=#=idx#" data-#=ns#page="#=idx#">#=text#</a></li>'
        });
        
        
        $("#items-list").kendoListView({
            dataSource: dataSource,
            template: kendo.template($("#item-template").html())
        });
        
        
        
    });
    
    
</script>