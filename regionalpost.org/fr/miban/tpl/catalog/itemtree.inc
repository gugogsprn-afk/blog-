<div >
    <div id="splitter-container">
        <div id="splitter">
            <div id="treeview"></div>
            <div id="grid"></div>
        </div>
    </div>
</div>
<script id="treeview-template" type="text/kendo-ui-template">
    <span class="item-#: item.visiblemark #visible">#: item.name #</span>
</script>
<script >
    
    function triggerResize() {
        var sze = $('#items-window').height()-22;
        $("#splitter").css('height', sze);
        $("#splitter").data("kendoSplitter").trigger("resize");
    }
    

    $(document).ready(function() {
      var homogeneous = new kendo.data.HierarchicalDataSource({
        transport: {
            read: {
                url: '?{{current.pageparams}}&response=json&act=categorytree',
                dataType: "json",
                type: "POST"
            }
        },
        schema: {
            errors: "error",
            model: {
                id: "id",
                hasChildren: "Children"
            }
        },
        error: function(e) {
            showError(e.errorThrown, e.errors);
        }
    });
    
    var dataTable = new kendo.data.DataSource({
            transport: {
                read: "?show=product&act=table&response=json&act=table",
                dataType: "json"
            },
            schema: {
                data: "rows",
                errors: "error",
                total: "records",
                model: {
                    id: "id",
                    fields: {
                            id: {type: "string"},
                            name: {type: "string"}
                         }
                     }
                 },
                 error: function(e) {
                     showError(e.errorThrown, e.errors);
                 },
                 pageSize: 12,
                 serverPaging: true,
                 serverSorting: true
             });
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
                 columns: [
                     {field: "id", title: "N", width: "60px"},
                     {field: "name", title: "Name", template: '<a href="1" class="item-select" title="edit item">#: name # </a>'},
                     {field: "manufact", title: "Manufacturer", width: "200px"},
                     {field: "pos", title: "pos.", width: "60px" }
                 ]
             });
    
    
     
    $("#treeview").kendoTreeView({
        dataSource: homogeneous,
        dataTextField: "name",
        dragAndDrop: false,
        template: kendo.template($("#treeview-template").html()),
        select: function (event)
        {
            var dataItem = this.dataItem(event.node);
            dataTable.transport.options.read.url = "?show=product&act=table&response=json&parent_id="+ dataItem.id; 
            var grid = $("#grid").data("kendoGrid");
            grid.dataSource.read();
            //dataItem.loaded(false); reload node
            //console.log("Selected node with id=" + dataItem.id);
        }
    });
    
    
    
    
    $("#splitter").kendoSplitter({
        panes: [
            { collapsible: true, min: "150px" , size: "250px"},
            { collapsible: false, min: "200px" }
        ]
    });      
    
    
    
    triggerResize();
    
    
    
    });
</script>