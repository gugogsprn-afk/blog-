<div style="width: 570px; height: 380px; position: relative;">
    <div id="treeview" style="height: 340px;"></div>
    <div style="text-align: right; position: absolute; right: 0; bottom: 10px;">
        <a href="#" class="k-button" id="addurl">Add</a>
    </div>
</div>
<script >

       $(document).ready(function() {
        var homogeneous = new kendo.data.HierarchicalDataSource({
            transport: {
                read: {
                    url: '?{{current.pageparams}}&response=json&voider=showmap',
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
            error: KendoErrorHandler
        });

        
        $("#treeview").kendoTreeView({
            dataSource: homogeneous,
            dataTextField: "alias",
            dragAndDrop: false,
            template: '<span>#: item.parent_id #. #: item.displaytext #</span>',
            select: function(event)
            {
                /*
                 var dataItem = this.dataItem(event.node);
                 dataTable.transport.options.read.url = "?show=product&act=table&response=json&parent_id="+ dataItem.id; 
                 var grid = $("#grid").data("kendoGrid");
                 grid.dataSource.read();
                 */

            }
        });

    $("#treeview").disableSelection();
        


    });
</script>