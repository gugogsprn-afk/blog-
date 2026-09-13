<script  src="js/plugins/timepicker/jquery-clockpicker.min.js" ></script>
<script  src="js/jquery.menuwind.js" ></script>
<div class="page-title">
    <img src="imgs/genericapp.png" />Рестораны <span class="last">{{if act(=add)}}Добавление нового{{else act}}Редактирование "{{item.name}}"{{end if act}}</span>
    <div class="page-toolbar">
        <a href="?show=catalog&id={{parent.id}}" id="apptoolbar-add" class="k-button w-button" title="Назад" style="background-image:url('imgs/left.png');">&nbsp;</a>
    </div>
</div>
<div id="page-content-wrapper">
    <form method="post" action="index.php" id="itemform">
        {{for current.queryparams as param}}
        <input type="hidden" name="{{param.key}}" value="{{param.value}}" />
        {{end for current.queryparams}}
        <input type="hidden" name="id" id="item-id" value="{{item.id}}" />
        <input type="hidden" name="voider" id="action" value="{{act}}"/>
        <input type="hidden" name="item[pos]" value="{{item.pos}}"/>
        <input type="hidden" name="url[componenttype]" value="product"/>
        <input type="hidden" name="item[parent_id]" value="0" />
        <div>
            <div id="main-tabstrip">
                <ul>
                    <li class="k-title k-state-active">Армянский</li>
                    <li class="k-title">Русский</li>
                    <li class="k-title">Английский</li>
                    {{if act(=edit)}}<li class="k-title">Меню</li>{{end if act}}
                </ul>
                <div>
                    <table class="fieldset">
                        <tr>
                            <th>Имя</th>
                            <td>
                                <input type="text" id="product-name" name="item[name]" class="k-input" value="{{item.name}}" />
                            </td>
                        </tr>
                        <tr>
                            <th>Раздел</th>
                            <td id="product-parent_id" >
                                <select class="multiselect" id="catlist" multiple="multiple" data-rawname="item[cats]"></select>
                            </td>
                        </tr>
                        <tr>
                            <th>Алиас<span class="k-descr">Только латынские строчные буквы,цифры и знак "-", без пробелов</span></th>
                            <td id="product-url-object">
                                <input type="text" id="urlcache-alias" name="url[alias]" class="k-input" value="{{url.alias}}" />
                                <input type="hidden" name="url[url]" id="urlcache-url" value="{{url.url}}" />
                            </td>
                        </tr>
                        <tr>
                            <th>Категория</th>
                            <td>
                                <input type="text" name="item[parent_name]" class="k-input" value="{{item.parent_name}}" />
                            </td>
                        </tr>
                        <tr>
                            <th>Описание</th>
                            <td class="notthis">
                                <textarea name="item[descr]" class="k-input" rows="5" cols="50" style="width: 100%; height: 65px;" >{{item.descr}}</textarea>
                            </td>
                        </tr>
                        
                        <tr class="notthis caption">
                            <th colspan="2">Содержание</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[content]" class="ckeditor" style=" margin: 0 auto;">{{item.content}}</textarea>
                            </td>
                        </tr>
                        <tr class="notthis">
                            <th>Логотип
                                <input name="thumb" type="file" />
                                <span class="k-descr">Размеры изброжения {{imagelogo}}</span>
                            </th>
                            <td>
                                <div class="View-item" style="text-align: left;">
                                    <img id="item-logo" style="max-height: 200px;" src="../{{file.thumbname}}"  />
                                    <a href="index.php?{{current.pageparams}}&voider=detachimage" data-itemid="{{item.id}}" data-fileid="{{file.id}}" data-filetype="thumb" class="k-button w-button a-action removefile" title="удалить">&nbsp;</a>
                                </div>
                            </td>
                        </tr>
                        <tr class="notthis">
                            <th>Банер
                                <input name="image" type="file" />
                                <span class="k-descr">Размеры изброжения {{imageslide}}</span>
                            </th>
                            <td>
                                <div class="View-item" style="text-align: left;">
                                    <img id="item-slide" style="max-height: 200px;" src="../{{file.filename}}"  />
                                    <a href="index.php?{{current.pageparams}}&voider=detachimage" data-itemid="{{item.id}}" data-fileid="{{file.id}}" data-filetype="image" class="k-button w-button a-action removefile" title="удалить">&nbsp;</a>
                                </div>
                            </td>
                        </tr>
                        
                        <tr id="field-member_id">
                            <th>Пользователь</th>
                            <td>
                                <input id="memberlist" type="text" name="item[member_id]" value="{{item.member_id}}" style="width: 400px;"  />
                            </td>
                        </tr>
                        <tr class="notthis caption">
                            <th colspan="2">Геопозиция</th>
                        </tr>    
                        <tr>
                            <td class="notthis" colspan="2">
                                <div id="geo-wrapper">
                                    <table class="fieldset subtable">
                                        <tr>
                                            <th>Область</th>
                                            <td><input type="text" id="product-manufact" name="item[manufact]" class="k-input" value="{{item.manufact}}" style="width: 402px;" /></td>
                                        </tr>
                                        <tr>
                                            <th>Город</th>
                                            <td><input type="text" class="k-input" id="itemgeo-city"  name="item[city]" value="{{item.city}}" style="width: 402px;" /></td>
                                        </tr>
                                        <tr>
                                            <th>Адрес</th>
                                            <td><input type="text" class="k-input" id="itemgeo-adres"  name="item[adres]" value="{{item.adres}}" style="width: 402px;" /></td>
                                        </tr>
                                        <tr>
                                            <th>Lat x Long - Zoom</th>
                                            <td>
                                                <input type="text" name="item[geo_lat]" class="k-input geoInput" id="itemgeo-lat" value="{{item.geo_lat}}" style="width: 180px;" /> x
                                                <input type="text" name="item[geo_long]" class="k-input geoInput" id="itemgeo-long" value="{{item.geo_long}}" style="width: 180px;" /> -
                                                <input type="text" name="item[geo_zoom]" class="k-input geoInput" id="itemgeo-zoom" value="{{item.geo_zoom}}" style="width: 60px;" />
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="map-container">
                                    
                                </div>
                            </td>
                        </tr>
                        
                        <tr class="notthis caption">
                            <th colspan="2">&nbsp;</th>
                        </tr>
                        <tr>
                            <th>Телефон</th>
                            <td><input type="text" name="item[phone]" class="k-input" value="{{item.phone}}" /></td>
                        </tr>
                        <tr>
                            <th>Количество звёзд</th>
                            <td>
                                <input type="number" name="item[stars]" class="k-integer" value="{{item.stars}}" />
                                <label><input type="checkbox" value="1" name="item[starsoff]" {{starsoffchecked}} /> Официальные</label>
                            </td>
                        </tr>
                        <tr>
                            <th>Методы оплаты</th>
                            <td>
                                <select class="multiselect" multiple="multiple" data-rawvalue="{{item.payments}}" data-rawname="item[payments]" data-rawsource="payments"></select>
                            </td>
                        </tr>
                        <tr>
                            <th>Дрескод</th>
                            <td>
                                <select class="multiselect" multiple="multiple" data-rawvalue="{{item.propsb}}" data-rawname="item[propsb]" data-rawsource="propsb"></select>
                            </td>
                        </tr>
                        <tr>
                            <th>Автостоянка</th>
                            <td>
                                <select class="multiselect" multiple="multiple" data-rawvalue="{{item.propsc}}" data-rawname="item[propsc]" data-rawsource="propsc"></select>
                            </td>
                        </tr>
                        <tr>
                            <th>Свойства</th>
                            <td>
                                <select class="multiselect" multiple="multiple" data-rawvalue="{{item.propsa}}" data-rawname="item[propsa]" data-rawsource="propsa"></select>
                            </td>
                        </tr>
                        <tr>
                            <th>Цена</th>
                            <td>
                                <input type="text" class="k-integer" name="item[startprice]" value="{{item.startprice}}" />
                            </td>
                        </tr>
                        <tr>
                            <th>Часы работы</th>
                            <td id="workdays" class="notthis">
                                <div>
                                    <label><input type="checkbox" name="item[workdays][0][enabled]" value="1" {{wh.0.checked}} /> Понедельник</label>
                                    <span>
                                        <input type="text" class="k-time k-input opentime" name="item[workdays][0][opentime]" value="{{wh.0.opentime}}" /> - 
                                        <input type="text" class="k-time k-input closetime" name="item[workdays][0][closetime]" value="{{wh.0.closetime}}" />
                                    </span>
                                </div>
                                <div>
                                    <label><input type="checkbox" name="item[workdays][1][enabled]" value="1" {{wh.1.checked}}/> Вторник</label>
                                    <span>
                                        <input type="text" class="k-time k-input opentime" name="item[workdays][1][opentime]" value="{{wh.1.opentime}}" /> - 
                                        <input type="text" class="k-time k-input closetime" name="item[workdays][1][closetime]" value="{{wh.1.closetime}}" />
                                    </span>
                                </div>
                                <div>
                                    <label><input type="checkbox" name="item[workdays][2][enabled]" value="1" {{wh.2.checked}}/> Среда</label>
                                    <span>
                                        <input type="text" class="k-time k-input opentime" name="item[workdays][2][opentime]" value="{{wh.2.opentime}}" /> - 
                                        <input type="text" class="k-time k-input closetime" name="item[workdays][2][closetime]" value="{{wh.2.closetime}}" />
                                    </span>
                                </div>
                                <div>
                                    <label><input type="checkbox" name="item[workdays][3][enabled]" value="1" {{wh.3.checked}}/> Четверг</label>
                                    <span>
                                        <input type="text" class="k-time k-input opentime" name="item[workdays][3][opentime]" value="{{wh.3.opentime}}" /> - 
                                        <input type="text" class="k-time k-input closetime" name="item[workdays][3][closetime]" value="{{wh.3.closetime}}" />
                                    </span>
                                </div>
                                <div>
                                    <label><input type="checkbox" name="item[workdays][4][enabled]" value="1" {{wh.4.checked}}/> Пятница</label>
                                    <span>
                                        <input type="text" class="k-time k-input opentime" name="item[workdays][4][opentime]" value="{{wh.4.opentime}}" /> - 
                                        <input type="text" class="k-time k-input closetime" name="item[workdays][4][closetime]" value="{{wh.4.closetime}}" />
                                    </span>
                                </div>
                                <div>
                                    <label><input type="checkbox" name="item[workdays][5][enabled]" value="1" {{wh.5.checked}}/> Суббота</label>
                                    <span>
                                        <input type="text" class="k-time k-input opentime" name="item[workdays][5][opentime]" value="{{wh.5.opentime}}" /> - 
                                        <input type="text" class="k-time k-input closetime" name="item[workdays][5][closetime]" value="{{wh.5.closetime}}" />
                                    </span>
                                </div>
                                <div>
                                    <label><input type="checkbox" name="item[workdays][6][enabled]" value="1" {{wh.6.checked}}/> Воскресение</label>
                                    <span>
                                        <input type="text" class="k-time k-input opentime" name="item[workdays][6][opentime]" value="{{wh.6.opentime}}" /> - 
                                        <input type="text" class="k-time k-input closetime" name="item[workdays][6][closetime]" value="{{wh.6.closetime}}" />
                                    </span>
                                </div>
                                <p>
                                    <span class="k-descr">Дополнительно</span>
                                    <textarea name="item[workdescr]" class="k-input" rows="5" cols="50" style="width: 100%; height: 65px;" >{{item.workdescr}}</textarea>
                                </p>
                            </td>
                        </tr>
                       
                        {{include (metas.inc)}}
                        
                    </table>
                </div>
                <div>
                    <table class="fieldset">
                        <tr>
                            <th class="trans" data-lang="ru">Имя</th>
                            <td>
                                <input type="text" id="product-name_lg1" name="item[name_lg1]" class="k-input" value="{{item.name_lg1}}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="trans" data-lang="ru">Категория</th>
                            <td>
                                <input type="text" name="item[parent_name_lg1]" class="k-input" value="{{item.parent_name_lg1}}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="trans" data-lang="ru">Описание</th>
                            <td class="notthis" >
                                <textarea name="item[descr_lg1]" class="k-input" rows="5" cols="50" style="width: 100%; height: 65px;" >{{item.descr_lg1}}</textarea>
                            </td>
                        </tr>
                        <tr class="notthis caption">
                            <th colspan="2" class="trans" data-lang="ru">Содержание</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[content_lg1]" class="ckeditor" style=" margin: 0 auto;">{{item.content_lg1}}</textarea>
                            </td>
                        </tr>
                        
                        <tr class="notthis caption">
                            <th colspan="2" class="trans" data-lang="ru">Геопозиция</th>
                        </tr>    
                        <tr>
                            <td class="notthis" colspan="2">
                                <div>
                                    <table class="fieldset subtable">
                                        <tr>
                                            <th>Область</th>
                                            <td><input type="text" id="product-manufact_lg1" name="item[manufact_lg1]" class="k-input" value="{{item.manufact_lg1}}" style="width: 402px;" /></td>
                                        </tr>
                                        <tr>
                                            <th>Город</th>
                                            <td><input type="text" class="k-input" id="itemgeo-city_lg1"  name="item[city_lg1]" value="{{item.city_lg1}}" style="width: 402px;" /></td>
                                        </tr>
                                        <tr>
                                            <th>Адрес</th>
                                            <td><input type="text" class="k-input"  name="item[adres_lg1]" value="{{item.adres_lg1}}" style="width: 402px;" /></td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="trans" data-lang="ru">Часы работы</th>
                            <td id="workdays" class="notthis">
                                <p>
                                    <span class="k-descr">Дополнительно</span>
                                    <textarea name="item[workdescr_lg1]" class="k-input" rows="5" cols="50" style="width: 100%; height: 65px;" >{{item.workdescr_lg1}}</textarea>
                                </p>
                            </td>
                        </tr>
                        {{include (metas_lg1.inc)}}
                        
                    </table>
                </div>
                <div>
                    <table class="fieldset">
                        <tr>
                            <th class="trans" data-lang="en">Имя</th>
                            <td>
                                <input type="text" id="product-name_lg2" name="item[name_lg2]" class="k-input" value="{{item.name_lg2}}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="trans" data-lang="en">Категория</th>
                            <td>
                                <input type="text" name="item[parent_name_lg2]" class="k-input" value="{{item.parent_name_lg2}}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="trans" data-lang="en">Описание</th>
                            <td class="notthis" >
                                <textarea name="item[descr_lg2]" class="k-input" rows="5" cols="50" style="width: 100%; height: 65px;" >{{item.descr_lg2}}</textarea>
                            </td>
                        </tr>
                        <tr class="notthis caption">
                            <th colspan="2" class="trans" data-lang="en">Содержание</th>
                        </tr>
                        <tr>
                            <td class="notthis" colspan="2" style="text-align: center;">
                                <textarea name="item[content_lg2]" class="ckeditor" style=" margin: 0 auto;">{{item.content_lg2}}</textarea>
                            </td>
                        </tr>
                        
                        <tr class="notthis caption">
                            <th colspan="2" class="trans" data-lang="en">Геопозиция</th>
                        </tr>    
                        <tr>
                            <td class="notthis" colspan="2">
                                <div>
                                    <table class="fieldset subtable">
                                        <tr>
                                            <th>Область</th>
                                            <td><input type="text" id="product-manufact_lg2" name="item[manufact_lg2]" class="k-input" value="{{item.manufact_lg2}}" style="width: 402px;" /></td>
                                        </tr>
                                        <tr>
                                            <th>Город</th>
                                            <td><input type="text" class="k-input" id="itemgeo-city_lg2"  name="item[city_lg2]" value="{{item.city_lg2}}" style="width: 402px;" /></td>
                                        </tr>
                                        <tr>
                                            <th>Адрес</th>
                                            <td><input type="text" class="k-input" name="item[adres_lg2]"  value="{{item.adres_lg2}}" style="width: 402px;" /></td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="trans" data-lang="en">Часы работы</th>
                            <td id="workdays" class="notthis">
                                <p>
                                    <span class="k-descr">Дополнительно</span>
                                    <textarea name="item[workdescr_lg2]" class="k-input" rows="5" cols="50" style="width: 100%; height: 65px;" >{{item.workdescr_lg2}}</textarea>
                                </p>
                            </td>
                        </tr>
                        {{include (metas_lg2.inc)}}
                    </table>
                </div>
                
                {{if act(=edit)}}
                <div>
                    <div id="booking-grid-wrapper">
                        <div style="margin: 10px 0;text-align: right;">
                            <a href="#" id="add-booking" class="k-button w-button" title="добавить" style="background-image:url('imgs/simple_plus.png');">&nbsp;</a>
                        </div>
                        <div id="booking-grid"></div>
                    </div>
                </div>
                {{end if act}}
            </div>
            <div class="submitrow">
                {{if act(=add)}}<span class="k-descr">Для управления изоброжениями, после добавления редактируйте товар</span> {{end if act}}<input type="submit" class="k-button submit" value="Сохранить" />
            </div>
        </div>
        
        
    </form>
    {{if act(=edit)}}
    <table class="fieldset" id="galerytable">
            <tr class="notthis caption">
                <th colspan="2">Изоброжения <span class="k-descr">Размер {{imagesize}}</span></th>
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
<div id="calwind" title="Меню">
    <form id="custombooking">
        <input type="hidden" name="item[item_id]" value="{{item.id}}" />
        <table class="fieldset">
            <tr data-bind="visible: fullmode">
                <th>Раздел</th>
                <td>
                    <select name="item[parent_id]" data-role="dropdownlist" data-bind="value: parent_id">
                        {{for ps as p}}
                        <option value="{{p.id}}">{{p.name}}</option>
                        {{end for ps}}
                    </select>
                </td>
            </tr>
            <tr>
                <th>Имя</th>
                <td><input type="text" name="item[name]" id="item-name" value="" class="k-input"  data-bind="value: name" /></td>
            </tr>
            <tr>
                <th class="trans" data-lang="ru">Имя</th>
                <td>
                    <input type="text" name="item[name_lg1]" class="k-input" value="" data-bind="value: name_lg1" />
                </td>
            </tr>
            <tr>
                <th class="trans" data-lang="en">Имя</th>
                <td>
                    <input type="text" name="item[name_lg2]" class="k-input" value="" data-bind="value: name_lg2" />
                </td>
            </tr>
            <tr data-bind="visible: fullmode">
                <th>Описание</th>
                <td class="notthis">
                    <textarea name="item[content]" class="k-input" rows="3" cols="50" style="width: 100%; " data-bind="value: content"></textarea>
                </td>
            </tr>
            <tr data-bind="visible: fullmode">
                <th class="trans" data-lang="ru">Описание</th>
                <td class="notthis" >
                    <textarea name="item[descr_lg1]" class="k-input" rows="3" cols="50" style="width: 100%; " data-bind="value: content_lg1"></textarea>
                </td>
            </tr>
            <tr data-bind="visible: fullmode">
                <th class="trans" data-lang="en">Описание</th>
                <td class="notthis" >
                    <textarea name="item[descr_lg2]" class="k-input" rows="3" cols="50" style="width: 100%; " data-bind="value: content_lg2"></textarea>
                </td>
            </tr>
            <tr data-bind="visible: fullmode">
                <th>Цена</th>
                <td>
                    <input type="text" name="item[price]" class="k-integer" value="" data-bind="value: price" />
                </td>
            </tr>
            <tr>
                <td colspan="2" class="submitrow">
                    <a href="#" class="k-button submit" id="calwind-ok" >Сохранить</a>
                </td>
            </tr>
        </table>
    </form>
