<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false{{if mapkey}}&amp;key={{mapkey}}{{end if mapkey}}"></script>
<script src="js/raty/jquery.raty.min.js"></script>
<div >
    <div id="item-list-head">
        <a href="#" class="active" data-status="1">{{dict.STATUS_NEWS}}</a>
        <a href="#" data-status="2">{{dict.STATUS_ACCEPTEDS}}</a>
        <a href="#" data-status="192">{{dict.STATUS_CANCELEDS}}</a>
        <span class="clear"></span>
    </div>
    <div id="items-list" class="itemlistview">
        
    </div>
    <span class="clear"></span>
</div>
<div id="items-pager" class="paging">
    
</div>
<div id="map-window">
    <div id="map-block">
        <img src="imgs/loading.gif" style=" position: absolute; top: 50%; left: 50%; margin-top: -24px; margin-left: -24px; " alt="loading..." />
    </div>
    {{if member}}
    <form method="POST" action="{{curlangp}}account/itemschange.html" id="geoform">
        <input type="hidden" id="itemgeo" name="geo" value="" />
        <input type="hidden" id="itemid" name="id" value="" />
        <input type="hidden" name="act" value="geo" />
        <input type="hidden" name="th" value="{{th}}" />
        <div class="submitrow">
        <input type="submit" class="flatbutton" value="{{dict.SAVE}}" />
        </div>
    </form>
    {{end if member}}
</div>
<div id="offer-window">
    {{include (account/offer.inc)}}
</div>
{{if member}}
{{else member}}
<div id="rate-window">
    <div class="h2">{{dict.YOURREVIEWSERVICE}}</div>
    <form method="POST" action="{{curlangp}}account/review/" id="frm_review">
        <input type="hidden" name="act" value="offer" />
        <input type="hidden" name="th" value="{{th}}" />
        <input type="hidden" id="revitem" name="item_id" value="" />
        <!-- <input type="hidden" id="revscore" name="rate" value="" /> -->
        <div id="rate-box">
            <div id="ratea">
                <label>{{dict.RATEA}}</label>
                <div class="ratestars" data-name="ratea"></div>
                <span class="clear"></span>
            </div>
            <div id="rateb">
                <label>{{dict.RATEB}}</label>
                <div class="ratestars" data-name="rateb"></div>
                <span class="clear"></span>
            </div>
            <div id="ratec">
                <label>{{dict.RATEC}}</label>
                <div class="ratestars" data-name="ratec"></div>
                <span class="clear"></span>
            </div>
        </div>
        <div style="margin-top: 30px;">
            <label>{{dict.COMMENT}}</label>
            <textarea name="review[descr]" class="m-input" id="review-descr" style="width: 100%; height: 60px;"></textarea>
        </div>
        <div id="rev-error" class="errobox tr">

        </div>
        <div class="submitrow">
            <input type="submit" class="flatbutton" value="{{dict.SAVE}}">
        </div>
    </form>
