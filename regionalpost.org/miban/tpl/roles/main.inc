<div class="page-title">
    <img src="imgs/genericapp.png" />Roles and permissions
    <div class="page-toolbar">
        <a href="?{{current.pageparams}}&voider=form&act=sadd" id="apptoolbar-add" class="k-button w-button" title="add item" style="background-image:url('imgs/mainpage/simple_plus.png');">&nbsp;</a>
    </div>
</div>
<div id="page-content-wrapper">
    <div id="grid"></div>
</div>

<script >
    $(document).ready(function() {
     var dataTable = new kendo.data.DataSource({
            transport: {
                read: "?show=roles&response=json",
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
                 error: KendoErrorHandler,
                 pageSize: 15,
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
                     {field: "name", title: "Name", width: "250px", template: '<a href="?show=roles&voider=form&act=sedit&id=#: id #" title="Edit">#: name # </a>'},
                     {field: "descr", title: "Description"},
                     { command: [
                             { name: "remove",text: "&nbsp;", click: removeItem, className: "w-button b-remove" }
                             ], title: "Delete", width: "50px" }
                 ]
             });
            function removeItem(e) {
                e.preventDefault();
                if (!confirm('Delete?')) return false;
                // json post id= result response.result == 'ok'
                var dataItem = this.dataItem($(e.currentTarget).closest("tr")).toJSON();
                makeRequest({
                    data: { show: 'roles', voider: 'delete', id: dataItem.id },
                    onSuccess: function(data) {
                        var grid = $("#grid").data("kendoGrid");
                         grid.dataSource.read();
                        return false;
                    }
                });
            }

    });
</script>