</div>
<script id="position-template" type="text/x-kendo-template">
  # if (data.pos>data.minpos) { #  
  <a href="id=#: id #&voider=move&direction=toup" class="k-button w-button b-up a-action" title="move up from position #: pos #">&nbsp;</a>
  # };
  if (data.pos<data.maxpos) { #  
  <a href="id=#: id #&voider=move&direction=todown" class="k-button w-button b-down a-action" title="move down from position #: pos #">&nbsp;</a>
  # }; #
</script>
<script type="text/x-kendo-tmpl" id="template">
    <div class="listView-item">
        <a href="../${filename}" target="_blank" title="открыть полный размер" class="openfullimage"><img src="../${thumbname}"  /></a>
        <a href="voider=detach&id=${id}&response=json" class="k-button w-button a-action removefile" rel="Удалить?" title="удалить">&nbsp;</a>
        # if (isdefault==0) { #
        <a href="voider=favorite&id=${id}&parent_id={{item.id}}&response=json" class="k-button w-button a-action makedefault" title="Назначить главным">&nbsp;</a>
        # } else { #
        <span class="defaultitem">&nbsp;</span>
        # } #
    </div>
</script>
<script type="text/x-kendo-template" id="dettemplate">
    <div class="detail-wrapper">
        <div class="detail-header">
            <a href="\#" class="add-button underline" title="add item" >Добавить</a><span>&nbsp;</span>
        </div>
        <div class="detail-grid"></div>
    </div>
</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=hy"></script>
<script >
        $('.k-decimal').kendoNumericTextBox();
        $('.k-integer').kendoNumericTextBox({
            format: 'n0',
            decimals: 0
        });
        
        $('.k-datepicker').kendoDatePicker({format: "dd.MM.yyyy"});
        $('.k-multiselect').kendoMultiSelect();
        
        $('.k-time').clockpicker({
            autoclose: true,
            twelvehour: false
        });
        
        
        $('#workdays input[type="checkbox"]').change(function() {
            $(this).closest('div').find('span').css('display',(this.checked?'':'none'));
        });
        
        $('#workdays input[type="checkbox"]').each(function() {
            $(this).closest('div').find('span').css('display',(this.checked?'':'none'));
        });
       
        var map = null;
        var marker = null;
        var geocoder = null;
        
        var lat = parseFloat('{{item.geo_lat}}');
        var long = parseFloat('{{item.geo_long}}');
        var zoom = parseFloat('{{item.geo_zoom}}');
        var geoExists = true;
        if (lat===0 || isNaN(lat)) {
            lat = 40.213875;
            geoExists = false;
        }
        if (long===0 || isNaN(long)) {
            long = 44.521431;
            geoExists = false;
        }
        if (zoom===0 || isNaN(zoom)) {
            zoom = 12;
            geoExists = false;
        }
        
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
            map.panTo(location)
            geocoder.geocode({
                latLng: location
            }, function(responses) {
                if (responses && responses.length > 0) {
                  $('#itemgeo-adres').val(responses[0].formatted_address);
                }
            });
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
            geocoder = new google.maps.Geocoder();
            var mapCanvas = document.getElementById('map-container');
            var loc = new google.maps.LatLng(lat, long)
            var mapOptions = {
              center: loc,
              zoom: zoom,
              mapTypeId: google.maps.MapTypeId.ROADMAP,
              scrollwheel: false
            }
            map = new google.maps.Map(mapCanvas, mapOptions);
            if (geoExists) {
                marker = new google.maps.Marker({
                    position: loc,
                    map: map,
                    draggable: false
                });
            }
            google.maps.event.addListener(map, 'click', function(event) {
                placeMarker(event.latLng);
                $('#itemgeo-lat').val(event.latLng.lat());
                $('#itemgeo-long').val(event.latLng.lng());
                $('#itemgeo-zoom').val(map.getZoom());
            });
        }
        google.maps.event.addDomListener(window, 'load', initializeMap);
        
        
        
        $('#product-parent').kendoDropDownList({
            value: '{{item.parent_id}}'
        });
        
        
        $('#urlcache-alias').kendotranslit({
            from: '#product-name'
        });
        
        $('#urlcache-url').kendourlbuilder({
            from: '#urlcache-alias',
            parentid: '#product-parent',
            itemtype: 'product',
            mode: '{{act}}'
        });
        
        
        
        $('#product-manufact').kendoAutoComplete({
            dataTextField: "name",
            filter: "startswith",
            minLength: 1,
            dataSource: { serverFiltering: true,  transport: {read: {url: "index.php?show=product&voider=manufacts&response=json",dataType: "json", type: "POST"}}}
        });
            
       $('#product-manufact_lg1').kendoAutoComplete({
            dataTextField: "name",
            filter: "startswith",
            minLength: 1,
            dataSource: { serverFiltering: true,  transport: {read: {url: "index.php?show=product&voider=manufacts&response=json&&lang=1",dataType: "json", type: "POST"}}}
        });

        $('#product-manufact_lg2').kendoAutoComplete({
            dataTextField: "name",
            filter: "startswith",
            minLength: 1,
            dataSource: { serverFiltering: true,  transport: {read: {url: "index.php?show=product&voider=manufacts&response=json&lang=2",dataType: "json", type: "POST"}}}
        });    
        
        
    
    
        $('#itemgeo-city').kendoAutoComplete({
            dataTextField: "name",
            filter: "startswith",
            minLength: 1,
            dataSource: { serverFiltering: true,  transport: {read: {url: "index.php?show=product&voider=cities&response=json",dataType: "json", type: "POST"}}}
        });
            
       $('#itemgeo-city_lg1').kendoAutoComplete({
            dataTextField: "name",
            filter: "startswith",
            minLength: 1,
            dataSource: { serverFiltering: true,  transport: {read: {url: "index.php?show=product&voider=cities&response=json&&lang=1",dataType: "json", type: "POST"}}}
        });

        $('#itemgeo-city_lg2').kendoAutoComplete({
            dataTextField: "name",
            filter: "startswith",
            minLength: 1,
            dataSource: { serverFiltering: true,  transport: {read: {url: "index.php?show=product&voider=cities&response=json&lang=2",dataType: "json", type: "POST"}}}
        });    
        
        
        
        var allProps = {{props}};   
        
        
        $('.selector').each(function() {
            var curVle = $(this).data("rawvalue");
            var src = $(this).data("rawsource");
            var opts = {
                value: curVle
            }
            if (src!==undefined) {
                opts = {
                    dataValueField: 'itemvalue',
                    dataTextField: 'name',
                    dataSource: {
                        data: allProps[src]
                    },
                    value: curVle
                }
            }
            $(this).kendoDropDownList(opts);
        });
          
        $('.multiselectex').each(function() {
            var curVle = $(this).data("rawvalue");
            var vleArr = [];
            if (curVle!='') {
                vleArr = curVle.split(',');
            }
           
            $(this).kendoMultiSelectEx({
                dataSource: {
                    data: allTags
                },
                userInput: true,
                value: vleArr
            });
        });
        
        
        $('.multiselect').each(function() {
            var curVle = $(this).data("rawvalue");
            var srcName= $(this).data("rawsource");
            var curVal = [];
            if (allProps[srcName]!==undefined) {
                $.each(allProps[srcName],function() {
                    if ( (curVle & this.itemvalue) === this.itemvalue ) {
                        curVal.push(this.itemvalue);
                    }
                });
                $(this).kendoMultiSelect({
                    dataSource: {
                        data: allProps[srcName]
                    },
                    dataValueField: 'itemvalue',
                    dataTextField: 'name',
                    userInput: true,
                    value: curVal
                });
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
        
        $('#memberlist').kendoDropDownList({
            dataTextField: "name",
            dataValueField: "id",
            dataSource: { transport: {read: {url: "index.php?show=members&voider=members&response=json", dataType: "json", type: "POST"}}}
        });   
      
      
        $('.removefile').click(function(e) {
            e.preventDefault();
            if (!confirm('Удалить?'))
                return;
            var url = $(this).attr('href');
            var itemid = $(this).data("itemid");
            var fileid = $(this).data("fileid");
            var filetype = $(this).data("filetype");
            var sender = $(this).closest('.View-item').find('img');
            makeRequest({
                url: url,
                method: 'POST',
                dataType: 'json',
                data: { response: 'json', itemid: itemid, id: fileid, type: filetype },
                onSuccess: function(data,status) { 
                    sender.attr("src",'');
                }
            });
        });
        
        $('#itemform').myForm({
            beforeSubmit : function(arr, $form, options) {
                var valid = true;
                /*
                var lat = parseFloat($('#itemgeo-lat').val());
                var long = parseFloat($('#itemgeo-long').val());
                var lvl = parseFloat($('#itemgeo-zoom').val());
                var img = '' // $('#itemgeo-adres').val();
                var geoval = lat+';'+long+';'+lvl+';'+img;
                arr.push({name:'item[geo]', value: geoval });
                */
               
                $('select.multiselectex').each(function() {
                    var mlt = $(this).data("kendoMultiSelectEx");
                    var nme = $(this).data('rawname');
                    var vle = mlt.value();
                    var ravVle = '';
                    if (vle.length>0) {
                        ravVle = vle.join(',');
                    }
                    arr.push({name:nme, value: ravVle });
                });
                $('select.multiselect').each(function() {
                    var mlt = $(this).data("kendoMultiSelect");
                    var nme = $(this).data('rawname');
                    var vle = mlt.value();
                    var ravVle = 0;
                    if (vle.length>0) {
                        $.each(vle,function() {
                            ravVle = ravVle | this;
                        });
                    }
                    arr.push({name:nme, value: ravVle });
                });
                
                var ctsValues = $('#catlist').data("kendoMultiSelect").value();
                for(var idx=0;idx<ctsValues.length;idx++) {
                    arr.push({name:'item[cats][]', value: ctsValues[idx] });
                }
                
                
                $('#workdays input[type="checkbox"]').each(function() {
                    if (this.checked) {
                        var dv =$(this).closest('div');
                        var sp = $(this).closest('div').find('span');
                        var openTIme = sp.find('input.opentime').val().trim();
                        var closeTIme = sp.find('input.closetime').val().trim();
                        if (openTIme=='' || closeTIme=='') {
                            dv.invalid();
                            valid = false;
                        }
                    }
                });
                
                $('.temporary').remove();
                return valid;
            },
            onSuccess: function (data) {
                if (data.data && data.data.filename!==undefined) {
                    $('#item-slide').attr("src",'../' + data.data.filename);
                }
                if (data.data && data.data.thumbname!==undefined) {
                    $('#item-logo').attr("src",'../' + data.data.thumbname);
                }
                return true;
            }
        });
        
        $('#main-tabstrip').kendoTabStrip({
            animation:  {
                open: {
                    effects: "fadeIn"
                }
            },
            select: function(e) {
                if ($(e.item).index()>0) {
                    $('#galerytable').css('display','none');
                } else {
                    $('#galerytable').css('display','');                        
                }
            }
        });
        

        {{if act(=edit)}}
        
        var dataSource = new kendo.data.DataSource({
                    transport: {
                        read: {
                            url: "index.php?show=product&voider=files&parent_id={{item.id}}&response=json", 
                            contentType: "application/json; charset=utf-8",
                            type: "POST",
                            dataType: "json"
                        }
                    },
                    requestEnd: function(e) {
                      if (e.response) {
                        if (e.response.error) {
                            showError('Ошибка',e.response.error); 
                        }
                      } else {
                          showError('Ошибка','Неизвестная ошибка');
                      }
                    },
                    error:  function(e) {
                       var mess = 'Ошибка загрузки';
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
                saveUrl: "index.php?show=product&voider=attach&response=block&id={{item.id}}",
                removeUrl: "remove",
                autoUpload: true
            },
            upload: function(e) {
                var files = e.files;

                // Check the extension of each file and abort the upload if it is not .jpg
                $.each(files, function() {
                    var spans = $('ul.k-upload-files').find("span[title='"+this.name+"']").length;
                    if (spans>1) {
                        showError('Ошибка','Файл с этим именем уже существует в списке');
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
               var mess = 'Ошибка загрузки файла';
               if (e.XMLHttpRequest && e.XMLHttpRequest.responseText !=undefined) {
                   var p =$(e.XMLHttpRequest.responseText).find('p');
                   if (p.length>0) {
                       mess = $(e.XMLHttpRequest.responseText).find('p').html();
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
                cancel: 'Отмена',
                dropFilesHere: 'перетаскивать файлы сюда',
                remove: 'Удалить',
                retry: 'Повторить',
                select: 'Выбрать',
                statusFailed: 'Ошибка',
                statusUploaded: 'Загружено',
                statusUploading: 'Загружается',
                uploadSelectedFiles: 'Загрузить'                
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
                    data: 'response=json&show=product&' + url,
                    onSuccess: function(data,status) { 
                        var list = $("#listView").data("kendoListView");
                        list.dataSource.read();
                    }
                });
        });
            

        var menuwind = new Menuwind({
            data: {
                itemid: parseInt('{{item.id}}')
            }
        });
        
        function saveData(sender,method,data) {
            data.response = 'json';
            makeRequest({
                url: 'index.php?show=itemmenu&voider='+method,
                data: data,
                onSuccess: function(data,status) { 
                    sender.dataSource.read();
                    menuwind.close();
                    return true;
                }
            });
        }
        
        
        
        $('#add-booking').click(function(e) {
            e.preventDefault();
            menuwind.show({
                onok: function(result){
                    var grid = $("#booking-grid").data("kendoGrid");
                    saveData(grid,'add',result);
                    return true;
                },
                data: {
                    name: '',
                    name_lg1: '',
                    name_lg2: '',
                    content: '',
                    content_lg1: '',
                    content_lg2: '',
                    price: 0,
                    item_id: parseInt('{{item.id}}'),
                    parent_id: 0,
                    fullmode: false
                }
            });
        });
        $('#booking-grid-wrapper').on('click','.add-button',function(e) {
            e.preventDefault();
            var parent_id= $(this).data('parent_id');
            var grid = $(this).closest('.detail-wrapper').find('.k-grid').data("kendoGrid");
            
            menuwind.show({
                onok: function(result){
                    saveData(grid,'add',result);
                    return true;
                },
                data: {
                    name: '',
                    name_lg1: '',
                    name_lg2: '',
                    content: '',
                    content_lg1: '',
                    content_lg2: '',
                    price: 0,
                    item_id: parseInt('{{item.id}}'),
                    fullmode: true,
                    parent_id: parent_id
                }
            });
           
            return;
        });
        $('#booking-grid-wrapper').on('click','.a-action-edit',function(e) {
            e.preventDefault();
            var grid = $(this).closest('.k-grid').data("kendoGrid");
            var item = grid.dataItem($(this).closest('tr'));
            item.fullmode = false;
            console.log(item);
            if (item.parent_id>0) {
                item.fullmode = true;
            }
            menuwind.show({
                onok: function(result){
                    saveData(grid,'edit',result);
                    return true;
                },
                data: item
            });
            
            
            return;
            
        });
        
        
        var dsTpl = {
            schema: {
                data: "rows",
                errors: "error",
                total: "records",
                model: {
                    id: "id",
                    fields: {
                            id: {type: "string"},
                            parent_id: { type: "number"},
                            item_id: { type: "number"},
                            name: {type: "string"},
                            childcount: {type: "number"},
                            maxpos: {type: "number"},
                            minpos: {type: "number"},
                            pos: {type: "number"}
                         }
                     }
                 },
                 error: KendoErrorHandler,
                 pageSize: 20,
                 serverPaging: true,
                 serverSorting: true
        };

        var grid = $("#booking-grid").kendoGrid({
            dataSource: new kendo.data.DataSource($.extend(true,{
                transport: {
                    read: "index.php?show=itemmenu&response=json&item_id={{item.id}}",
                    dataType: "json"
                }
            },dsTpl)),
            filterable: false,
            groupable: false,
            sortable: true,
            reorderable: true,
            resizable: true,
            scrollable: false,
            pageable: true,
            selectable: "row",
            rowTemplate: "",
            detailInit: function(e) {
                e.detailRow.find(".detail-header > .add-button").data("parent_id",e.data.id);
                
                e.detailRow.find(".detail-grid").kendoGrid({
                    dataSource: new kendo.data.DataSource($.extend(true,{
                        transport: {
                            read: "index.php?show=itemmenu&response=json&item_id={{item.id}}&parent_id="+e.data.id,
                            dataType: "json"
                        },
                        sort: { field: "pos", dir: "asc" }
                    },dsTpl)),
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
                        {field: "visible", title: "&nbsp;", width: "50px",template: '<a href="id=#: id #&voider=visible&value=#: visible #" class="k-button w-button b-visible#: visible # a-action" title="toggle visibility">&nbsp;</a>'},
                        {field: "id", title: "N", width: "60px"},
                        {field: "name", title: "Имя", template: '<a href="#: id #" title="edit item" data-id="#: id #" class="a-action-edit">#: name # </a>'},
                        {field: "pos", title: "поз.", width: "97px",template: kendo.template($("#position-template").html()), attributes: {"class": "center"}},
                        { command: [
                             { name: "remove",text: "&nbsp;", click: removeItem, className: "w-button b-remove" }
                             ], title: "Удалить", width: "50px" }
                        
                    ]
                });
                
            },
            detailTemplate: kendo.template($("#dettemplate").html()),
            columns: [
                {field: "visible", title: "&nbsp;", width: "50px",template: '<a href="id=#: id #&voider=visible&value=#: visible #" class="k-button w-button b-visible#: visible # a-action" title="toggle visibility">&nbsp;</a>'},
                {field: "id", title: "N", width: "100px"},
                {field: "name", title: "Имя", template: '<a href="#: id #" title="edit item" data-id="#: id #" class="a-action-edit">#: name # </a>'},
                {field: "pos", title: "поз.", width: "97px",template: kendo.template($("#position-template").html()), attributes: {"class": "center"}},
                { command: [
                        { name: "removesub",text: "&nbsp;", click: removeItem, className: "w-button b-remove" }
                ], title: "Удалить", width: "50px" }
            ]
        });
        
        function removeItem(e) {
            e.preventDefault();
            if (!confirm('Удалить?')) return false;
            // json post id= result response.result == 'ok'
            var grid = $(e.currentTarget).closest('.k-grid').data("kendoGrid");
            var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
            postData(grid,'voider=delete&id='+dataItem.id);
        }
        
        $('#booking-grid-wrapper').on('click','.a-action',function(e) {
            e.preventDefault();
            var grid = $(this).closest('.k-grid').data("kendoGrid");
            var url = $(this).attr('href');
            postData(grid,url);
        });

        function postData(sender,params) {
            makeRequest({
                data: 'show=itemmenu&' + params,
                onSuccess: function(data,status) { 
                    sender.dataSource.read();
                }
            });
        }

        {{end if act}}
        
</script>
