<div id="newoffer">
    <form method="POST" action="{{curlangp}}account/itemschange.html" id="newoffer-form">
        <input type="hidden" id="oitemid" name="id" value="" />
        <input type="hidden" name="act" value="offer" />
        <input type="hidden" name="th" value="{{th}}" />
        <input type="hidden" name="uid" id="iuid" value="" />
        
        <div class="fieldset table">
            <div class="tr"> 
                <div class="td" id="offer-amount">
                    <label>{{dict.AMOUNT}} ({{dict.AMD}})*</label>
                    <input type="text" name="offer[amount]" id="el-offer-amount" class="m-decimal" style="width: 100%;"/>
                    <div id="calculated-box">
                        <div id="calculated-value"></div>   
                        <div>+ {{dict.INTEREST}} {{interest}}%</div>
                        
                        <span class="clear"></span>
                    </div>
                </div>
            </div>
            <div class="tr"> 
                <div class="td" id="offer-dateend">
                    <label>{{dict.OFFERDEADLINE}} *</label>
                    <input type="text" name="offer[dateend]" id="el-offer-dateend" class="m-integer" style="width: 300px;"/> {{dict.HOUR}}
                </div>
            </div>
            <div class="tr"> 
                <div class="td submitrow" id="offer-save">
                    <div id="offer-error" class="errobox">
                        
                    </div>
                    <input type="submit" class="flatbutton" value="{{dict.SAVE}}" />
                </div>
            </div>
        </div>
    </form>
</div>
<script >
    
    (function() {
        var interest = parseFloat('{{interest}}')
            ,interestmax = parseFloat('{{interestmax}}')
            ,interestmin = parseFloat('{{interestmin}}');
        var calEl = $('#calculated-value');
        function calcInterest(amount) {
            calEl.html(kendo.toString(Math.min(Math.max(amount * interest / 100,interestmin),interestmax), "n2"));
        }

        $('#el-offer-amount').on('keyup',function() {
            var amount = parseFloat(this.value);
            if (isNaN(amount))
                return;
            calcInterest(amount);
        });

        $('#el-offer-amount').kendoNumericTextBox({ 
            min: 0,
            change: function() {
                var amount = parseFloat(this.value());
                if (isNaN(amount))
                    return;
                calcInterest(amount);
            }
        });
        

        $('#el-offer-dateend').kendoNumericTextBox({
            format: 'n0',
            min: 1,
            decimals: 0
        });

    })();
    
</script>