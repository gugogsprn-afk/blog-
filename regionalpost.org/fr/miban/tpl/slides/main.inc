<div class="page-title">
    <img src="imgs/genericapp.png" />Slides 
    <div class="page-toolbar">
        <a href="?{{current.pageparams}}&voider=form&act=sadd" id="apptoolbar-add" class="k-button w-button" title="add item" style="background-image:url('imgs/mainpage/simple_plus.png');">&nbsp;</a>
    </div>
</div>
<div id="page-content-wrapper">
    <div id="grid"></div>
    
    
</div>
<script id="position-template" type="text/x-kendo-template">
  # if (data.showup==1) { #  
  <a href="id=#: id #&voider=move&direction=toup" class="k-button w-button b-up a-action" title="move up from position #: pos #">&nbsp;</a>
  # };
  if (data.showdown==1) { #  
  <a href="id=#: id #&voider=move&direction=todown" class="k-button w-button b-down a-action" title="move down from position #: pos #">&nbsp;</a>
  # }; #
</script>
<script >
    $(document).ready(function() {
     var dataTable = new kendo.data.DataSource({
            transport: {
                read: "?{{current.pageparams}}&response=json",
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
                     {field: "visible", title: "&nbsp;", width: "50px",template: '<a href="id=#: id #&voider=visible&value=#: notvisible #" class="k-button w-button b-#: visiblemark #visible a-action" title="toggle visibility">&nbsp;</a>'}, 
                     {field: "id", title: "N", width: "60px"},
                     {field: "name", title: "Name", template: '<a href="?{{current.pageparams}}&id=#: id #&voider=form&act=sedit" title="edit item">#: name # </a>'},
                     {field: "pos", title: "pos.", width: "97px",template: kendo.template($("#position-template").html()), attributes: {"class": "center"}}, 
                     { command: [
                             { name: "remove",text: "&nbsp;", click: removeItem, className: "w-button b-remove" }
                             ], title: "Delete", width: "50px" }
                 ]
             });

            function removeItem(e) {
                e.preventDefault();
                if (!confirm('Delete?')) return false;
                // json post id= result response.result == 'ok'
                var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
                postData('id=' + dataItem.id + '&voider=delete');
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