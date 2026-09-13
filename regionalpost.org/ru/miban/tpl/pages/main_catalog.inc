<div class="page-title rp-page-header">
    <div class="rp-page-header-top">
        <div class="rp-page-header-text">
            <p class="rp-page-kicker">Pages · {{current.title}}</p>
            <h1 class="rp-page-title">{{current.title}}</h1>
            <p class="rp-page-desc">Browse and manage content here. Pick a category on the left, then open an article to edit text, images, and visibility. Use the buttons on the right to create something new.</p>
        </div>
        <div class="page-toolbar rp-toolbar">
            <a href="?show=catalog&voider=form&act=sadd" class="rp-btn rp-btn-secondary" title="Add category">
                <span class="rp-btn-icon" aria-hidden="true">+</span>
                New category
            </a>
            {{if allowedit}}
            <a href="?{{current.pageparams}}&voider=form&act=sadd&block_id={{block_id}}{{parentid}}" class="rp-btn rp-btn-primary" title="Add article">
                <span class="rp-btn-icon" aria-hidden="true">+</span>
                New article
            </a>
            {{end if allowedit}}
        </div>
    </div>
</div>
<div id="page-content-wrapper">
    <div class="rp-help-strip">
        <span><strong>Tip:</strong> Click a row title to edit.</span>
        <span><strong>Status:</strong> Live / Hidden controls what visitors see.</span>
        <span><strong>Category:</strong> Right-click a folder for more actions.</span>
    </div>
    <div id="splitter-container" class="rp-splitter-shell">
        <div id="splitter">
            <div class="rp-panel rp-categories-panel">
                <div class="rp-panel-head">
                    <h3>Categories</h3>
                    <p>Filter articles by section</p>
                </div>
                <div class="rp-panel-body">
                    <div id="treeview"></div>
                </div>
            </div>
            <div class="rp-panel rp-articles-panel">
                <div class="rp-panel-head">
                    <h3>Items</h3>
                    <p>Articles in the selected category</p>
                </div>
                <div class="rp-articles-toolbar">
                    <label class="rp-search-field" for="rp-article-search">
                        <span class="rp-btn-icon" aria-hidden="true">⌕</span>
                        <input type="search" id="rp-article-search" placeholder="Filter visible rows…" autocomplete="off" />
                    </label>
                    <span class="rp-search-hint">Filters the current page of results</span>
                </div>
                <div id="grid"></div>
            </div>
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

                     {field: "visible", title: "Status", width: "92px", template: '<a href="id=#: id #&voider=visible&value=#: visible #" class="rp-status rp-status-#: (visible==1?"live":"draft") # a-action" title="Toggle visibility">#: (visible==1?"Live":"Hidden") #</a>'},

                     {field: "id", title: "ID", width: "72px", template: '<span class="rp-id-chip">#: id #</span>'},
                     {field: "name", title: "Title", template: '<a href="?{{current.pageparams}}&id=#: id #&voider=form&act=sedit" title="Edit item">#: name #</a>'}
                     {{if showdate}}
                     ,{field: "date_object", title: "Published", width: "110px", template: '#= kendo.toString(date_object, "dd.MM.yyyy") #'}
                     {{end if showdate}}

                    {{if allowedit}}
                    ,{ title: "Actions", width: "130px", template: '<span class="rp-row-actions"><a href="?{{current.pageparams}}&id=#: id #&voider=form&act=sedit" class="rp-action-link">Edit</a><a href="\\#" class="rp-action-link rp-action-danger rp-delete-row" data-id="#: id #">Delete</a></span>' }
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



            $('#grid').on('click', '.rp-delete-row', function(e) {
                e.preventDefault();
                if (!confirm('Delete this item?')) return false;
                var gridWidget = $('#grid').data('kendoGrid');
                var row = $(this).closest('tr');
                var dataItem = gridWidget.dataItem(row);
                postData('id=' + dataItem.id + '&voider=delete', gridWidget);
            });

            function removeItem(e) {
                e.preventDefault();
                if (!confirm('Delete this item?')) return false;
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

            var searchTimer = null;
            $('#rp-article-search').on('input', function() {
                var query = $.trim($(this).val()).toLowerCase();
                var gridWidget = $('#grid').data('kendoGrid');
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    if (!query) {
                        gridWidget.dataSource.filter([]);
                        return;
                    }
                    gridWidget.dataSource.filter({
                        logic: 'or',
                        filters: [
                            { field: 'name', operator: 'contains', value: query },
                            { field: 'id', operator: 'contains', value: query }
                        ]
                    });
                }, 180);
            });

    });
</script>
