<div class="page-title">
    <img src="imgs/genericapp.png" />Pages <span class="last">{{if act(=add)}}Add new page{{else act}}edit page "{{item.name}}"{{end if act}}</span>
    <div class="page-toolbar">
        <a href="?show=pages&amp;block_id=imageitems" class="k-button w-button" title="Back" style="background-image:url('imgs/left.png');">&nbsp;</a>
    </div>
</div>
<div id="page-content-wrapper">
    <form method="POST" action="index.php" id="itemform" enctype="multipart/form-data">
        {{for current.queryparams as param}}
        <input type="hidden" name="{{param.key}}" value="{{param.value}}" />
        {{end for current.queryparams}}
        <input type="hidden" name="voider" id="action" value="{{act}}"/>
        <input type="hidden" name="item[pos]" value="{{item.pos}}"/>
        <input type="hidden" name="item[block_id]" value="{{block.name}}" />
        <input type="hidden" name="id" value="{{item.id}}" />

        {{if parent_id}}
        <!--<input type="hidden" name="item[parent_id]" id="pageparent" value="{{parent_id}}" /> -->
        {{else parent_id}}
        <!--<input type="hidden" id="pageparent" value="1" /> -->
        {{end if parent_id}}
        <input type="hidden" name="item[parent_id]" id="pageparent" value="5" />

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
                            <th>Title</th>
                            <td><input type="text" id="pagesitem-name" name="item[name]" class="k-input" value="{{item.name}}" /></td>
                        </tr>
                        <tr>
                            <th>Categories</th>
                            <td id="product-parent_id" >
                                <select class="multiselect" id="catlist" multiple="multiple" data-rawname="item[cats]"></select>
                            </td>
                        </tr>
                        <tr>
                            <th>Heading</th>
                            <td><input type="text" name="item[ainfo]" class="k-input" value="{{item.ainfo}}" /></td>
                        </tr>
                        <tr>
                            <th>Subtitle</th>
                            <td><input type="text" name="item[binfo]" class="k-input" value="{{item.binfo}}" /></td>
                        </tr>
                        <tr>
                            <th>Author</th>
                            <td><input type="text" name="item[cinfo]" class="k-input" value="{{item.cinfo}}" /></td>
                        </tr>
                        <tr>
                            <th>Alias<span class="k-descr">Only lowercase Latin letters,digits and " - " sign with no spaces</span></th>
                            <td id="pages-url-object">
                                <input type="text" id="urlcache-alias" name="url[alias]" class="k-input" value="{{url.alias}}" />
                                <input type="hidden" name="url[url]" id="urlcache-url" value="{{url.url}}" />
                                <input type="hidden" name="url[componenttype]" value="pages"/>
                            </td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>
                                <input type="text" id="pagesitem-date" name="item[date_object]" value="{{item.date_object}}" />
                            </td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td class="notthis">
                                <textarea name="item[descr]" class="k-input" rows="5" cols="50" style="width: 100%; height: 40px;" >{{item.descr}}</textarea>
                            </td>
                        </tr>
                        <tr class="notthis caption">
                            <th colspan="2">Contents</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[content]" class="ckeditor" style=" margin: 0 auto;">{{item.content}}</textarea>
                            </td>
                        </tr>
                        <tr class="notthis caption">
                            <th>Main carousel image
                                <input name="thumb" type="file" />
                                <span class="k-descr">Image size {{thumbset.w}}x{{thumbset.h}}</span>
                            </th>
                            <td>
                                <div class="View-item" style="text-align: left;">
                                    <img id="item-thumb" style="max-height: 200px;" src="../{{file.thumbname}}"  />
                                    <a href="index.php?{{current.pageparams}}&voider=detach" data-itemid="{{item.id}}" data-fileid="{{file.id}}" data-filetype="thumb" class="k-button w-button a-action removefile" title="delete">&nbsp;</a>
                                </div>
                            </td>
                        </tr>
                        <tr class="notthis caption">
                            <th>Image
                                <input name="image" type="file" />
                                <span class="k-descr">Image size {{imageset.w}}x{{imageset.h}}</span>
                            </th>
                            <td>
                                <div class="View-item" style="text-align: left;">
                                    <img id="item-image" style="max-height: 200px;" src="../{{file.filename}}"  />
                                    <a href="index.php?{{current.pageparams}}&voider=detach" data-itemid="{{item.id}}" data-fileid="{{file.id}}" data-filetype="image" class="k-button w-button a-action removefile" title="delete">&nbsp;</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Tags</th>
                            <td>
                                <select id="tags" multiple="multiple">{{tags}}</select>
                            </td>
                        </tr>
                        <tr>
                            <th>Related Articles ids</th>
                            <td>
                                <input type="text" name="item[tagsb]" class="k-input" value="{{item.tagsb}}" />
                            </td>
                        </tr>
                        <tr>
                            <th>Social share image #<br/><span class="k-descr" style="font-weight:normal;font-size:11px;">Gallery index for Facebook/Telegram (1 = first gallery image). Leave empty to use cover.</span></th>
                            <td>
                                <input type="text" name="item[intsc]" class="k-input" value="{{item.intsc}}" />
                            </td>
                        </tr>
                        {{include (metas.inc)}}
                    </table>
                </div>
                <div>
                    <table class="fieldset transblock" data-lang="{{langs.1.id}}">
                        <tr>
                            <th class="trans">Title<span class="k-descr"></span></th>
                            <td><input type="text" id="pagesitem-name_lg1" name="item[name_lg1]" class="k-input" value="{{item.name_lg1}}" /></td>
                        </tr>
                        <tr>
                            <th class="trans" >Heading</th>
                            <td><input type="text" name="item[ainfo_lg1]" class="k-input" value="{{item.ainfo_lg1}}" /></td>
                        </tr>
                        <tr>
                            <th>Subtitle</th>
                            <td><input type="text" name="item[binfo_lg1]" class="k-input" value="{{item.binfo_lg1}}" /></td>
                        </tr>
                        <tr>
                            <th>Author</th>
                            <td><input type="text" name="item[cinfo_lg1]" class="k-input" value="{{item.cinfo_lg1}}" /></td>
                        </tr>
                        <tr>
                            <th class="trans" >Description</th>
                            <td class="notthis" >
                                <textarea name="item[descr_lg1]" class="k-input" rows="5" cols="50" style="width: 100%; height: 40px;" >{{item.descr_lg1}}</textarea>
                            </td>
                        </tr>
                        <tr class="notthis caption">
                            <th colspan="2" class="trans" >Contents</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[content_lg1]" class="ckeditor" style=" margin: 0 auto;">{{item.content_lg1}}</textarea>
                            </td>
                        </tr>

                        {{include (metas_lg1.inc)}}

                    </table>
                </div>
                <div>
                    <table class="fieldset transblock" data-lang="{{langs.2.id}}">
                        <tr>
                            <th class="trans" >Title<span class="k-descr"></span></th>
                            <td><input type="text" id="pagesitem-name_lg2" name="item[name_lg2]" class="k-input" value="{{item.name_lg2}}" /></td>
                        </tr>
                        <tr>
                            <th class="trans" >Heading</th>
                            <td><input type="text" name="item[ainfo_lg2]" class="k-input" value="{{item.ainfo_lg2}}" /></td>
                        </tr>
                        <tr>
                            <th>Subtitle</th>
                            <td><input type="text" name="item[binfo_lg2]" class="k-input" value="{{item.binfo_lg2}}" /></td>
                        </tr>
                        <tr>
                            <th>Author</th>
                            <td><input type="text" name="item[cinfo_lg2]" class="k-input" value="{{item.cinfo_lg2}}" /></td>
                        </tr>
                        <tr>
                            <th class="trans" >Description</th>
                            <td class="notthis" >
                                <textarea name="item[descr_lg2]" class="k-input" rows="5" cols="50" style="width: 100%; height: 40px;" >{{item.descr_lg2}}</textarea>
                            </td>
                        </tr>
                        <tr class="notthis caption">
                            <th colspan="2" class="trans" >Contents</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[content_lg2]" class="ckeditor" style=" margin: 0 auto;">{{item.content_lg2}}</textarea>
                            </td>
                        </tr>

                        {{include (metas_lg2.inc)}}

                    </table>
                </div>
            </div>
            <div class="submitrow">
                <input type="submit" class="k-button submit" value="Save" />
            </div>
        </div>



    </form>

    {{if act(=edit)}}
        <table class="fieldset">
            <tr class="notthis caption">
                <th colspan="2">Gallery</th>
            </tr>
            <tr class="notthis">
                <th><input name="files[]" id="files" type="file" /></th>
                <td>
                    <div id="listView" class="listView"></div>
                </td>
            </tr>
        </table>
    {{end if act}}

    </div>
    <div id="linkwindow">
    Additional Data:
    <textarea rows="3" id="link-box" class="k-input" style="width: 100%"></textarea>
    <br><br>Additional Data (hy):
    <textarea rows="3" id="link-box_lg1" class="k-input" style="width: 100%"></textarea>
    <br><br>Additional Data (ru):
    <textarea rows="3" id="link-box_lg2" class="k-input" style="width: 100%"></textarea>
    <div style="text-align: right;margin: 20px 0;">
        <a href="#" id="setLink" class="k-button">Save</a>
    </div>
    </div>
    <script type="text/x-kendo-tmpl" id="template">
    <div class="listView-item">
        <a href="../${filename}" target="_blank" title="открыть полный размер" class="openfullimage"><img src="../${thumbname}"  /></a>
        <a href="voider=detachgal&id=${id}&response=json" class="k-button w-button a-action removefile" rel="Delete?" title="Delete">&nbsp;</a>
    </div>
    </script>
