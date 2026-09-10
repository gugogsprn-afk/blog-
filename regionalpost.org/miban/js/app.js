kendo.culture("en-US");

$(document).ready(function () {
    
    var PageWrapper = $('#page-content-wrapper');

    $("#Sirius-MainMenu").kendoMenu();
    $("#Sirius-userinfo").kendoMenu();
    
    PageWrapper.on({
        mouseenter: function () {
            $(this).children(':not(.notthis)').css('background-color', '#c0e463'); //a5ce39
        },
        mouseleave: function () {
            $(this).children(':not(.notthis)').css('background-color', '');
        }
    },'table.fieldset > tbody > tr:not(.notthis) ,table.fieldset > tr:not(.notthis)');
    
    
    $('.transblock').each(function() {
        var lang = $(this).data('lang');
        console.log(lang);
        $(this).find('.trans').each(function() {
            $(this).append('<span class="k-descr">translation (' + lang + ')</span>');
            if (lang=='ar') {
               // $(this).closest('tr').attr('dir','rtl');
            }
        });
    });
    
    $('.fieldset .trans').each(function() {
        var lang = $(this).data('lang');
        if (lang && lang!=='') {
            $(this).append('<span class="k-descr">translation (' + lang + ')</span>');
            if (lang=='ar') {
               // $(this).closest('tr').attr('dir','rtl');
            }
        }
    });
    
    
    $('textarea.maxlength').bind("change paste keyup", function(e) {
        var limit = parseInt($(this).attr('maxlength'));  
        var text = $(this).val();  
        var chars = text.length;  
        var new_text = text;
        if (chars>=limit) {
            e.preventDefault();
            e.stopPropagation();   
        }
        if(chars > limit){  
            new_text = text.substr(0, limit);  
            $(this).val(new_text);  
            chars = limit;
        }
        $(this).closest('td').find('.chars_remainer strong').html(limit - chars);
    });  

   document.addEventListener("keydown", function(e) {
        if (e.keyCode === 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
          e.preventDefault();
          var frms = PageWrapper.find('#itemform');
          if (frms.length>0) {
            $(frms[0]).submit();
          }
    }}, false); 


    var wnd = {};
    $('#popupForm').kendoWindow({
        width: "500px",
        modal: true,
        visible: false,
        refresh: function() {
            $('#popup-form').myForm({
                onSuccess: function(data) {
                    wnd.close();
                    return false;
                }
            });
        },
        animation: {close: {duration: 200}, open: {duration: 200}}
    });
    var wnd = $('#popupForm').data("kendoWindow");

    $('#member-passChange').click(function(e) {
        e.preventDefault();
        wnd.refresh({url: "?show=adminmembers&voider=passform", iframe: false}).title("Change password").open().center();
    });	   

$('.indexfollow').each(function() {
        var curValue = $(this).data("value");
        $(this).kendoDropDownList({
            value: curValue
        });
    });
 
 
});


