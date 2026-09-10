(function($) {
    var kendo = window.kendo,
        ui = kendo.ui,
        Widget = ui.Widget,
        NS = ".sttranslit",
        CHANGE = "change",
        KEYUP = "keyup",
        PASTE = "paste";

    var UrlBuilder = Widget.extend({

        init: function (element, options) {
            var that = this;
            Widget.fn.init.call(that, element, options);
            var vle = element.value;
            element = that.element;
            
            that.options.params = that.options.params || {};
            
            if (!that.options.params.itemid) {
                that.options.params.itemid = $(element).closest('form').find('input[name="id"]').val();
            }
            
            that.source = $(that.options.from);
            that.parent = $(that.options.parentid);
            that.mode = that.options.mode;
            that.params = that.options.params;
            
            that.source.on(CHANGE + NS, $.proxy(that._beginFetchUrl,that))
                        .on(PASTE + NS, $.proxy(that._beginFetchUrl,that));
            
            that._create();
            that.value(vle);
            that.trigger(CHANGE);
            that.element.trigger(CHANGE);
        },
        options: {
            name: "urlbuilder",
            value: '',
            url: 'index.php?show=helpers&voider=urlbuilder',
            delay: 300,
            from: '',
            itemtype: 'unknown',
            mode: 'add',
            parentid: '',
            params: {}
        },
        events: [CHANGE],
        _templates: {
            textbox: '<span class="k-url-wrap"></span>',
            url: '<span class="k-url k-descr">&nbsp;</span>'
        },
        _create: function () {
            var that = this,
                element = this.element;
            
            var template = kendo.template(that._templates.textbox);
            that.textbox = $(template(that.options)); 
            
            template = kendo.template(that._templates.url);
            that.urlblock = $(template(that.options));
            
            that.element.wrap(that.textbox);
            that.element.closest('.k-url-wrap').append(that.urlblock);
            
        },
        value: function(value) { // calls mvvm on value bind or change
            
            var that = this;

            if (value === undefined) {
                return that._value;
            }
            
            that._value = value;
            that._old = that._value;
            that._updateUI();
        },
        _change: function (value) { // triggers change event and notifies the mvvm for value change
            var that = this;
            if (that._old != value) {
                that._value = value;
                that._old = value;
                
                that.trigger(CHANGE);
            }
        },
        _updateUI:function() { // updates the UI
            var that = this;
            that.urlblock.html('&nbsp;' + that._value);
            that.element.val(that._value);
        },
        
        _beginFetchUrl: function() {
            var that = this;
            clearTimeout(that.fetchTimer);
            that.fetchTimer = setTimeout( $.proxy(that._fetchurl,that) , that.options.delay);
        },
        _fetchurl: function() {
            
            var that = this,
                element = this.element,    
                vle = $.extend({ alias: that.source.val(), itemtype: that.options.itemtype, parent_id: that.parent.val(), mode: that.mode  },that.params);
            that.urlblock.css('color','');
            $.post(that.options.url,vle,
                function(data) {
                    that.value(data.url);
                    that.trigger(CHANGE);
                    that.element.trigger(CHANGE);
                }, 'json').error(
                function(xhr, status, error)  { 
                    that.value('');
                    var data = AjaxErrorHandler(xhr,status,error);
                    that.urlblock.css('color','red').html('&nbsp;' + data.error);
                });
                
        },
        
        
        destroy: function() {
            if (this.source) {
                this.source.off(NS);
            }
            Widget.fn.destroy.call(this);
        }
        
    });
    
    
    ui.plugin(UrlBuilder);
})(jQuery);    