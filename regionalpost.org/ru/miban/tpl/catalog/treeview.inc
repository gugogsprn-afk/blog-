<div>
    <div id="categorytree"></div>
</div>
<div class="k-window-button-wrapper" style="">
    <a href="#" id="selectnode" class="k-button"  >Ok</a>
    <a href="#" id="selectroot" class="k-button"  >Root</a>
    <a href="#" id="cancel" class="k-button"  >Cancel</a>
</div>
<script >
        
            var inlineDefault =new kendo.data.HierarchicalDataSource({
                transport: {
                    read: {
                        url: '?{{current.pageparams}}&response=json&voider=categorytree&nid={{nid}}',
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


                $("#categorytree").kendoTreeView({
                    dataSource: inlineDefault,
                    dataTextField: "name",
                    dataBound: function(e) {
                        if (e.node === undefined) {
                         // root load    
                        }
                        //SelectedID,selectedPath
                        var treeView = $("#categorytree").data("kendoTreeView");	
                        if (selectedPath.length > 0) {
                            var first = treeView.dataSource.get(selectedPath[0]);
                            if (first) {
                                selectedPath.shift();
                                var elem = treeView.findByUid(first.uid);
                                if (selectedPath.length==0) {
                                    treeView.select(elem);
                                } else {
                                    treeView.expand(elem);
                                }
                            } 
                        }
                      
                        //console.log(e);
                    }
                });
                
                $("#cancel").click(function(e) {
                   e.preventDefault();
                   twd.data("kendoWindow").close();
                });
                
                $("#selectnode").click(function(e) {
                   e.preventDefault();
                   var treeView = $("#categorytree").data("kendoTreeView");	
                   var selectedNode = treeView.select();
                   if (selectedNode ===undefined) {
                       return false;
                   }
                   var dataItem = treeView.dataItem(selectedNode);
                   if (dataItem === undefined) {
                       return false;
                   }
                   
                   $('#selectedCategory').html(dataItem.name);
                   var kitems = $(selectedNode).add($(selectedNode).parentsUntil('#categorytree', '.k-item'));
                   var texts = $.map(kitems, function(kitem) {
                        var dt = treeView.dataItem(kitem);
                        return dt.id;
                   });
                   
                   $('#selectednode').val(dataItem.id);
                   $('#selectednode').trigger('change');
                   $('#selectednodepath').val(texts.join(','));
                   twd.data("kendoWindow").close();
                });
                
                $('#selectroot').click(function(e) {
                    e.preventDefault();
                    $('#selectedCategory').html('Корневой раздел');
                    $('#selectednode').val('0');
                   $('#selectednodepath').val('0');
                    twd.data("kendoWindow").close();

                });

        </script>
