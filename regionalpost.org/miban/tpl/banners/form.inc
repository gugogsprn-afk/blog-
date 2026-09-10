<div class="page-title">
    <img src="imgs/genericapp.png" />Banners <span class="last">{{if act(=add)}}Add{{else act}}Edit "{{item.name}}"{{end if act}}</span>
    <div class="page-toolbar">
        <a href="?{{current.pageparams}}" id="apptoolbar-add" class="k-button w-button" title="Back" style="background-image:url('imgs/left.png');">&nbsp;</a>
    </div>
</div>
<div id="page-content-wrapper">
    <form method="POST" action="index.php" id="itemform" enctype="multipart/form-data">
        {{for current.queryparams as param}}
        <input type="hidden" name="{{param.key}}" value="{{param.value}}" />
        {{end for current.queryparams}}
        <input type="hidden" name="id" id="item-id" value="{{item.id}}" />
        <input type="hidden" name="voider" id="action" value="{{act}}"/>
        <input type="hidden" name="item[context]" value="30" />
        
        <input type="hidden" name="url[1][id]" value="1" />
        <input type="checkbox" name="url[1][children]" value="1" checked="checked" style="width: 0;height: 0;"/>

        <div>
            <div id="main-tabstrip">
                <ul>
                    <li class="k-title k-state-active">{{langs.0.name_en}}</li>
                    <li class="k-title">{{langs.1.name_en}}</li>
                    <li class="k-title">{{langs.2.name_en}}</li>
                </ul>
                <div>
                    <table class="fieldset">
                        <tr>
                            <th>Name</th>
                            <td><input type="text" id="banner-name" name="item[name]" class="k-input" value="{{item.name}}" /></td>
                        </tr>
                        <tr class="notthis caption">
                            <th colspan="2">Content</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[content]" class="ckeditor" style=" margin: 0 auto;">{{item.content}}</textarea>
                            </td>
                        </tr>
                        
                        <tr>
                            <th>Show up to</th>
                            <td id="banner-validdate">
                                <input type="text" name="item[validdate]" class="k-datepicker" value="{{item.validdate}}" />
                            </td>
                        </tr>
                        <tr>
                            <th>Place<span id="place-size" class="k-descr">&nbsp;</span></th>
                            <td>
                                <select name="item[place]" class="imagemodeselect" style="width: 400px;" >
                                    <option value="ontop" {{item.place_ontop}}>on top</option>
                                    <option value="onmiddle" {{item.place_onmiddle}}>in the middle</option>
                                    <option value="onleft" {{item.place_onleft}}>on aside</option>
                                    <option value="onbottom" {{item.place_onbottom}}>on bottom</option>
                                </select> 
                            </td>
                        </tr>
                        <tr>
                            <th>Priority<span class="k-descr">Position in banners list</span></th>
                            <td>
                                <input type="number" name="item[priority]" class="k-numeric" value="{{item.priority}}" />
                            </td>
                        </tr>
                        <tr class="notthis" style="display: none;">
                            <th>
                                Pages
                                <a class="k-button" title="add page" href="#" id="add-bannerpage" style="float: right;">Add page</a>
                            </th>
                            <td id="analogitem-analogstock">
                                <ul id="itemlist">
                                    {{for urls as one}}
                                    <li>
                                        <input type="hidden" name="url[{{one.url_id}}][id]" value="{{one.url_id}}" />{{one.displaytext}} |
                                        <a href="{{siteroot}}{{one.url}}" target="_blank" style="color:#929292;">{{one.url}}</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label><input type="checkbox" name="url[{{one.url_id}}][children]" value="1" {{one.children}} /> Nested pages</label>
                                        <a href="#" class="k-button w-button b-remove">&nbsp;</a><div class="clear"></div>
                                    </li>
                                    {{end for urls}}                        
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table class="fieldset transblock" data-lang="{{langs.1.id}}">
                        <tr>
                            <th class="trans">Name<span class="k-descr"></span></th>
                            <td><input type="text" name="item[name_lg1]" class="k-input" value="{{item.name_lg1}}" /></td>
                        </tr>
                        <tr class="notthis caption">
                            <th colspan="2" class="trans">Content</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[content_lg1]" class="ckeditor" style=" margin: 0 auto;">{{item.content_lg1}}</textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table class="fieldset transblock" data-lang="{{langs.2.id}}">
                        <tr>
                            <th class="trans">Name<span class="k-descr"></span></th>
                            <td><input type="text" name="item[name_lg2]" class="k-input" value="{{item.name_lg2}}" /></td>
                        </tr>
                        <tr class="notthis caption">
                            <th colspan="2" class="trans">Content</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[content_lg2]" class="ckeditor" style=" margin: 0 auto;">{{item.content_lg2}}</textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="submitrow">
                <input type="submit" class="k-button submit" value="Save" />
            </div>
        </div>
    </form>