</div>
{{end if member}}
<script id="item-template" type="text/x-kendo-template">
    <div class="bitem #: getBlockVisibleClass(showblock) #">
        <div>
            <div class="ohead">
                <i>#: id #</i><span class="itext location">#: tcountry_src# - #: tcountry_dst #</span><span class="status">#: getstatus(status,accprice)#</span>
            </div>
            <div class="obody">
                <div class="descr-box">
                    <p><span>#: tcountry_src#,</span> #: city_src #</p>
                    <p><span>#: tcountry_dst#,</span> #: city_dst #</p>
                    <p><span>{{dict.PARCEL}}:</span> #: size # {{dict.MCUBE}}, #: weight # {{dict.KG}}</p>
                    <p><span>{{dict.DELDATE}}:</span> #: pickdate_start_tz#-#: pickdate_end_tz#</p>
                    <p><span>{{dict.CARTYPE}}:</span> #: propsa_text#</p>
                    <p><span>{{dict.LANGB}}:</span> #: langs#</p>
                    <p class="timer">#= getTimer(remain) #</p>
                    <p><span>{{dict.PDESCR}}:</span> #: descr#</p>
                    <p><span>{{dict.NOTES}}:</span> #: notes#</p>
                    <span class="clear" style="height: 65px;"></span>
                </div>
                <div class="offer-box">
                    #= renderRows(rows,status,remain) #
                    # if (memberVisible(status,member)) { #
                        <div class="offer-member-box">
                            <div class="descr-box">
                                <p><span>{{dict.COMPNAME}}:</span> #: member.name #</p>
                                <p><span>{{dict.RATE}}:</span> <span class="rate">#: member.avgrate#</span></p>
                                <p><span>{{dict.CR}}:</span> <span class="cr">#: member.avgcr #%</span></p>
                                <p><span>{{dict.ADDRESS}}:</span> #: member.m_address #, #: member.m_countryraw #</p>
                                <p><span>{{dict.PHONE}}:</span> #: member.phone #</p>
                                <p><span>{{dict.EMAIL}}:</span> #: member.m_email #</p>
                                <p><span>{{dict.LANGB}}:</span> #: member.langs #</p>
                                <p><span>{{dict.MCARS}}:</span> #: member.propsa #</p>
                                <p><span>{{dict.WORKHOURS}}:</span> #= member.m_hours #</p>
                            </div>
                        </div>
                    # } #
                    <div class="tr errobox">#: errmessage#</div>
                </div>
                <span class="clear"></span>
                <div class="tool-box">
                    <a href="showmap" class="icon ic-map btn-map #: geoVisible(geo,status)# flatbutton whitebutton" title="{{dict.SHOWONMAP}}" data-geo="#: geo #">&nbsp;</a>
                    <a href="makeoffer" class="flatbutton whitebutton btn-offer #: btnOfferVisible(status,remain) #" title="{{dict.MAKEOFFER}}">{{dict.MAKEOFFER}}</a>
                    <a href="cancel" class="flatbutton acceptbutton btn-state #: btnStatevisibleClass(status,64,remain) #" title="{{dict.CANCEL}}" data-status="64" data-quest="{{dict.CANCEL}}">{{dict.CANCEL}}</a>
                    <a href="makeoffer" class="flatbutton whitebutton btn-rate #: rateBtnVisibleClass(status,marker,elapsed) #" title="{{dict.LIVEREVIEW}}">{{dict.LIVEREVIEW}}</a>
                </div>
            </div>
            
        </div>
    </div>
