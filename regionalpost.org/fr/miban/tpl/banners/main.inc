<div class="page-title">
    <img src="imgs/genericapp.png" />Banners
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
                read: "?{{current.pageparams}}&response=json&act=table",
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
            pageSize: 20,
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
                {field: "name", title: "Name", template: '<a href="?{{current.pageparams}}&id=#: id #&voider=form&act=sedit" title="edit item">#: name # </a>'},
                {field: "daysleft", title: "Days left", width: "80px"},
                {field: "validdate", title: "to", width: "110px"},
                {field: "priority", title: "Priority", width: "80px"},
                {command: [
                        {name: "remove", text: "&nbsp;", click: removeItem, className: "w-button b-remove"}
                    ], title: "Remove", width: "50px"}
            ]
        });
        
        function removeItem(e) {
            e.preventDefault();
            if (!confirm('Remove?')) return false;
            // json post id= result response.result == 'ok'
            var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
            postData('id=' + dataItem.id + '&voider=delete&block_id=' +dataItem.block_id);
        }
        
        $('#page-content-wrapper').on('click','.a-action',function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            postData(url);
        });


        function postData(params) {
            makeRequest({
                data: '{{current.pageparams}}&' + params,
                onSuccess: function(data,status) { 
                    var grid = $("#grid").data("kendoGrid");
                    grid.dataSource.read();
                }
            });
        }


       
    });
</script>