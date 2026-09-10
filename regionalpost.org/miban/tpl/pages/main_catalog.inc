<div class="page-title">
    <img src="imgs/genericapp.png" />Pages <span class="last">{{current.title}}</span>
    <div class="page-toolbar">
        <a href="?show=catalog&voider=form&act=sadd" id="apptoolbar-add" class="k-button w-button" title="Add category" style="background-image:url('imgs/folder_add.png');">&nbsp;</a>
        {{if allowedit}}<a href="?{{current.pageparams}}&voider=form&act=sadd&block_id={{block_id}}{{parentid}}" id="apptoolbar-add" class="k-button w-button" title="add item" style="background-image:url('imgs/mainpage/simple_plus.png');">&nbsp;</a>{{end if allowedit}}
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
    function triggerResize() {
        var sze = window.innerHeight - $('#header').height() - $('#content .page-title').height()-80-$('#footer').height();
        $("#splitter").css('height', sze);
        $("#splitter").data("kendoSplitter").trigger("resize");
    }

    $(document).ready(function() {

    var homogeneous = new kendo.data.HierarchicalDataSource({
        transport: {
            read: {
                url: '?show=catalog&response=json',
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
            dataTable.transport.options.read.url = "?show=pages&response=json&block_id=imageitems&pid="+ dataItem.id;
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
                        dataTable.transport.options.read.url ="?show=pages&response=json&block_id=imageitems&pid="+ dataItem.id;
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
                text: 'Add subcategory',
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
                text: 'Add category',
                click: function (event) {
                    redirectTo([
                        {key:'show',value:'catalog'},
                        {key:'voider',value:'form'},
                        {key:'act',value:'sadd'}
                    ]);
                }
            },
            {
                text: 'Add article',
                click: function (event) {
                    var treeview = $("#treeview").data("kendoTreeView");
                    var dataItem = treeview.dataItem('#treeview_tv_active');
                    redirectTo([
                        {key:'show',value:'pages'},
                        {key:'block_id',value:'imageitems'},
                        {key:'voider',value:'form'},
                        {key:'act',value:'sadd'}
                    ]);
                }
            },
            {
                text: 'Edit',
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
                text: 'Enable/Disable',
                cssClass: "editbutton",
                click: function (event) {
                    var treeview = $("#treeview").data("kendoTreeView");
                    var dataItem = treeview.dataItem('#treeview_tv_active');
                    var url = 'show=catalog&voider=visible&id=' + dataItem.id + '&value=' + dataItem.notvisible;
                    var parentItem =treeview.dataItem(treeview.parent('#treeview_tv_active'));
                    postDataCat(url,parentItem);
                }
            },
            {
                text: 'Move up',
                cssClass: "moveUp editbutton",
                click: function (event) {
                    var treeview = $("#treeview").data("kendoTreeView");
                    var dataItem = treeview.dataItem('#treeview_tv_active');
                    var url = 'show=catalog&voider=move&direction=toup&id=' + dataItem.id;
                    var parentItem =treeview.dataItem(treeview.parent('#treeview_tv_active'));
                    postDataCat(url,parentItem);
                }
            },
            {
                text: 'Move down',
                cssClass: "moveDown editbutton",
                click: function (event) {
                    var treeview = $("#treeview").data("kendoTreeView");
                    var dataItem = treeview.dataItem('#treeview_tv_active');
                    var url = 'show=catalog&voider=move&direction=todown&id=' + dataItem.id;
                    var parentItem =treeview.dataItem(treeview.parent('#treeview_tv_active'));
                    postDataCat(url,parentItem);
                }
            },
            {
                text: 'Delete',
                cssClass: "editbutton",
                click: function (event) {
                    var treeview = $("#treeview").data("kendoTreeView");
                    var dataItem = treeview.dataItem('#treeview_tv_active');
                    var url = 'show=catalog&voider=delete&id=' + dataItem.id;
                    var parentItem =treeview.dataItem(treeview.parent('#treeview_tv_active'));
                    postDataCat(url,parentItem);
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

    function postDataCat(url,ParentItem) {
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