</div>
<script >
    $(document).ready(function () {

        $('#pagesitem-date').kendoDatePicker();


        $('#urlcache-alias').kendotranslit({
            from: '#pagesitem-name'
        });

        $('#urlcache-url').kendourlbuilder({
            from: '#urlcache-alias',
            parentid: '#pageparent',
            itemtype: 'pages',
            mode: '{{act}}',
            params: {
                urltype: 'html'
            }
        });

        $('#catlist').kendoMultiSelect({
            dataSource: {
                data: {{cats}}
            },
            dataValueField: 'id',
            dataTextField: 'name',
            userInput: true,
            value: {{itemcats}}
        });

        $("#tags").kendoMultiSelectEx({
            userInput: true,
            value: '{{item.tags}}'.split(',')
        });


        $('#itemform').myForm({
            beforeSubmit : function(arr, $form, options) {
                var tags = $("#tags").data("kendoMultiSelectEx").value();
                var ravVle = '';
                if (tags.length>0) {
                    ravVle = tags.join(',');
                }
                arr.push({name:'item[tags]', value: ravVle });


                var ctsValues = $('#catlist').data("kendoMultiSelect").value();
                for(var idx=0;idx<ctsValues.length;idx++) {
                    arr.push({name:'item[cats][]', value: ctsValues[idx] });
                }
                return true;
            },
            showSuccess: true,
            onSuccess: function (data) {
                if (data.data && data.data.filename!==undefined) {
                    $('#item-image').attr("src",'../' + data.data.filename);
                }
                if (data.data && data.data.thumbname!==undefined) {
                    $('#item-thumb').attr("src",'../' + data.data.thumbname);
                }
                return true;
            }
        });

        $('.removefile').click(function(e) {
            e.preventDefault();
            if (!confirm('Delete?'))
                return;
            var url = $(this).attr('href');
            var itemid = $(this).data("itemid");
            var fileid = $(this).data("fileid");
            var filetype = $(this).data("filetype");

            makeRequest({
                url: url,
                method: 'POST',
                dataType: 'json',
                data: { response: 'json', itemid: itemid, id: fileid, type: filetype },
                onSuccess: function(data,status) {
                    $('#item-' + filetype).attr("src",'');
                }
            });
        });

        $('#linkwindow').kendoWindow({
            width: "500px",
            modal: true,
            visible: false,
            animation: {close: {duration: 200}, open: {duration: 200}}
        });
        var wnd = $('#linkwindow').data("kendoWindow");

       {{if act(=edit)}}

        var dataSource = new kendo.data.DataSource({
                    transport: {
                        read: {
                            url: "index.php?show=pages&voider=files&parent_id={{item.id}}&group=galery&response=json",
                            contentType: "application/json; charset=utf-8",
                            type: "POST",
                            dataType: "json"
                        }
                    },
                    requestEnd: function(e) {
                      if (e.response) {
                        if (e.response.error) {
                            showError('Error',e.response.error);
                        }
                      } else {
                          showError('Error','Unknown error');
                      }
                    },
                    error:  function(e) {
                       var mess = 'Load error';
                       if (e.xhr && e.xhr.responseText !=undefined) {
                           mess = e.xhr.responseText;
                       }
                       showError('Ошибка',mess);
                    },
                    schema: {
                        data: function(data) {
                            return data.rows || [];
                        }
                    }
                });
        $("#listView").kendoListView({
                dataSource: dataSource,
                template: kendo.template($("#template").html())
            });


        $("#files").kendoUpload({
            async: {
                saveUrl: "index.php?show=pages&voider=attach&response=block&group=galery&id={{item.id}}",
                removeUrl: "remove",
                autoUpload: true
            },
            upload: function(e) {
                var files = e.files;

                // Check the extension of each file and abort the upload if it is not .jpg
                $.each(files, function() {
                    var spans = $('ul.k-upload-files').find("span[title='"+this.name+"']").length;
                    if (spans>1) {
                        showError('Error','The file already exists in list');
                        e.preventDefault();
                    }
                });
            },
            success: function(e) {
                // complete one
                //e.operation="upload"
                console.log("success");
                if (e.operation="upload") {
                    if (e.response && e.response.status=='ok') {
                        $.each(e.files, function() {
                            var spans = $('ul.k-upload-files').find("span[title='"+this.name+"']");
                            spans.closest('li').fadeOut('slow', function() {
                               $(this).remove();
                            });
                        });

                        var dataItem = dataSource.insert(0, { id: e.response.id, parent_id:"{{item.id}}", filename : e.response.filename, thumbname : e.response.thumbname, isdefault: "0"  });

                    }
                }
            },
            complete: function(e) {
                // complete all
                // The upload is now idle
            },
            error: function(e) {
                // Array with information about the uploaded files
               var mess = 'File load error';

               if (e.XMLHttpRequest && e.XMLHttpRequest.responseText !=undefined) {
                   var err = tryParseJson(e.XMLHttpRequest.responseText);

                   if (err && err.error) {
                       mess = err.error;
                   }
               }
               $.each(e.files, function() {
                    var spans = $('ul.k-upload-files').find("span[title='"+this.name+"']");
                    spans.closest('li').find('span.k-descr').remove();
                    spans.closest('li').append('<span class="k-descr">' + mess + '</span>');
                });
                /*
                e.files Array
                List of the files that were uploaded or removed . Each file has:
                name
                extension - the file extension inlcuding the leading dot - ".jpg", ".png", etc.
                size - the file size in bytes (null if not available)
                e.operation String
                "upload" or "remove".
                e.XMLHttpRequest Object
                */
                //var files = e.files;
                //if (e.operation == "upload") {
                //    alert("Failed to uploaded " + files.length + " files");
                //}
                //showError('Ошибка',e.XMLHttpRequest.responseText + '<div style="color: black !important;">' + e.XMLHttpRequest.responseText + '</div>');
            },
            localization : {
                cancel: 'Cancel',
                dropFilesHere: 'Drop files here',
                remove: 'Remove',
                retry: 'Retry',
                select: 'Select',
                statusFailed: 'Error',
                statusUploaded: 'Uploaded',
                statusUploading: 'Uploading',
                uploadSelectedFiles: 'Upload'
            }
        });

        $('#listView').on('click','.a-action',function(e) {
            e.preventDefault();

            //if (!confirm('Удалить?')) return false;

            var rl = $(this).attr("rel");
            if (rl!=undefined && rl!='') {
                if (confirm(rl)==false) return false;
            }

            var url = $(this).attr('href');

            makeRequest({
                    data: 'response=json&show=pages&' + url,
                    onSuccess: function(data,status) {
                        var list = $("#listView").data("kendoListView");
                        list.dataSource.read();
                    }
                });
        });

        var curFile = 0;
        $('#listView').on('click','a.makedefault',function(e) {
            e.preventDefault();
            var index = $(this).closest('.listView-item').index();
            var fle = (dataSource.view()[index]).toJSON();
            curFile = fle.id;
           $('#link-box').val(fle.descr);
           $('#link-box_lg1').val(fle.descr_lg1);
           $('#link-box_lg2').val(fle.descr_lg2);
            wnd.open().center();

        });
        $('#setLink').click(function(e) {
            e.preventDefault();
            var urlSet = $('#link-box').val();
            var urlSet1 = $('#link-box_lg1').val();
            var urlSet2 = $('#link-box_lg2').val();
            wnd.close();
            makeRequest({
                    data: { response: 'json',show: 'pages', voider: 'setlink', values: { descr: urlSet, descr_lg1: urlSet1, descr_lg2: urlSet2} , id: curFile  }, //   'response=json&show=pages&voider=setlink&value=' + urlSet + '&id=' + curFile
                    onSuccess: function(data,status) {
                        var list = $("#listView").data("kendoListView");
                        list.dataSource.read();
                    }
                });

        });

        {{end if act}}

        $('#main-tabstrip').kendoTabStrip({
            animation:  {
                open: {
                    effects: "fadeIn"
                }
            }
        });
    });
</script>
