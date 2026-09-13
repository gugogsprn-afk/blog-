<div class="page-title">
    <img src="imgs/genericapp.png" />Administrator
    <div class="page-toolbar">
        <a href="?{{current.pageparams}}&voider=form&act=sadd" id="apptoolbar-add" class="k-button w-button" title="add item" style="background-image:url('imgs/mainpage/simple_plus.png');">&nbsp;</a>
    </div>
</div>
<div id="page-content-wrapper">
    <div id="grid"></div>
    
    
</div>
<script id="status-template" type="text/x-kendo-template">
  # if (data.usr_active==0) { #  
  <span class="inactive" title="Not activated"></span>
  # } else if (data.usr_active==1 && data.daysleft>=0) { # 
  <span class="active"></span>
  # } else if (data.daysleft<0) { # 
  <span class="disabled" title="Account is expired"></span>
  # }; #
</script>
<script >
    
    function toggleItem(element) {
        var that = $(element);
        var curEnabled = parseInt($(that).data("ienabled"))===1?true:false;
        var quest = curEnabled?'Disable':'Enable';
        if (!confirm(quest + '?'))
            return false;
        var Userid = that.data('id');
        var isEnable = curEnabled?0:1;
        makeRequest({
                data: { show:'adminmembers', voider:'togglemember', id: Userid, enabled: isEnable, response: 'json' },
                onSuccess: function(data) {
                    var grid = $("#grid").data("kendoGrid");
                    grid.dataSource.read();
                    return false;
                }
            });
        return false;
    }

    $(document).ready(function() {
     var dataTable = new kendo.data.DataSource({
            transport: {
                read: "?show=adminmembers&response=json",
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
                     {field: "enabled", title: "Enabled", width: "50px",template: '<a href="changeenabled" onClick="return toggleItem(this);" data-id="#: id #" data-ienabled="#: enabled #" class="k-button w-button b-enabled enabled#: enabled #" title="Enable or disable">&nbsp;</a>',attributes: {"class": "center"}, sortable: false}, 
                     {field: "usr_active", title: "Status", width: "50px",template: kendo.template($("#status-template").html()),attributes: {"class": "center"}, sortable: false}, 
                     {field: "id", title: "N", width: "60px"},
                     {field: "usr_name", title: "E-mail", template: '<a href="?show=adminmembers&voider=form&act=sedit&id=#: id #" title="Edit" title="Created #: created #">#: usr_name # </a>'},
                     {field: "name", title: "Name" , width: "200px"},
                     /*{field: "role_name", title: "Role", width: "160px"},*/
                     {field: "current_visit", title: "Last access", width: "150px"},
                     {field: "daysleft", title: "days left", width: "60px"},
                     {command: [
                        {name: "remove", text: "&nbsp;", click: removeItem, className: "w-button b-remove"}
                    ], title: "Delete", width: "50px"}
                 ]
             });


        function removeItem(e) {
            e.preventDefault();
            if (!confirm('Delete?'))
                return false;
            var dataItem = this.dataItem($(e.currentTarget).closest("tr")).toJSON();
            makeRequest({
                    data: "show=adminmembers&voider=delete&id=" + dataItem.id,
                    onSuccess: function(data) {
                        var grid = $("#grid").data("kendoGrid");
                         grid.dataSource.read();
                        return false;
                    }
                });
            
        }
        
        
        
        
            
            
    });
</script>