function wnd_treeview(settings) {
    
    var options = $.extend({
        nID : 0,
        currentPath: [],
        onSelect: function(item) {},
        rootAllow: true
    },settings,true);
    
    var that = this,
        element = $('#popup_tree').html(''),
        wnd = {},
        treeView = {},
        selectedItem = {},
        treeViewElement = $('<div />').appendTo(element),
        btn_wrap = $('<div class="wnd_btn_wrap" />').appendTo(element),
        btn_root = $('<a href="#" class="k-button">Root category</a>'),
        btn_select = $('<a href="#" class="k-button">Select</a>').appendTo(btn_wrap);
    
    if (options.rootAllow===true) {
        btn_root.appendTo(btn_wrap);
    }
    
    var initilize = function() {
        
        treeViewElement.kendoTreeView({
            dataSource: new kendo.data.HierarchicalDataSource({
                transport: {
                    read: {
                        url: '?show=catalog&response=json&nid='+options.nID,
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
            }),
            dataTextField: "name",
            dataBound: function(e) {
                if (e.node === undefined) {
                 // root load    
                }
                if (options.currentPath.length > 0) {
                    var first = treeView.dataSource.get(options.currentPath[0]);
                    if (first) {
                        options.currentPath.shift();
                        var elem = treeView.findByUid(first.uid);
                        if (options.currentPath.length===0) {
                            treeView.select(elem);
                            var dataItem = treeView.dataItem(elem);
                            if (dataItem === undefined) {
                                return false;
                            }
                            var parentElements =  $(elem).add($(elem).parentsUntil('div.k-treeview', '.k-item'));
                            var parents = $.map(parentElements, function(kitem) {
                                 var dt = treeView.dataItem(kitem);
                                 return dt.toJSON();
                            });
                            selectedItem = { item: dataItem.toJSON() , parents: parents};
                        } else {
                            treeView.expand(elem);
                        }
                    } 
                }
            }
        });
        treeView = treeViewElement.data("kendoTreeView");
       
       
        element.kendoWindow({
            width: "400px",
            modal: true,
            visible: false,
            title: "Select category",
            animation: { close : { duration: 200 }, open : { duration: 200 } }
       });
       wnd = element.data("kendoWindow");
        
       
       btn_root.click(function(e) {
            e.preventDefault();
            selectedItem = { item: { id: 0 }, parents: [] };
            options.onSelect(selectedItem);
            that.close();
       });
       
       
       btn_select.click(function(e) {
           e.preventDefault();
           var selectedNode = treeView.select();
           if (selectedNode ===undefined) {
               return false;
           }
           var dataItem = treeView.dataItem(selectedNode);
           if (dataItem === undefined) {
               return false;
           }
           var parentElements =  $(selectedNode).add($(selectedNode).parentsUntil('div.k-treeview', '.k-item'));
           var parents = $.map(parentElements, function(kitem) {
                var dt = treeView.dataItem(kitem);
                return dt.toJSON();
           });
           selectedItem = { item: dataItem.toJSON() , parents: parents};
           options.onSelect(selectedItem);
           that.close();
       });
    };
    
    
    
    this.show = function() {
        wnd.open().center();
    };
    
    this.close = function() {
        wnd.close();
    };
    
    initilize();
}

function wnd_itemslist(settings) {
    
    var options = $.extend({
        currentPath: [],
        multiSelect: false,
        onSelect: function(item) {},
        onShow: function(wrap,btnwrap) {},
        onInit: function(wrap,btnwrap) {}
    },settings,true);
    
    var that = this,
        element = $('#popup_products').html(''),
        wnd = {},
        treeView = {},
        grid = {},
        selectedItem = {},
        dataTable = {},
        
        spliiterElement = $('<div class="wnd-splitter" />').appendTo(element),
        treeViewElement = $('<div />').appendTo(spliiterElement),
        gridElement = $('<div />').appendTo(spliiterElement),
        btn_wrap = $('<div class="wnd_btn_wrap" />').appendTo(element),
        btn_select = $('<a href="#" class="k-button">Select</a>').appendTo(btn_wrap);
    
    
    var triggerResize = function() {
        var sze = element.height()-60;
        spliiterElement.css('height', sze);
        spliiterElement.data("kendoSplitter").trigger("resize");
    };
    
    var initilize = function() {
        
        treeViewElement.kendoTreeView({
            dataSource: new kendo.data.HierarchicalDataSource({
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
                error: function(e) {
                    showError(e.errorThrown, e.errors);
                }
            }),
            dataTextField: "name",
            dataBound: function(e) {
                if (e.node === undefined) {
                 // root load    
                }
                if (options.currentPath.length > 0) {
                    var first = treeView.dataSource.get(options.currentPath[0]);
                    if (first) {
                        options.currentPath.shift();
                        var elem = treeView.findByUid(first.uid);
                        if (options.currentPath.length===0) {
                            treeView.select(elem);
                            var dataItem = treeView.dataItem(elem);
                            if (dataItem === undefined) {
                                return false;
                            }
                            var parentElements =  $(elem).add($(elem).parentsUntil('div.k-treeview', '.k-item'));
                            var parents = $.map(parentElements, function(kitem) {
                                 var dt = treeView.dataItem(kitem);
                                 return dt.toJSON();
                            });
                            selectedItem = { item: {} , parents: parents, count: 0};
                            
                            dataTable.transport.options.read.url = "?show=product&response=json&parent_id="+ dataItem.id; 
                            grid.dataSource.page(1);
                        } else {
                            treeView.expand(elem);
                        }
                    } 
                }
            },
            select: function (event) {
                var dataItem = this.dataItem(event.node);
                dataTable.transport.options.read.url = "?show=product&response=json&parent_id="+ dataItem.id; 
                grid.dataSource.page(1);
            }
        });
        treeView = treeViewElement.data("kendoTreeView");
        
        dataTable = new kendo.data.DataSource({
            transport: {
                read: "?show=product&response=json&act=table",
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
        
        gridElement.kendoGrid({
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
                {field: "name", title: "Name"}
            ]
        });
        grid = gridElement.data("kendoGrid");
        
        element.kendoWindow({
            width: "950px",
            height: "500px",
            modal: true,
            visible: false,
            title: "Select items",
            animation: { close : { duration: 200 }, open : { duration: 200 } },
            resize: function(e) {
                triggerResize();
            }
        });
       wnd = element.data("kendoWindow");
      
       
       spliiterElement.kendoSplitter({
            panes: [
                { collapsible: true, min: "150px" , size: "250px"},
                { collapsible: false, min: "200px" }
            ]
       });      

       btn_select.click(function(e) {
           e.preventDefault();
           var row = grid.select();
           if (row.length<=0) {
                return false;
           }
           var dataItem = grid.dataItem(row);
           
           var selectedNode = treeView.select();
           if (selectedNode ===undefined) {
               return false;
           }
           var parentElements =  $(selectedNode).add($(selectedNode).parentsUntil('div.k-treeview', '.k-item'));
           var parents = $.map(parentElements, function(kitem) {
                var dt = treeView.dataItem(kitem);
                return dt.toJSON();
           });
           
           selectedItem = { item: dataItem.toJSON(), parents: parents, count: 1 };
           options.onSelect(selectedItem);
           if (options.multiSelect===false) {
                that.close();
           }
       });
       options.onInit(element,btn_wrap);
    };
    
    
    
    this.show = function() {
        wnd.open().center();
        options.onShow(element,btn_wrap);
        triggerResize();
    };
    
    this.close = function() {
        wnd.close();
    };
    
    initilize();
}



function wnd_urlmap(settings) {
    
    var options = $.extend({
        nID : 0,
        currentPath: [],
        onSelect: function(item) {return false},
        rootAllow: true
    },settings,true);
    
    var that = this,
        element = $('#popup_urls').html(''),
        wnd = {},
        treeView = {},
        selectedItem = {},
        treeViewElement = $('<div />').appendTo(element),
        btn_wrap = $('<div class="wnd_btn_wrap" />').appendTo(element),
        btn_root = $('<a href="#" class="k-button">Root page</a>'),
        btn_select = $('<a href="#" class="k-button">Select</a>').appendTo(btn_wrap);
    
    if (options.rootAllow===true) {
        btn_root.appendTo(btn_wrap);
    }
    
    var initilize = function() {
        
        treeViewElement.kendoTreeView({
            dataSource: new kendo.data.HierarchicalDataSource({
                transport: {
                    read: {
                        url: '?show=helpers&voider=urlmap&response=json&nid='+options.nID,
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
            }),
            dataTextField: "displaytext",
            dataBound: function(e) {
                if (e.node === undefined) {
                 // root load    
                }
                if (options.currentPath.length > 0) {
                    var first = treeView.dataSource.get(options.currentPath[0]);
                    if (first) {
                        options.currentPath.shift();
                        var elem = treeView.findByUid(first.uid);
                        if (options.currentPath.length===0) {
                            treeView.select(elem);
                            var dataItem = treeView.dataItem(elem);
                            if (dataItem === undefined) {
                                return false;
                            }
                            var parentElements =  $(elem).add($(elem).parentsUntil('div.k-treeview', '.k-item'));
                            var parents = $.map(parentElements, function(kitem) {
                                 var dt = treeView.dataItem(kitem);
                                 return dt.toJSON();
                            });
                            selectedItem = { item: dataItem.toJSON() , parents: parents};
                        } else {
                            treeView.expand(elem);
                        }
                    } 
                }
            }
        });
        treeView = treeViewElement.data("kendoTreeView");
       
       
        element.kendoWindow({
            width: "400px",
            modal: true,
            visible: false,
            title: "Select page",
            animation: { close : { duration: 200 }, open : { duration: 200 } }
       });
       wnd = element.data("kendoWindow");
        
       
       btn_root.click(function(e) {
            e.preventDefault();
            selectedItem = { item: { id: 0 }, parents: [] };
            var bl = options.onSelect(selectedItem);
            if (!bl) that.close();
       });
       
       
       btn_select.click(function(e) {
           e.preventDefault();
           var selectedNode = treeView.select();
           if (selectedNode ===undefined) {
               return false;
           }
           var dataItem = treeView.dataItem(selectedNode);
           if (dataItem === undefined) {
               return false;
           }
           var parentElements =  $(selectedNode).add($(selectedNode).parentsUntil('div.k-treeview', '.k-item'));
           var parents = $.map(parentElements, function(kitem) {
                var dt = treeView.dataItem(kitem);
                return dt.toJSON();
           });
           selectedItem = { item: dataItem.toJSON() , parents: parents};
           var bl = options.onSelect(selectedItem);
           if (!bl) that.close();
       });
    };
    
    
    
    this.show = function() {
        wnd.open().center();
    };
    
    this.close = function() {
        wnd.close();
    };
    
    initilize();
}