<div class="page-title">
    <img src="imgs/genericapp.png" />Каталог <span class="last">{{current.title}}</span>
    <div class="page-toolbar">
        <a href="?{{current.pageparams}}&voider=form&act=sadd" id="apptoolbar-add" class="k-button w-button" title="Добавить Раздел" style="background-image:url('imgs/folder_add.png');">&nbsp;</a>
    </div>
</div>
<div id="page-content-wrapper">
    <div id="splitter-container">
        <div id="splitter">
            <div id="treeview"></div>
            <div id="grid"></div>
        </div>
        <div id="contextMenu"></div>
    </div>
</div>
<script id="treeview-template" type="text/kendo-ui-template">
    <span class="item-#: item.visiblemark #visible">#: item.name #</span>
</script>
<script id="position-template" type="text/x-kendo-template">
  # if (data.showup==1) { #
  <a href="id=#: id #&voider=move&direction=toup" class="k-button w-button b-up a-action" title="move up from position #: pos #">&nbsp;</a>
  # };
  if (data.showdown==1) { #
  <a href="id=#: id #&voider=move&direction=todown" class="k-button w-button b-down a-action" title="move down from position #: pos #">&nbsp;</a>
  # }; #
</script>
<script type="text/x-kendo-template" id="dettemplate">
    <div class="detail-wrapper">
        <div class="detail-header">
            <a href="?show=adprods&voider=form&act=sadd&parentid=#: id #" class="add-button underline" title="add item" >Добавить комнату</a>
        </div>
        <div class="detail-grid"></div>
    </div>
</script>
<script >
    function triggerResize() {
        var sze = window.innerHeight - $('#header').height() - $('#content .page-title').height()-80-$('#footer').height();
        $("#splitter").css('height', sze);
        $("#splitter").data("kendoSplitter").trigger("resize");
    }

    $(document).ready(function() {
      var homogeneous = new kendo.data.HierarchicalDataSource({
        transport: {
            read: {
                url: '?{{current.pageparams}}&response=json',
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

    var dataTable = new kendo.data.DataSource({
            transport: {
                read: "?show=product&response=json",
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
                 columns: [
                     {field: "visible", title: "&nbsp;", width: "50px",template: '<a href="id=#: id #&voider=visible&value=#: notvisible #" class="k-button w-button b-#: visiblemark #visible a-action" title="toggle visibility">&nbsp;</a>'},
                     {field: "id", title: "N", width: "60px"},
                     {field: "name", title: "Имя", template: '<a href="?show=product&id=#: id #&voider=form&act=sedit" title="edit item">#: name # </a>'},
                     {field: "pos", title: "Поз.", width: "97px",template: kendo.template($("#position-template").html()), attributes: {"class": "center"}},
                     { command: [
                             { name: "remove",text: "&nbsp;", click: removeItem, className: "w-button b-remove" }
                             ], title: "Удал.", width: "50px" }
                 ]
             });



    var SelectedPathStr = '{{selcat.fullpath}}';
    var selectedPath = SelectedPathStr.split(',');

    $("#treeview").kendoTreeView({
        dataSource: homogeneous,
        dataTextField: "name",
        dragAndDrop: false,
        template: kendo.template($("#treeview-template").html()),
        select: function (event)
        {
            var dataItem = this.dataItem(event.node);
            dataTable.transport.options.read.url = "?show=product&response=json&parent_id="+ dataItem.id;
            var grid = $("#grid").data("kendoGrid");
            grid.dataSource.page(1);
            //dataItem.loaded(false); reload node
            //console.log("Selected node with id=" + dataItem.id);
        },
        dataBound: function(e) {
            if (e.node === undefined) {
             // root load
            }
            //SelectedID,selectedPath
            var treeView = $("#treeview").data("kendoTreeView");
            if (selectedPath.length > 0) {
                var first = treeView.dataSource.get(selectedPath[0]);
                if (first) {
                    selectedPath.shift();
                    var elem = treeView.findByUid(first.uid);
                    if (selectedPath.length==0) {
                        treeView.select(elem);
                        var dataItem = treeView.dataItem(elem);
                        dataTable.transport.options.read.url = "?show=product&response=json&parent_id="+ dataItem.id;
                        var grid = $("#grid").data("kendoGrid");
                        grid.dataSource.read();
                    } else {
                        treeView.expand(elem);
                    }
                }
            }

            //console.log(e);
        }
    });




    var cm = $('#contextMenu').kendoMenuEx({
        dataSource: [

            {
                text: 'Добавить подраздел',
                click: function (event) {

                    var treeview = $("#treeview").data("kendoTreeView");
                    var dataItem = treeview.dataItem('#treeview_tv_active');
                    redirectTo([
                        {key:'show',value:'catalog'},
                        {key:'voider',value:'form'},
                        {key:'act',value:'sadd'},
                        {key:'parent_id',value:dataItem.id}
                    ]);
                }
            },
            {
                text: 'Добавить Товар',
                click: function (event) {
                    var treeview = $("#treeview").data("kendoTreeView");
                    var dataItem = treeview.dataItem('#treeview_tv_active');
                    redirectTo([
                        {key:'show',value:'product'},
                        {key:'voider',value:'form'},
                        {key:'act',value:'sadd'},
                        {key:'parent_id',value:dataItem.id}
                    ]);
                }
            },
            {
                text: 'Редактировать',
                cssClass: "editbutton",
                click: function (event) {
                    var treeview = $("#treeview").data("kendoTreeView");
                    var dataItem = treeview.dataItem('#treeview_tv_active');
                    redirectTo([
                        {key:'show',value:'catalog'},
                        {key:'voider',value:'form'},
                        {key:'act',value:'sedit'},
                        {key:'id',value:dataItem.id}
                    ]);
                }
            },
            {
                text: 'Вкл/Откл.',
                cssClass: "editbutton",
                click: function (event) {
                    var treeview = $("#treeview").data("kendoTreeView");
                    var dataItem = treeview.dataItem('#treeview_tv_active');
                    var url = '{{current.pageparams}}&voider=visible&id=' + dataItem.id + '&value=' + dataItem.notvisible;
                    var parentItem =treeview.dataItem(treeview.parent('#treeview_tv_active'));
                    postData(url,parentItem);
                }
            },
            {
                text: 'Переместить вверх',
                cssClass: "moveUp editbutton",
                click: function (event) {
                    var treeview = $("#treeview").data("kendoTreeView");
                    var dataItem = treeview.dataItem('#treeview_tv_active');
                    var url = '{{current.pageparams}}&voider=move&direction=toup&id=' + dataItem.id;
                    var parentItem =treeview.dataItem(treeview.parent('#treeview_tv_active'));
                    postData(url,parentItem);
                }
            },
            {
                text: 'Переместить вниз',
                cssClass: "moveDown editbutton",
                click: function (event) {
                    var treeview = $("#treeview").data("kendoTreeView");
                    var dataItem = treeview.dataItem('#treeview_tv_active');
                    var url = '{{current.pageparams}}&voider=move&direction=todown&id=' + dataItem.id;
                    var parentItem =treeview.dataItem(treeview.parent('#treeview_tv_active'));
                    postData(url,parentItem);
                }
            },
            {
                text: 'Удалить',
                cssClass: "editbutton",
                click: function (event) {
                    var treeview = $("#treeview").data("kendoTreeView");
                    var dataItem = treeview.dataItem('#treeview_tv_active');
                    var url = '{{current.pageparams}}&voider=delete&id=' + dataItem.id;
                    var parentItem =treeview.dataItem(treeview.parent('#treeview_tv_active'));
                    postData(url,parentItem);
                }
            }
        ],
        anchor: '#splitter-container',
        selector: '#treeview li',
        delay: 5000,
        onShow: function(mnu) {
            //var menu = $('#contextMenu').data("kendoMenu");
            var treeview = $("#treeview").data("kendoTreeView");
            var dataItem = treeview.dataItem('#treeview_tv_active');
            mnu.enable(".editbutton",(dataItem.id == 0?false:true));
            mnu.enable(".moveUp",(dataItem.showup == '1'?true:false));
            mnu.enable(".moveDown",(dataItem.showdown == '1'?true:false));
            return true;
        }
    });

    $("#splitter").kendoSplitter({
        panes: [
            { collapsible: true, min: "150px" , size: "250px"},
            { collapsible: false, min: "200px" }
        ]
    });
    $(window).resize(function(){
        triggerResize();
    });
    triggerResize();

    function postData(url,ParentItem) {
        makeRequest({
            data: url + '&response=json',
            onSuccess: function(data,status) {
                if (ParentItem === undefined) {
                    var treeview = $("#treeview").data("kendoTreeView");
                    //treeview.dataSource.transport.options.read.url = loadurl + query; ie cache
                    treeview.dataSource.read();
                } else {
                    ParentItem.loaded(false);
                    ParentItem.load();
                }
            }
        });
    };


    /****** grid *********/


            function removeItem(e) {
                e.preventDefault();
                if (!confirm('Удалить?')) return false;
                var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
                var data = 'id='+ dataItem.id + '&voider=delete';
                postGridData(data);
            }





            $('#page-content-wrapper').on('click','.a-action',function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                postGridData(url);
            });



            function postGridData(params) {
                makeRequest({
                    data: 'response=json&show=product&' + params,
                    onSuccess: function(data,status) {
                        var grid = $("#grid").data("kendoGrid");
                        grid.dataSource.read();
                    }
                });
            }





    });
</script>
