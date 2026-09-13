<div class="page-title">
    <img src="imgs/genericapp.png" />Pages <span class="last">{{if act(=add)}}Add new page{{else act}}edit page "{{item.name}}"{{end if act}}</span>
    <div class="page-toolbar">
        <a href="?show=pages&amp;block_id=fixed" class="k-button w-button" title="Back" style="background-image:url('imgs/left.png');">&nbsp;</a>
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
        <input type="hidden" name="item[parent_id]" id="pageparent" value="{{parent_id}}" />
        {{else parent_id}}
        <input type="hidden" id="pageparent" value="1" />
        {{end if parent_id}}
        
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
                            <td><input type="text" id="pagesitem-name" name="item[name]" class="k-input" value="{{item.name}}" /></td>
                        </tr>
                        <tr>
                            <th>Heading</th>
                            <td><input type="text" name="item[ainfo]" class="k-input" value="{{item.ainfo}}" /></td>
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
                            <th>Geolocation</th>
                            <td>
                                <div id="geo-wrapper">
                                    Lat <input type="text" class="k-input geoInput" id="itemgeo-lat" value="" style="width: 180px;" /> 
                                    Long <input type="text" class="k-input geoInput" id="itemgeo-long" value="" style="width: 180px;" />
                                    Zoom <input type="text" class="k-input geoInput" id="itemgeo-zoom" value="" style="width: 60px;" />
                                </div>
                                <div id="map-container">
                                    
                                </div>
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
                        <tr>
                            <th>Footer Contacts</th>
                            <td class="notthis">
                                <textarea name="item[descr]" class="k-input" rows="5" cols="50" style="width: 100%; height: 150px;" >{{item.descr}}</textarea>
                            </td>
                        </tr>
                        
                        {{include (metas.inc)}}
                        
                    </table>
                </div>
                <div>
                    <table class="fieldset transblock" data-lang="{{langs.1.id}}">
                        <tr>
                            <th class="trans">Name</th>
                            <td><input type="text" id="pagesitem-name_lg1" name="item[name_lg1]" class="k-input" value="{{item.name_lg1}}" /></td>
                        </tr>
                        <tr>
                            <th class="trans">Heading</th>
                            <td><input type="text" name="item[ainfo_lg1]" class="k-input" value="{{item.ainfo_lg1}}" /></td>
                        </tr>
                        <!--
                        
                        -->
                        <tr class="notthis caption">
                            <th colspan="2" class="trans">Contents</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[content_lg1]" class="ckeditor" style=" margin: 0 auto;">{{item.content_lg1}}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th class="trans">Footer contacts</th>
                            <td class="notthis" >
                                <textarea name="item[descr_lg1]" class="k-input" rows="5" cols="50" style="width: 100%; height: 150px;" >{{item.descr_lg1}}</textarea>
                            </td>
                        </tr>
                        {{include (metas_lg1.inc)}}
                        
                    </table>
                </div>
                <div>
                    <table class="fieldset transblock" data-lang="{{langs.2.id}}">
                        <tr>
                            <th class="trans" >Name</th>
                            <td><input type="text" id="pagesitem-name_lg2" name="item[name_lg2]" class="k-input" value="{{item.name_lg2}}" /></td>
                        </tr>
                        <tr>
                            <th class="trans" >Heading</th>
                            <td><input type="text" name="item[ainfo_lg2]" class="k-input" value="{{item.ainfo_lg2}}" /></td>
                        </tr>
                        <!--
                        
                        -->
                        <tr class="notthis caption">
                            <th colspan="2" class="trans" >Contents</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[content_lg2]" class="ckeditor" style=" margin: 0 auto;">{{item.content_lg2}}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th class="trans" >Footer contacts</th>
                            <td class="notthis" >
                                <textarea name="item[descr_lg2]" class="k-input" rows="5" cols="50" style="width: 100%; height: 150px;" >{{item.descr_lg2}}</textarea>
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




</div>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false&amp;{{if mapkey}}key={{mapkey}}&amp;{{end if mapkey}}language=en"></script>
<script >
    var itemDescr = '{{item.tags}}';
    
    var map = null;
    var marker = null;
   
    var lat = 40.213875;
    var long = 44.521431;
    var zoom = 12;
    
    if (itemDescr!=='') {
        var parts = itemDescr.split(';');
        lat =parseFloat( parts[0]);
        long =parseFloat( parts[1]);
        zoom = parseFloat(parts[2]);
    } 
    if (zoom===0) {
        zoom = 12;
    }
    $('#itemgeo-lat').val(lat);
    $('#itemgeo-long').val(long);
    $('#itemgeo-zoom').val(zoom);

    function placeMarker(location) {
        if (marker===null) {
            marker = new google.maps.Marker({
                position: location,
                map: map,
                draggable: false
            });
        } else {
            marker.setPosition(location);
        }
        map.panTo(location);
    }

    function setZoom(level) {
        if (level===0) {
            level = 12;
        }
        map.setZoom(level);
    }


    $('#geo-wrapper .geoInput').change(function() {
        var lat = parseFloat($('#itemgeo-lat').val());
        var long = parseFloat($('#itemgeo-long').val());
        var lvl = parseFloat($('#itemgeo-zoom').val());
        var loc = new google.maps.LatLng(lat, long);
        placeMarker(loc);
        setZoom(lvl);
    });


    function initializeMap() {
        var mapCanvas = document.getElementById('map-container');
        var loc = new google.maps.LatLng(lat, long)
        var mapOptions = {
          center: loc,
          zoom: zoom,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          scrollwheel: false
        };
        map = new google.maps.Map(mapCanvas, mapOptions);
        marker = new google.maps.Marker({
            position: loc,
            map: map,
            draggable: false
        });
        
        google.maps.event.addListener(map, 'click', function(event) {
            placeMarker(event.latLng);
            $('#itemgeo-lat').val(event.latLng.lat());
            $('#itemgeo-long').val(event.latLng.lng());
            $('#itemgeo-zoom').val(map.getZoom());
        });
    }
    google.maps.event.addDomListener(window, 'load', initializeMap);


    $(document).ready(function () {
      
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
       
        $('#itemform').myForm({
            beforeSubmit : function(arr, $form, options) {
                var valid = true;
                var lat = parseFloat($('#itemgeo-lat').val());
                var long = parseFloat($('#itemgeo-long').val());
                var lvl = parseFloat($('#itemgeo-zoom').val());
                var geoval = lat+';'+long+';'+lvl;
                arr.push({name:'item[tags]', value: geoval });
                $('.temporary').remove();
                return valid;
            }
        });
        $('#main-tabstrip').kendoTabStrip({
            animation:  {
                open: {
                    effects: "fadeIn"
                }
            }
        });
    });
</script>