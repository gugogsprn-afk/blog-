(function ($) {
    var kendo = window.kendo,
        ui = kendo.ui,
        Widget = ui.Widget,
        NS = ".sttranslit",
        CLICK = "click",
        BLUR = "blur",
        CHANGE = "change",
        KEYPRESS = "keypress",
        mask = /^([a-z0-9-]+)?$/;
        
    var Translit = Widget.extend({

        init: function (element, options) {
            var that = this;
                
            Widget.fn.init.call(that, element, options);
    
            element = that.element;
           
            element.on(BLUR + NS, $.proxy(that._blur, that))
                   .on(KEYPRESS + NS, $.proxy(that._keyPress,element[0]));
            
            that._create();

            that.source = $(that.options.from);
            
            
            that.icon.on(CLICK + NS, $.proxy(that._buttonclick, that));
            
            if (element.is("[disabled]")) {
                that.enable(false);
            }
            
            

            that.value(options.value || that.element.val());

            kendo.notify(that);
            
        },
        options: {
            name: "translit",
            value: null,
            url: 'index.php?show=helpers&voider=translit',
            from: '#item-name',
            iconclass: 'k-i-magic',
            iconwrapclass: 'k-magic',
            width: '200px;'
        },
        events: [CHANGE],
        _templates: {
            textbox: '<span style="width: #: width #px;" class="k-widget k-datepicker k-header tb"><span class="k-picker-wrap k-state-default"></span></span>',
            icon: '<span unselectable="on" class="k-select #: iconwrapclass #" role="button"><span unselectable="on" class="k-icon #: iconclass #">select</span></span>'        
        },
        _create: function () {
            var that = this;
            
            var template = kendo.template(that._templates.icon);
            that.icon = $(template(that.options));

            template = kendo.template(that._templates.textbox);
            that.textbox = $(template(that.options));

            that.element.addClass("k-input");
            that.element.wrap(that.textbox);
            that.element.after(that.icon);
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
            that.element.val(that._value);
        },
        
        
        
        
        _blur: function() { // track changes from ui
            var that = this;            
            that._change(that.element.val());
        },
        _keyPress: function(event) { // restrict input chars
            if (!event.charCode) return true;
            var part1 = this.value.substring(0,this.selectionStart);
            var part2 = this.value.substring(this.selectionEnd,this.value.length);
            if (!mask.test(part1 + String.fromCharCode(event.charCode) + part2))
                return false;
        },
        _buttonclick: function () { // button clicked
            var that = this,
                sourceVal = that.source.val();
            makeRequest({
                url: that.options.url,
                wrapper: that.element.closest('.k-widget'),
                loading: 'js/plugins/kendo/styles/flat/loading.gif',
                data: { word: sourceVal },
                onSuccess: function(data) {
                    that.value(data.text);
                    that.trigger(CHANGE);
                    that.element.trigger(CHANGE);
                    return false;
                }
            });
            return that;
        },
        
        
        destroy: function() {
            if (this.element) {
                this.element.off(NS);
            }
            if (this.icon) {
                this.icon.off(NS);
            }
            this.icon = null;
            this.textbox = null;
            Widget.fn.destroy.call(this);
        }
        
    });
    ui.plugin(Translit);
})(jQuery);