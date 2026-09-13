<div class="page-title">
    <img src="imgs/genericapp.png" />Pages <span class="last">{{current.title}}</span>
    <div class="page-toolbar">
        {{if allowedit}}<a href="?{{current.pageparams}}&voider=form&act=sadd&block_id={{block_id}}{{parentid}}" id="apptoolbar-add" class="k-button w-button" title="add item" style="background-image:url('imgs/mainpage/simple_plus.png');">&nbsp;</a>{{end if allowedit}}
    </div>
</div>
<div id="page-content-wrapper">
    <div id="grid"></div>
    
    
</div>
<script id="position-template" type="text/x-kendo-template">
  # if (data.pos>data.minpos) { #  
  <a href="id=#: id #&voider=move&direction=toup" class="k-button w-button b-up a-action" title="move up from position #: pos #">&nbsp;</a>
  # };
  if (data.pos<data.maxpos) { #  
  <a href="id=#: id #&voider=move&direction=todown" class="k-button w-button b-down a-action" title="move down from position #: pos #">&nbsp;</a>
  # }; #
</script>
<script type="text/x-kendo-template" id="dettemplate">
    <div class="detail-wrapper">
        <div class="detail-header">
            <a href="\#" class="add-button underline" title="add item" >Add page</a><span>&nbsp;</span>
        </div>
        <div class="detail-grid"></div>
    </div>
</script>
<script >
    $(document).ready(function() {
        
    var mainUrl = "?{{current.pageparams}}&response=json&act=table";    
    var dsTpl = {
        schema: {
            data: "rows",
            errors: "error",
            total: "records",
            model: {
                id: "id",
                fields: {
                        id: {type: "string"},
                        name: {type: "string"},
                        childcount: {type: "number"},
                        maxpos: {type: "number"},
                        minpos: {type: "number"},
                        pos: {type: "number"},
                        childallowadd: {type: "boolean"},
                        childallowmove: {type: "boolean"}{{if showdate}},
                        date_object: {type: "date"}{{end if showdate}}
                     }
                 }
             },
             error: KendoErrorHandler,
             pageSize: 20,
             serverPaging: true,
            
             serverSorting: true
    };
    
    
    var dataTable = new kendo.data.DataSource($.extend(true,{
        transport: {
            read: mainUrl + "&block_id={{block_id}}{{if parent_id}}&parent_id={{parent_id}}{{end if parent_id}}",
            dataType: "json"
        },
        sort: {{ordering}}
    },dsTpl));
    
    
   

    var grid = $("#grid").kendoGrid({
                 dataSource: dataTable,
                 filterable: false,
                 groupable: false,
                 sortable: true,
                 reorderable: true,
                 resizable: true,
                 scrollable: false,
                 pageable: true,
                 selectable: "row",
                 rowTemplate: "",
                 detailInit: detailInit,
                 detailTemplate: kendo.template($("#dettemplate").html()),
                 dataBound: function(e) {
                    var grid = this;
                    grid.tbody.find("tr.k-master-row").each(function () {
                        var dt = grid.dataItem($(this));
                        if (dt.childcount==0 && dt.childallowadd!==true) {
                            $(this).find("td.k-hierarchy-cell").html('');
                        }
                    })
                 },
                 columns: [
                 
                     {field: "visible", title: "&nbsp;", width: "50px",template: '<a href="id=#: id #&voider=visible&value=#: visible #" class="k-button w-button b-visible#: visible # a-action" title="toggle visibility">&nbsp;</a>'}, 
                 
                     {field: "id", title: "N", width: "60px"},
                     {field: "name", title: "Page", template: '<a href="?{{current.pageparams}}&id=#: id #&voider=form&act=sedit" title="edit item">#: name # </a>'}
                     {{if showdate}}
                     ,{field: "date_object", title: "Date", width: "97px", template: '#= kendo.toString(date_object, "dd.MM.yyyy") #'}
                     {{end if showdate}}
                     {{if allowmove}}
                     ,{field: "pos", title: "pos.", width: "97px",template: kendo.template($("#position-template").html()), attributes: {"class": "center"}}
                     {{end if allowmove}}
                    {{if allowedit}} 
                    ,{ command: [
                             { name: "remove",text: "&nbsp;", click: removeItem, className: "w-button b-remove" }
                             ], title: "Delete", width: "50px" }
                     {{end if allowedit}}       
                 ]
             });
             
            function detailInit(e) {
                var nstdt = new kendo.data.DataSource($.extend(true,{
                    transport: {
                        read: mainUrl + "&parent_id="+e.data.id,
                        dataType: "json"
                    },
                    sort: { field: "pos", dir: "asc" }
                },dsTpl));
               
               console.log(e.data);
                
                var cols = [];
                
                if (e.data.childallowadd===true) {
                    e.detailRow.find(".detail-header > .add-button").attr("href","?{{current.pageparams}}&voider=form&act=sadd&block_id=" + e.data.childblock + '&parent_id=' + e.data.id);
                    cols.push({field: "visible", title: "&nbsp;", width: "50px",template: '<a href="id=#: id #&voider=visible&value=#: visible #" class="k-button w-button b-visible#: visible # a-action" title="toggle visibility">&nbsp;</a>'});
                } else {
                    e.detailRow.find(".detail-header > .add-button").remove();
                }
                
                cols.push({field: "id", title: "N", width: "60px"});
                cols.push({field: "name", title: "Page", template: '<a href="?{{current.pageparams}}&id=#: id #&voider=form&act=sedit" title="edit item">#: name # </a>'});
                
                if (e.data.childallowmove===true) {
                    cols.push({field: "pos", title: "pos.", width: "97px",template: kendo.template($("#position-template").html()), attributes: {"class": "center"}});
                }
                if (e.data.childallowadd===true) {
                    cols.push({ command: [
                             { name: "removesub",text: "&nbsp;", click: removeSubItem, className: "w-button b-remove" }
                             ], title: "Delete", width: "50px" });
                }
                
                e.detailRow.find(".detail-grid").kendoGrid({
                    dataSource: nstdt,
                    filterable: false,
                    groupable: false,
                    sortable: true,
                    reorderable: true,
                    resizable: true,
                    scrollable: false,
                    pageable: true,
                    selectable: "row",
                    rowTemplate: "",
                    columns: cols
                });
                
            }
             
             
            
            function removeItem(e) {
                e.preventDefault();
                if (!confirm('Delete?')) return false;
                // json post id= result response.result == 'ok'
                var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
                
                postData('id=' + dataItem.id + '&voider=delete',this);
            }
            
            
            function removeSubItem(e) {
                e.preventDefault();
                if (!confirm('Delete?')) return false;
                // json post id= result response.result == 'ok'
                var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
                
                postData('id=' + dataItem.id + '&voider=delete',this);
            }
            
            
            $('#page-content-wrapper').on('click','.a-action',function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var grid = $(this).closest('.k-grid').data("kendoGrid");
                postData(url,grid);
            });
            
            function postData(params,sender) {
                makeRequest({
                    data: '{{current.pageparams}}&' + params,
                    onSuccess: function(data,status) { 
                        
                        sender.dataSource.read();
                    }
                });
            }
            
    });
</script>