</div>
<div id="items-window">
    
</div>
<script >
    $(document).ready(function () {
        /* var places = { ontop:'910x???',onleft: '250x???',onbottom:'910x???'}; */
                        
        
        $('.k-numeric').kendoNumericTextBox({
            format: '###'
        });
        $('.dropselect').kendoDropDownList();
        $('.k-datepicker').kendoDatePicker({format: "dd.MM.yyyy"});
	$('.imagemodeselect').kendoDropDownList({
            /*,
            template : '<div><img src="imgs/${ data.value }.png" style="vertical-align:middle;margin:10px 0;" />&nbsp;${ data.text }</div><div class="clear"></div>'
            
            select: function(e) {
                var dataItem = this.dataItem(e.item.index());
                $('#place-size').html(places[dataItem.value]);
            }*/
        });
        
        
        $('#itemform').myForm({
            showSuccess: true,
            onSuccess: function (data) {
                return true;
            },
            beforeSubmit: function () {
                return true;
            }
        });
        $('#main-tabstrip').kendoTabStrip({
            animation:  {
                open: {
                    effects: "fadeIn"
                }
            }
        });
        
        
        var wd = $("#items-window");
         var opened = false;
         var loaded = false;
         
        $('#add-bannerpage').click(function(e) {
            e.preventDefault();
            if (opened==true) return false;
            var wdd = wd.data("kendoWindow");
            if (loaded===false) {
                wdd.refresh("index.php?response=block&show=banners&voider=showmap");
            }
            wdd.center().open();
         });
       
        wd.kendoWindow({
                 width: "600px",
                 height: "400px",
                 title: "Sitemap",
                 modal: false,
                 actions: [
                                "Minimize",
                                "Close"
                            ],
                 visible: false,
                 animation: { close : { duration: 200 }, open : { duration: 200 } },
                 resize: function(e) {
                     triggerResize();
                 },
                 close: function(e) {
                     opened = false;
                 },
                 open: function(e) {
                    opened = true;
                    loaded = true;
                 }
             });
        
       
            $('#items-window').on('click','#addurl',function(e) {
                    e.preventDefault();
                    var tree = $("#treeview").data("kendoTreeView");
                    var node = tree.select();
                    var dataItem = tree.dataItem(node);
                    addToLost(dataItem);
            });
            
            $('#itemlist').on('click','.b-remove',function(e) {
                e.preventDefault();
                $(this).closest('li').remove();
            });
            
            $('#itemlist').on({
                mouseenter: function () {
                    $(this).css('background-color', '#ededed'); //a5ce39
                },
                mouseleave: function () {
                    $(this).css('background-color', '');
                }
            },'li');
            
            
            
            function addToLost(dataItem) {
                var it = $('#itemlist').find('li input[value="' + dataItem.id + '"]');
                if (it.length>0) {
                    alert('Page already exists in list');
                    return false;
                }
                var li = $('<li></li>');
                li.append('<input type="hidden" name="url['+ dataItem.id +'][id]" value="' + dataItem.id + '" />');
                li.append(dataItem.displaytext + ' | ');
                li.append('<a href="{{siteroot}}' + dataItem.topath + '" target="_blank" style="color:#929292;">' + dataItem.topath + '</a>');
                li.append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="checkbox" name="url['+ dataItem.id +'][children]" value="1" /> Nested pages</label>');
                li.append('<a href="#" class="k-button w-button b-remove">&nbsp;</a><div class="clear"></div>');
                $('#itemlist').append(li);
            }
            
    });
</script>