</script>
<script >
    var isMember = {{ismember}};
    var statuses = {{statuses}};
    
    
    var allowRate = parseInt("{{allowrate}}");
    
    var mapinited = false,mmarker = null,map = null, currentLocation = {};;
    
    var allowStatuses = {{allowstatuses}}
    
    function getstatus(statint,accprice) {
        return (3 & statint)>0?(accprice==''?'':accprice + ' {{dict.AMD}}'):statuses['s' + statint];
    }
    
    function getTimer(remain) {
        return remain>0?'<span>{{dict.DEADLINE}}:</span> '+ TimerFormatStr(remain):'{{dict.ERR_OFFERELAPSED}}';
    }
    
    function btnStatevisible(status,state,remain) {
        return ((allowStatuses[status] & state) > 0);
    }
    function btnStatevisibleClass(status,state,remain) {
        return btnStatevisible(status,state)?'':'hidden';
    }
    
    function geoVisible(geo,status) {
        return ((isMember===false && geo!=null && geo!='') || (isMember===true && (2 & status)===status) ?'':'hidden');
    }
    
    function btnOfferVisible(status,remain) {
        return (remain>0 && ((status & 1)===1 && isMember===true)?'':'hidden');
    }
    function memberVisible(status,member) {
        return (member && (status & 2)>0);
    }
    
    function rateBtnVisibleClass(status,marker,elapsedHours) {
        return (((status & 2) ===2 && (marker & 1) !==1 & isMember===false && elapsedHours>allowRate)?'':'hidden');
    }
    
    function getBlockVisibleClass(showblock) {
        return showblock===true?'visible':'';
    }
    function placeMarker(location) {
        if (mmarker===null) {
            mmarker = new google.maps.Marker({
                position: location,
                map: map,
                draggable: false
            });
        } else {
            mmarker.setPosition(location);
        }
        map.panTo(location);
    }
    
    function setZoom(level) {
        if (level===0) {
            level = 12;
        }
        map.setZoom(level);
    }
    
    function initializeGeo() {
        console.log('initilize map to',currentLocation);
        if (mapinited===false) {
            mapinited = true;
            var loc = new google.maps.LatLng(currentLocation.lat, currentLocation.long);
            var mapOptions = {
              zoom: currentLocation.zoom || 10,
              center: loc,
              mapTypeId: google.maps.MapTypeId.ROADMAP,
              scrollwheel: false
            };
            map = new google.maps.Map(document.getElementById('map-block'),mapOptions);
            placeMarker(loc)
            {{if member}}
            google.maps.event.addListener(map, 'click', function(event) {
                placeMarker(event.latLng);
                $('#itemgeo').val(event.latLng.lat()+  ';' + event.latLng.lng()+ ';'+map.getZoom());
            });
            
            {{end if member}}
        } else {
            var loc = new google.maps.LatLng(currentLocation.lat, currentLocation.long);
            placeMarker(loc)
            setZoom(currentLocation.zoom)
        }
    }
      
            
    function renderRows(rows,status,remain) {
        var ht = '<table><tbody><tr><th>{{dict.RATE}}</th><th>{{dict.PRICE}}</th><th>{{dict.DEADLINES}}</th>'+ (btnStatevisible(status,2)?'<th></th>':'') +'</tr>', i= 0;
        for(i;i<rows.length;i++) {
            //  ht = ht + '<tr data-id="'+ rows[i].id +'" class="'+ ((status & 2) > 0?'memberrow':rows[i].marked) +'"><th>{{dict.OFFER}} ' + ((status & 2) > 0?'':rows[i].rn) + '</th><td class="tr">' + rows[i].pricef + ' {{dict.AMD}}</td><td class="ratecell"><div><span class="rate" title="{{dict.RATE}}">'+ rows[i].avgrate +'</span><span class="cr" title="{{dict.CR}}">'+ rows[i].avgcr +'%</span><div class="ratetooltip tooltip"><div><label>{{dict.RATEA}}:</label> '+ rows[i].avgratea +'</div><div><label>{{dict.RATEB}}:</label> '+ rows[i].avgrateb +'</div><div><label>{{dict.RATEC}}:</label> '+ rows[i].avgratec +'</div></div></div></td><td class="timer">'+ TimerFormat(rows[i].remain) +'</td>' + (btnStatevisible(status,2)?'<td><a href="accept" title="{{dict.ACCEPT}}" class="flatbutton acceptbutton btn-state" data-status="2" data-quest="{{dict.ACCEPT}}">{{dict.ACCEPT}}</a></td>':'')+'</tr>';
             ht = ht + '<tr data-id="'+ rows[i].id +'" class="'+ ((status & 2) > 0?'memberrow':rows[i].marked) +'">' +
                     '<td class="ratecell"><div><span class="rate" title="{{dict.RATE}}">'+ rows[i].avgrate +'</span><span class="cr" title="{{dict.CR}}">'+ rows[i].avgcr +'%</span><div class="ratetooltip tooltip"><div><label>{{dict.RATEA}}:</label> '+ rows[i].avgratea +'</div><div><label>{{dict.RATEB}}:</label> '+ rows[i].avgrateb +'</div><div><label>{{dict.RATEC}}:</label> '+ rows[i].avgratec +'</div></div></div></td>' +
                     '<td class="tr">' + rows[i].pricef + ' {{dict.AMD}}</td>'+ 
                     '<td class="timer">'+ TimerFormat(rows[i].remain) +'</td>' + 
                     (btnStatevisible(status,2)?'<td><a href="accept" title="{{dict.ACCEPT}}" class="flatbutton acceptbutton btn-state" data-status="2" data-quest="{{dict.ACCEPT}}">{{dict.ACCEPT}}</a></td>':'')+
                     '</tr>';
        }
        return ht + '</tbody></table>' + ((status & 2) > 0?'':(rows.length>4?'<div class="tr"><a href="more" class="btn-loadmore">{{dict.READMORE}}</a></div>':''));
       
    }
    
    $(function() {
        $('#map-window').kendoWindow({
            width: "800px",
            title: "{{dict.MAP}}",
            modal: true,
            actions: ["Close"],
            visible: false,
            resizable: false,
            activate: initializeGeo,
            animation: { close : { duration: 200 }, open : { duration: 200 } }
        });
        var wndMap = $('#map-window').data("kendoWindow");
        
        $('#offer-window').kendoWindow({
            width: "400px",
            title: "{{dict.NEWOFFER}}",
            modal: true,
            actions: ["Close"],
            visible: false,
            resizable: false,
            animation: { close : { duration: 200 }, open : { duration: 200 } }
        });
        var wndOffer = $('#offer-window').data("kendoWindow");
        
        
        
        {{if member}}
        
        
        
        $('#geoform').myForm({
            onSuccess: function(data,status) { 
                wndMap.close();
            },
            wrapper: function() { return $('#geoform').closest('.k-window');}
        });
        $('#newoffer-form').myForm({
            onSuccess: function(data,status) { 
                wndOffer.close();
                var dataitem = dataSource.getByUid($('#iuid').val());
                if (!dataitem) 
                    return;
                dataitem.set('rows',data.item.rows);
            },
            onError: function(error,responseText) { 
                console.log(error);
                $('#offer-error').html(error.error);
                return false; 
            },
            wrapper: function() { return $('#newoffer-form').closest('.k-window');}
        });
        {{else member}}
        $('#rate-window').kendoWindow({
            width: "500px",
            title: "{{dict.LIVEREVIEW}}",
            modal: true,
            actions: ["Close"],
            visible: false,
            resizable: false,
            animation: { close : { duration: 200 }, open : { duration: 200 } }
        });
        var wndRate = $('#rate-window').data("kendoWindow");
        var rateSender = null;
        
        $('.ratestars').each(function(index,el) {
            $(el).raty({ 
                path: 'js/raty/img',
                scoreName: $(el).data('name'),
                number: 5,
                width: 180,
                click: function(score,evt) {
                    return true;
                }
            });
        })
        
        $('#frm_review').myForm({
            wrapper: function() { return $('#rate-window').closest('.k-window');},
            beforeSubmit: function(arr, form, options) {
                $('#rev-error').html('');
                return true; 
            },
            onSuccess: function(data,status) { 
                wndRate.close();
                if (rateSender) {
                    rateSender.addClass('hidden');
                }
            },
            onError: function(error,responseText) { 
                if (error.invalids) {
                    $('#'+error.invalids[0]).invalid();
                } else {
                    $('#rev-error').html(error.error);
                }
                return false; 
            }
        });
        {{end if member}}
        
        var currentStatus = 1;
        var dataSource = new kendo.data.DataSource({
            transport: {
                read: {
                    url: "{{curlangp}}account/items.html",
                    dataType: "json",
                    type: "POST",
                    data: {
                       
                    }
                },
                parameterMap: function (data, type) {
                    if (type == "read") {
                        data.status = currentStatus;
                        return data;
                    }
                }
            },
            requestStart: function(e) {
                $('#items-list').block({message: '<img src="imgs/loading.gif" />'});
            },
            requestEnd: function(e) {
                $('#items-list').unblock();
            },   
            schema: {
                data: "items",
                errors: "error",
                total: "total",
                model: {
                    id: "id",
                    fields: {}
                }
            },
            pageSize: 7,
            serverPaging: true,
            serverFiltering: false
        });
        
        $('#item-list-head > a').click(function(e) {
            e.preventDefault();
            $('#item-list-head > a').removeClass('active');
            $(this).addClass('active');
            currentStatus = $(this).data('status');
            dataSource.page(1);
        });
        
        $('#items-list').on('click','.btn-loadmore',function(e) {
            e.preventDefault();
            var that = $(this),block = $(this).closest('.bitem'),
                dataitem = dataSource.getByUid($(this).closest('.bitem').data('uid'));
            if (dataitem) {
                makeRequest({
                url: '{{curlangp}}account/items.html',
                data: { response: 'json', act: 'offers', id: dataitem.id},
                onSuccess: function(data,status) { 
                    if (data.success && data.items) {
                        dataitem.set("rows",data.items);
                        
                    }
                    return false
                },
                onError: function(error,responseText) { 
                    console.log(error);
                    return false; 
                },
                wrapper: block
            });
            }
        });
        
        $('#items-list').on('click','.btn-map',function(e) {
            e.preventDefault();
            var that = $(this),block = $(this).closest('.bitem'),
                dataitem = dataSource.getByUid($(this).closest('.bitem').data('uid'));
            currentLocation = null;
            if (dataitem) {
                if (isMember===true) {
                    if (dataitem.geo==null || dataitem.geo=='') {
                        currentLocation = { lat: 40.20758201297572, long: 44.53729748725891, zoom: 10 };
                    } else {
                        var parts = dataitem.geo.split(';');
                        currentLocation = {
                            lat: parseFloat( parts[0]),
                            long: parseFloat( parts[1]),
                            zoom: parseFloat( parts[2])
                        }
                    }
                    $('#itemgeo').val(currentLocation.lat + ';' + currentLocation.long + ';' + currentLocation.zoom);
                    $('#itemid').val(dataitem.id);
                    wndMap.open().center();
                } else {
                    if (dataitem.geo && dataitem.geo!==null && dataitem.geo!=='') {
                        var parts = dataitem.geo.split(';');
                        currentLocation = {
                            lat: parseFloat( parts[0]),
                            long: parseFloat( parts[1]),
                            zoom: parseFloat( parts[2])
                        }
                        wndMap.open().center();
                    } 
                }
            }
        });
        
        $('#items-list').on('click','.btn-state',function(e) {
            e.preventDefault();
            
            var that = $(this),block = $(this).closest('.bitem'),oid = $(this).closest('tr').data('id'),
                dataitem = dataSource.getByUid($(this).closest('.bitem').data('uid'));
            if (!confirm('{{dict.CHANGESTATUS}} "' + that.data('quest') + '"?')) {
                return false;
            }
            if (dataitem) {
                makeRequest({
                url: '{{curlangp}}account/itemschange.html',
                data: { response: 'json', status: that.data("status"), id: dataitem.id, oid: oid, th: '{{th}}'},
                onSuccess: function(data,status) { 
                    if (data.success && data) {
                        dataitem.set("status",data.status);
                    }
                    return false
                },
                onError: function(error,responseText) { 
                    console.log(error);
                    dataitem.set('errmessage',error.error);
                    return false; 
                },
                wrapper: block
            });
            }
        });
        
        $('#items-list').on('click','.btn-offer',function(e) {
            e.preventDefault();
            var that = $(this),block = $(this).closest('.bitem'),oid = $(this).closest('tr').data('id'),
                dataitem = dataSource.getByUid($(this).closest('.bitem').data('uid'));
            if (!dataitem) 
                return;
            
            $('#oitemid').val(dataitem.id);
            $('#iuid').val($(this).closest('.bitem').data('uid'))
            $('#el-offer-amount').data("kendoNumericTextBox").value(null);
            $('#el-offer-dateend').data("kendoNumericTextBox").value(null);
            $('#offer-error').html('');
            $('#calculated-value').html('');
            wndOffer.open().center();
            
        });
        
        $('#items-list').on('click','.btn-rate',function(e) {
            e.preventDefault();
            var that = $(this),block = $(this).closest('.bitem'),
                dataitem = dataSource.getByUid(block.data('uid'));
            if (!dataitem) 
                return;
            rateSender = that;
            $('#revitem').val(dataitem.id);
            $('#review-descr').val('');
            $('#rev-error').html('')
            $('.ratestars').each(function(index,el) {
                e.preventDefault();
                $(el).raty('cancel',true);
            });
            wndRate.open().center();
        });
        
       
        
        
        $('#items-pager').html('').kendoPager({
            info: false,
            previousNext: false,
            dataSource: dataSource,
            selectTemplate: '<li class="curpagenum"><span>#=text#</span></li>',
            linkTemplate: '<li class="pagenum"><a href="{{curlangp}}account/items.html?page=#=idx#" data-#=ns#page="#=idx#">#=text#</a></li>'
        });
        
        
        $("#items-list").kendoListView({
            dataSource: dataSource,
            template: kendo.template($("#item-template").html())
        });
        
        
        $('#items-list').on('click','.ohead',function(e) {
            e.preventDefault();
            var that = $(this),block = $(this).closest('.bitem'),
                dataitem = dataSource.getByUid(block.data('uid'));
            
            dataitem.set('showblock',!dataitem.get('showblock'));
            
        });
        
        
    });
    
    
</script>