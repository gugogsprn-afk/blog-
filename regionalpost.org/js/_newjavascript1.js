var flipflop=1;
var storedata;
var auctionUpdateTime = 1000;
var counterUpdateTime = 1000;
var auctiondata = '';
var getStatusUrl;
var lastsendtime;
var GlobalVar = 1;
var reloadWhenEnd=false;
var transtime=200;

var lastmovetime=new Date();

jQuery(function($){
    
    $('.cleardefault').focus(
        function(){
            $(this).val('');
        });
    
    $( "#timeout_dialog" ).dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            Ok: function() {
                $(this).dialog( "close" );
            }
        },
        close: function(event, ui) {
            setTimeout('updateAuctionInfo();', auctionUpdateTime);
        }
    });
    
   

    $('body').mousemove(function(){
        lastmovetime=new Date();
    });
    OnloadPage();
});

function OnloadPage() {
    auctionUpdateTime=reloadWhenEnd==true?refreshRate/2 : refreshRate;
    var $=jQuery;
    if (jQuery.browser.msie) {
        jQuery.ajaxSetup({
            cache: false,
            timeout:ajaxTimeOut
        });	//Configuring ajax
    }

    var firstauction=true;
    jQuery('.auction-item').each(function() {
        //var auctionId    = jQuery(this).attr('id');
        var auctionTitle = jQuery(this).attr('title');
        if(firstauction){
            auctiondata += auctionTitle;
            firstauction=false;
        }else{
            auctiondata += ',' + auctionTitle;
        }
        
    });

    getStatusUrl = 'update_info.php?flp=' + flipflop;

    if(jQuery('#bidder_count').length){
        getStatusUrl = 'update_info.php?flp=' + flipflop+'&biddercount='+jQuery('#bidder_count').html();
    }

    if(jQuery('#display_avatar').length){
        getStatusUrl+='&avatar='+jQuery('#display_avatar').html();
    }

    

    setTimeout('updateAuctionInfo();', auctionUpdateTime);


    jQuery('.ubid-button-link').click(function(){
        var aname=jQuery(this).attr('name');

        if(aname==''){
            return;
        }

        var id=jQuery(this).attr('rel');
        var price=jQuery('#lowestprice_'+id).val();
        if(isNaN(price)==true || Number(price)<0.01){
            //alert('Invalid Price');
            showInvalidPrice();
            return;
        }

        jQuery.ajax({
            url: siteurl+aname+"&bidprice="+price,
            dataType: 'json',
            success: function(data) {

                jQuery.each(data, function(i, item) {
                    result = item.result;

                    //alert(result[0]);

                    if (result=="unsuccess") {
                        showAlertBox(item.message);
                    }else if(result=='nobids'){
                        showConfirmBox(item.message);
                    }
                 
                    if (result=="success") {
                        jQuery('#lowestprice_'+id).val('');
                        updateUserBids();
                    }
                });
            },
            error: function(XMLHttpRequest,textStatus, errorThrown) { }
        });
        
    });

    jQuery('.bid-button-link').click(function() {
        //alert('a');
        var url=jQuery(this).attr('name');
        if(url=='')
            return;
        jQuery.ajax({            
            url: siteurl+url,
            dataType: 'json',
            success: function(data) {
				
                jQuery.each(data, function(i, item) {
                    result = item.result;
					
                    //alert(result[0]);
					
                    if (result=="unsuccess") {
                        
                        showAlertBox(item.message);
                    }else if(result=='nobids'){
                        showConfirmBox(item.message);
                    }
                    if (result=="success") {
                        updateUserBids();
                        if(typeof(myAfterBid)=='function'){
                            myAfterBid();
                        }
                        
                        
                    }
                });
            },
            error: function(XMLHttpRequest,textStatus, errorThrown) { }
        });

        return false;
    });

    jQuery('.butseat-button-link').click(function() {
        //alert('a');
        jQuery.ajax({
            url: siteurl+jQuery(this).attr('name'),
            dataType: 'json',
            success: function(data) {

                jQuery.each(data, function(i, item) {
                    result = item.result;

                    //alert(result[0]);

                    if (result=="unsuccess") {
                        showAlertBox(item.message);
                    }else if(result=='nobids'){
                        showConfirmBox(item.message);
                    }

                    if (result=="success") {
                        updateUserBids();
                    }
                });
            },
            error: function(XMLHttpRequest,textStatus, errorThrown) { }
        });

        return false;
    });
   

    jQuery(".bookbidbutlerbutton").click(function() {
        //alert(document.getElementById('bookbidbutlerbutton').name);
        if (jQuery('#bookbidbutlerbutton').attr('name')!="") {
			
            var bidbutstartprice = Number(jQuery('#bid_form').val());
            //var bidbutendprice = Number(jQuery('#bid_to').val());
            var totalbids = jQuery('#bid_bids').val();
		
            if (bidbutstartprice=="") {
                //alert("Please enter AutoBidder start price!");
                showAutoBidStart();
                return false;
            }
//            if (bidbutendprice=="") {
//                //alert("Please enter AutoBidder end price!");
//                showAutoBidEnd();
//                return false;
//            }
            if (totalbids=="") {
                //alert("Please enter AutoBidder bids!");
                showAutoBids();
                return false;
            }
            if (totalbids<=1) {
                //alert("You palce AutoBidder for more than one bid!");
                showMoreThenOneBids();
                return false;
            }

//            if(jQuery('#isreverseauction').val()=='0'){
//                if (bidbutstartprice >= bidbutendprice) {
//                    //alert("AutoBidder start price must greater than end price!");
//                    showEndGreaterStart();
//                    return false;
//                }
//            }else{
//                if (bidbutstartprice <= bidbutendprice) {
//                    //alert("AutoBidder start price must greater than end price for reverse auction!");
//                    showStartGreaterEnd();
//                    return false;
//                }
//            }

            jQuery.ajax({
                url: siteurl+"addbidbutler.php?aid="+jQuery(this).attr('name')+"&bidsp="+bidbutstartprice+"&totb="+totalbids,
                dataType: 'json',
                success: function(data) {
                    jQuery.each(data, function(i, item) {
                        if (item.result) {
                            result = item.result;
                            showAlertBox(item.message);
                        } else {
                            jQuery('#bid_form').val('');
                            //jQuery('#bid_to').val('');
                            jQuery('#bid_bids').val('');                            
                            jQuery('#butlermessage').show();
                            changeMessageTimer = setInterval("ChangeButlerImageSecond()",5000);
                            changedatabutler(data,"abut",Math.round(totalbids));
                            showAddAutoBidderSuccess();
                            updateUserBids();
                        }
                    });
                },
                error: function(XMLHttpRequest,textStatus, errorThrown) { }
            });

            return false;
        }
    });
}

function updateUniqueHistory(){
    if(jQuery('.productUniqueAuction').length<=0){
        return;
    }

    auctionhisid = jQuery('#history_auctionid').html();//document.getElementById('history_auctionid').innerHTML;

    oldbids = jQuery('#curproductbids').html();//document.getElementById('curproductprice').innerHTML;
    newbids = jQuery('#ubid_index_page_' + auctionhisid).html();//document.getElementById('price_index_page_' + auctionhisid).innerHTML;

    if (true) {
        //alert('a');
        getStatusUrl3 = siteurl+'updatehistory_unique.php?aucid_new='+auctionhisid;

        jQuery.ajax({
            url: getStatusUrl3,
            dataType: 'json',
            success: function(data) {
                var lastPos,lastName,currentName,currentPos,avatar;

                if(data==null || data.message=='failed') return;
                //data1 = eval('(' + data.responseText + ')');
                var fontweight;
                //alert(data);
                if(jQuery("#lasttendbidders").length){
                    jQuery("#lasttendbidders").html(data.tenbiders);
                }
                for (var i=0; i<data.hiss.length; i++) {
                    username = data.hiss[i].his.un;
                    adddate = data.hiss[i].his.ad;

                    if(i==0){
                        fontweight="bold";
                    }else{
                        fontweight="normal";
                    }

                    if(i==0){
                        currentName=data.hiss[i].his.un;
                        currentPos=data.hiss[i].his.latlng;
                        avatar=data.hiss[i].his.av;
                    }

                    if(i==1){
                        lastName=data.hiss[i].his.un;
                        lastPos=data.hiss[i].his.latlng;
                    }

                    jQuery("#bid_user_name_"+i).html(username);
                    

                    jQuery("#bid_date_"+i).html(adddate);
                    
                    
                    if(typeof(setFirstUniqueHistoryItemStyle)=="function"){
                        setFirstUniqueHistoryItemStyle();
                    }else{
                        jQuery("#bid_user_name_"+i).css("font-weight", fontweight);
                        jQuery("#bid_date_"+i).css("font-weight", fontweight);
                    }
                }

                if(typeof(updateMarker)=='function'){
                    updateMarker(currentPos,currentName,siteurl+avatar);
                }

                //alert(data.myhistories.length);

                if (data.mhiss.length>0) {
                    for (j=0; j<data.mhiss.length; j++) {
                        if(j==0){
                            fontweight="bold";
                        }else{
                            fontweight="normal";
                        }

                        username1 = data.mhiss[j].mhis.un;
                        adddate1= data.mhiss[j].mhis.ad;
                        bidprice1= data.mhiss[j].mhis.bp;

                        //document.getElementById('my_bid_price_' + j).innerHTML = "$" +  biddingprice1;
                        jQuery("#my_bid_username_"+j).html(username1);
                        

                        jQuery("#my_bid_price_"+j).html(bidprice1);
                        

                        //document.getElementById('my_bid_time_' + j).innerHTML = biddingusername1;
                        jQuery("#my_bid_date_"+j).html(adddate1);
                        
                        
                        
                        if(typeof(setMyFirstUniqueHistoryItemStyle)=="function"){
                            setMyFirstUniqueHistoryItemStyle();
                        }else{
                            jQuery("#my_bid_username_"+j).css("font-weight", fontweight);
                            jQuery("#my_bid_price_"+j).css("font-weight", fontweight);
                            jQuery("#my_bid_date_"+j).css("font-weight", fontweight);
                        }
                        
                    }
                }

                jQuery("#curproductbids").html(newbids);
            //changedatabutler(data,"abut",data.butlerslength.length);
            //document.getElementById('curproductprice').innerHTML = data.histories[0].history.bprice;
            },
            error: function(XMLHttpRequest,textStatus, errorThrown) { }
        });

        //update saving
        var onlineperbidvalue=jQuery("#onlineperbidvalue_text").val();
        var price=jQuery("#price_text").val();
        var fprice=jQuery("#fprice_text").val();
        var aucid=jQuery("#aucid_text").val();

        //alert(price+"_"+fprice+"_"+aucid+"_"+onlineperbidvalue);

        jQuery.ajax({
            type:'POST',
            url:siteurl+'update_savingprice.php',
            dataType:'json',
            cache:false,
            data:{
                onlineperbidvalue:onlineperbidvalue,
                aucid:aucid,
                price:price,
                fprice:fprice
            },
            success:function(data){
                //alert(data);
                if(data.msg=='ok'){
                    updateSavings(data.data);
                }
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
            //alert(textStatus);
            }
        });

    }
}


function updateHistory(){
    if(jQuery('.productImageThumb').length<=0){
        return;
    }

    auctionhisid = jQuery('#history_auctionid').html();//document.getElementById('history_auctionid').innerHTML;

    oldprice = jQuery('#curproductprice').html();//document.getElementById('curproductprice').innerHTML;
    newprice = jQuery('#price_index_page_' + auctionhisid).html();//document.getElementById('price_index_page_' + auctionhisid).innerHTML;

    //console.log(oldprice+' '+newprice);

    if (true) {
        getStatusUrl3 = siteurl+'updatehistory.php?aucid_new='+auctionhisid;

        jQuery.ajax({
            url: getStatusUrl3,
            dataType: 'json',
            success: function(data) {
                var lastPos,lastName,currentName,currentPos,avatar;

                if(data==null || data.message=='failed') return;
                //data1 = eval('(' + data.responseText + ')');
                var fontweight;
                //alert(data);
                if(jQuery("#lasttendbidders").length){
                    jQuery("#lasttendbidders").html(data.tenbiders);
                }
                
                if(data.rp && typeof(updateProgressBar)=="function"){
                    updateProgressBar(data.rpp);
                }
                
                if(data.nodata) return;

                for (var i=0; i<data.hiss.length; i++) {
                    biddingprice = data.hiss[i].his.bp;
                    biddingusername = data.hiss[i].his.un;
                    biddingtype = data.hiss[i].his.bt;
                    bidtime=data.hiss[i].his.t;

                    if(i==0){
                        currentName=data.hiss[i].his.un;
                        currentPos=data.hiss[i].his.latlng;
                        avatar=data.hiss[i].his.av;
                    }

                    if(i==1){
                        lastName=data.hiss[i].his.un;
                        lastPos=data.hiss[i].his.latlng;
                    }


                    if(i==0){
                        fontweight="bold";
                    }else{
                        fontweight="normal";
                    }

                    jQuery("#bid_price_"+i).html(biddingprice + CurrencySymbol);
                    
                    

                    jQuery("#bid_user_name_"+i).html(biddingusername);
                    

                    
                    if (biddingtype=='s') {
                        jQuery("#bid_type_"+i).html("Single Bid");
                    //document.getElementById('bid_type_' + i).innerHTML = "Single Bid";
                    } else if (biddingtype=='b') {
                        //document.getElementById('bid_type_' + i).innerHTML = "AutoBidder";
                        jQuery("#bid_type_"+i).html("AutoBidder");
                    } else if (bidding_type=='m') {
                        //document.getElementById('bid_type_' + i).innerHTML = "SMS Bid";
                        jQuery("#bid_type_"+i).html("SMS Bid");
                    }
                    
                    jQuery("#bid_timestamp_"+i).html(bidtime);
                    
                    if(typeof(setFirstHistoryItemStyle)=="function"){
                        setFirstHistoryItemStyle();
                    }else{
                        jQuery("#bid_price_"+i).css("font-weight", fontweight);
                        jQuery("#bid_user_name_"+i).css("font-weight", fontweight);
                        jQuery("#bid_type_"+i).css("font-weight", fontweight);
                        jQuery("#bid_timestamp_"+i).css("font-weight", fontweight);
                    }
                    
                }
                
                
                if(typeof(updateMarker)=='function'){
                    updateMarker(currentPos,currentName,siteurl + avatar);
                }

                //alert(data.myhistories.length);

                if (data.mhiss.length>0) {
                    for (j=0; j<data.mhiss.length; j++) {
                        if(j==0){
                            fontweight="bold";
                        }else{
                            fontweight="normal";
                        }

                        biddingprice1 = data.mhiss[j].mhis.bp;
                        biddingusername1 = data.mhiss[j].mhis.t;
                        biddingtype1 = data.mhiss[j].mhis.bt;

                        //document.getElementById('my_bid_price_' + j).innerHTML = "$" +  biddingprice1;
                        jQuery("#my_bid_price_"+j).html(biddingprice1 + CurrencySymbol);

                        

                        //document.getElementById('my_bid_time_' + j).innerHTML = biddingusername1;
                        jQuery("#my_bid_time_"+j).html(biddingusername1);
                        

                        if (biddingtype1=='s') {
                            jQuery("#my_bid_type_"+j).html("Single Bid");
                        //document.getElementById('my_bid_type_' + j).innerHTML = "Single Bid";
                        } else if (biddingtype1=='b') {
                            jQuery("#my_bid_type_"+j).html("AutoBidder");
                        //document.getElementById('my_bid_type_' + j).innerHTML = "AutoBidder";
                        } else if (biddingtype1=='m') {
                            //document.getElementById('my_bid_type_' + j).innerHTML = "SMS Bid";
                            jQuery("#my_bid_type_"+j).html("SMS Bid");
                        }
                        
                        
                        
                        if(typeof(setMyFirstHistoryItemStyle)=="function"){
                            setMyFirstHistoryItemStyle();
                        }else{
                            jQuery("#my_bid_price_"+j).css("font-weight", fontweight);
                            jQuery("#my_bid_time_"+j).css("font-weight", fontweight);
                            jQuery("#my_bid_type_"+j).css("font-weight", fontweight);
                        }
                    }
                }

                if(jQuery('#product_auctionprice').length>0)
                    jQuery("#product_auctionprice").html(data.hiss[0].his.bp + CurrencySymbol);

                jQuery("#curproductprice").html(data.hiss[0].his.bp);
                changedatabutler(data,"",data.butlerslength.length);
            },
            error: function(XMLHttpRequest,textStatus, errorThrown) { }
        });

        //update saving
        var onlineperbidvalue=jQuery("#onlineperbidvalue_text").val();
        var price=jQuery("#price_text").val();
        var fprice=jQuery("#fprice_text").val();
        var aucid=jQuery("#aucid_text").val();
        
        //alert(price+"_"+fprice+"_"+aucid+"_"+onlineperbidvalue);

        jQuery.ajax({
            type:'POST',
            url:siteurl+'update_savingprice.php',
            dataType:'json',
            cache:false,
            data:{
                onlineperbidvalue:onlineperbidvalue,
                aucid:aucid,
                price:price,
                fprice:fprice
            },
            success:function(data){
                if(data.msg=='ok'){
                    updateSavings(data.data);
                }
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
            //alert(textStatus);
            }
        });

    }
}


function updateSavings(data){
    
    jQuery("#placebidscount").html(data.totbid);
    jQuery(".placebidscount").html(data.totbid);
    
    jQuery("#placebidsamount").html(data.totbidprice + CurrencySymbol);
    jQuery(".placebidsamount").html(data.totbidprice + CurrencySymbol);
    
    jQuery("#placebidssavingdisp").html(data.saving + CurrencySymbol);
    jQuery("#placebidssaving").html(data.saving);
    jQuery("#buynowdiscount").html(data.buynowdiscount + CurrencySymbol);
    jQuery("#buynowprice").html(data.buynowprice + CurrencySymbol);
}

function updateAuctionInfo() {
    if (auctiondata.length>0) {
        jQuery.ajax({
            url: siteurl+getStatusUrl,
            dataType: 'json',
            type: 'get',
            cache:false,
            timeout: 3000,
            data: {
                auctionlist:auctiondata
            },
            global: false,
            success: function(response) {
                if(response.message!='ok') return;

                var data=response.data;
                storedata = response.data;

                //console.log((new Date()-lastsendtime)/1000-response.time+' '+response.time);
                transtime=(new Date()-lastsendtime)/1000;
                log(transtime-response.time+' '+response.time);
                
                //alert(data);

                jQuery.each(data, function(i, item) {
                    //alert(item.auc_id);
                    auction_id = item.id;
                    auction_price = item.np;
                    auction_bidder_name = item.hu==null?'---':item.hu;

                    if(reloadWhenEnd && item.lt==0){
                        //console.log('reload');
                        window.location.reload();
                    }

                    if(typeof(updateAuction)=='function'){
                        updateAuction(auction_id,auction_price + CurrencySymbol,item.lt);
                    }
              
                    var options = {
                        color:'#f79909'
                    };

                    if(item.sa==true){
                        if(item.san==true){
                            if(jQuery('#seat_count_'+auction_id+",.seat_count_"+auction_id).html()!=item.sc){
                                jQuery('#seat_count_'+auction_id+",.seat_count_"+auction_id).html(item.sc);
                                var bpos=(item.sc / item.ms-1)*120;
                                jQuery('#seat_bar_'+auction_id+",.seat_count_"+auction_id).css('background-position',bpos+'px 0px');
                                if (GlobalVar == 1) {
                                    if(typeof(flashEffect)=="function"){
                                        flashEffect('seat_bar_',auction_id);
                                    }else{
                                        jQuery('#seat_count_' + auction_id+",.seat_count_"+auction_id).effect('highlight',options,500);
                                    }
                                    
                                }
                            }
                            return;
                        }else{
                            if(jQuery('.seat_panel_'+ auction_id).length>0 && jQuery('.seat_panel_'+ auction_id).css('display')=='block'){
                                jQuery('.seat_panel_'+ auction_id).css('display', 'none');
                                jQuery('.normal_panel_'+ auction_id).css('display', 'block');
                                // the button
                                jQuery('.seat_button_'+ auction_id).css('display', 'none');
                                jQuery('.normal_button_'+ auction_id).css('display', 'block');
                            }
                        }
                    }

                    if(item.ua==false){

                        if (jQuery("#price_index_page_"+auction_id).length>0 && jQuery("#price_index_page_"+auction_id).html() != auction_price + CurrencySymbol) {
                            
                            
                            if (GlobalVar == 1) {
                                if (jQuery('#history_auctionid').length>0) {
                                    updateHistory();
                                    if (auction_id==jQuery('#history_auctionid').html()){// document.getElementById('history_auctionid').innerHTML) {
                                        
                                        if(typeof(flashDetailEffect)=="function"){
                                            flashDetailEffect('price_index_page_',auction_id);
                                        }else{
                                            jQuery('#price_index_page_' + auction_id+',.price_index_page_' + auction_id).effect('highlight',options,500);
                                        }
                                        jQuery("#bid_form").val(auction_price);
                                    //jQuery('#currencysymbol_' + auction_id).effect('highlight',options,500);

                                    } else {
                                        if(typeof(flashEffect)=="function"){
                                            flashEffect('price_index_page_',auction_id);
                                        }else{
                                            jQuery('#price_index_page_' + auction_id+',.price_index_page_' + auction_id).effect('highlight',options,500);
                                        }
                                        
                                    //jQuery('#currencysymbol_' + auction_id).effect('highlight',options,500);

                                    }
                                    
                                } else {
                                    if(typeof(flashEffect)=="function"){
                                        flashEffect('price_index_page_',auction_id);
                                    }else{
                                        jQuery('#price_index_page_' + auction_id+',.price_index_page_' + auction_id).effect('highlight',options,500);
                                    }
                                //jQuery('#currencysymbol_' + auction_id).effect('highlight',options,500);

                                }

                            }
                            


                            if(jQuery('#product_avatarimage_'+auction_id).length && item.av!=""){
                                jQuery('#product_avatarimage_'+auction_id).attr('src',item.av);
                            }


                            jQuery('#price_index_page_' + auction_id+',.price_index_page_' + auction_id).html(auction_price + CurrencySymbol);
                            //jQuery('#currencysymbol_' + auction_id).html(CurrencySymbol);
                            
                            //set the tax amount

                            if(jQuery('#product_taxamount_'+auction_id).length>0){
                                var tax1=jQuery('#product_tax1_'+auction_id).val();
                                var tax2=jQuery('#product_tax2_'+auction_id).val();
                                var taxamount=0;
                                if(tax1!=0){
                                    taxamount+=auction_price*tax1/100;
                                }
                                if(tax2!=0){
                                    taxamount+=auction_price*tax2/100;
                                }
                                jQuery('#product_taxamount_'+auction_id).html(Math.round(taxamount*100)/100 + CurrencySymbol);
                            }


                            if(jQuery("#product_bidder_"+auction_id).length>0){
                                jQuery("#product_bidder_"+auction_id+",.product_bidder_"+auction_id).html(auction_bidder_name);
                            }

                            //alert(jQuery('#topbider_index_page_' + auction_id).length);

                            if(jQuery('#topbider_index_page_' + auction_id).length>0){
                                //PennyAuctionWizards add for top bidder
                                topbidder=item.tb;
                                acls=jQuery('#topbider_index_page_' + auction_id).attr('class');
                                totalcount=0;
                                if(acls.indexOf('i4')>0){
                                    totalcount=4;
                                }else if(acls.indexOf('i3')>0){
                                    totalcount=3;
                                }
                                if(totalcount>0 && topbidder!=null){
                                    bidderhtml="";
                                    jQuery.each(topbidder,function(i,bitem){
                                        bidderhtml+='<li><a>'+bitem+'</a></li>';
                                        totalcount--;
                                        if(totalcount==0) return false;
                                    });
                                    for(i=totalcount-1;i>=0;i--){
                                        bidderhtml+='<li><a>---</a></li>';
                                    }
                                    jQuery('#topbider_index_page_' + auction_id).html(bidderhtml);
                                }
                            }
                        //PennyAuctionWizards add for top bidder
                            

                        }
                    }else{
                        if (jQuery("#ubid_index_page_"+auction_id).length>0 && jQuery("#ubid_index_page_"+auction_id).html() != item.lbc) {                            

                            if (GlobalVar == 1) {
                                if (jQuery('#history_auctionid').length>0) {
                                    updateUniqueHistory();    
                                    if (auction_id==jQuery('#history_auctionid').html()){// document.getElementById('history_auctionid').innerHTML) {
                                        
                                        //jQuery('#currencysymbol_' + auction_id).effect('highlight',options,500);
                                        if(typeof(flashDetailEffect)=="function"){
                                            flashDetailEffect('ubid_index_page_',auction_id);
                                        }else{
                                            jQuery('#ubid_index_page_' + auction_id+',.ubid_index_page_' + auction_id).effect('highlight',options,500);
                                        }

                                    } else {
                                        
                                        //jQuery('#currencysymbol_' + auction_id).effect('highlight',options,500);
                                        if(typeof(flashEffect)=="function"){
                                            flashEffect('ubid_index_page_',auction_id);
                                        }else{
                                            jQuery('#ubid_index_page_' + auction_id+',.ubid_index_page_' + auction_id).effect('highlight',options,500);
                                        }

                                    }
                                }else {
                                    if(typeof(flashEffect)=="function"){
                                        flashEffect('ubid_index_page_',auction_id);
                                    }else{
                                        jQuery('#ubid_index_page_' + auction_id+',.ubid_index_page_' + auction_id).effect('highlight',options,500);
                                    }
                                //jQuery('#currencysymbol_' + auction_id).effect('highlight',options,500);
                                }

                            }


                            if(jQuery('#product_avatarimage_'+auction_id).length && item.av!=""){
                                jQuery('#product_avatarimage_'+auction_id).attr('src', item.av);
                            }
                            
                            if(jQuery('#product_taxamount_'+auction_id).length>0){
                                tax1=jQuery('#product_tax1_'+auction_id).val();
                                tax2=jQuery('#product_tax2_'+auction_id).val();
                                taxamount=0;
                                if(tax1!=0){
                                    taxamount+=auction_price*tax1/100;
                                }
                                if(tax2!=0){
                                    taxamount+=auction_price*tax2/100;
                                }
                                jQuery('#product_taxamount_'+auction_id).html(Math.round(taxamount*100)/100 + CurrencySymbol);
                            }

                            jQuery('#ubid_index_page_'+auction_id+',.ubid_index_page_' + auction_id).html(item.lbc);

                            if(jQuery("#product_bidder_"+auction_id).length>0){
                                jQuery("#product_bidder_"+auction_id+",.product_bidder_"+auction_id).html(auction_bidder_name);
                            }
                            
                        }
                    }
                });
                
                if(typeof(updateConnectionInfo)=='function'){
                    updateConnectionInfo(transtime);
                }
                GlobalVar = 1;
            },
            error: function(XMLHttpRequest,textStatus, errorThrown) {
            //runUpdateTimer();
            },
            complete:function(){
                runUpdateTimer();
            },
            beforeSend:function(XMLHttpRequest){
                lastsendtime=new Date();
            }
        });
    }
    if (flipflop==1) {
        flipflop = 1;
    //ChangeCountdownData(storedata);
    } else if (flipflop==2) {
        flipflop = 1;
    //alert(storedata);
    //ChangeCountdownData(storedata);
    }
    ChangeCountdownData(storedata);
}

function runUpdateTimer(){
    if((new Date()-lastmovetime)/1000>timeoutvalue && timeoutvalue!=0){
        jQuery( "#timeout_dialog" ).dialog('open');
        return;
    }

    var lefttime=(auctionUpdateTime-(new Date()-lastsendtime));
    if(lefttime<=0)
        lefttime=0;
    //console.log(now+'  '+lastsendtime+'  '+runtime+'  '+lefttime);
    setTimeout('updateAuctionInfo();', lefttime);
}

function DeleteBidButler(id, div_id) {
    jQuery.ajax({
        url: url = siteurl+"deletebutler.php?delid=" + id,
        dataType: 'json',
        success: function(data) {
            jQuery.each(data, function(i, item) {
                result = item.result;
                if (result=="unsuccess") {
                    //alert("Your BidBuddy is running you can't delete it!");
                    showAlertBox(item.message);
                } else {
                    updateUserBids();
                    changedatabutler(data,"dbut","");
                }
            });
        },
        error: function(XMLHttpRequest,textStatus, errorThrown) { }
    });
    return false;
}

function ChangeCountdownData(resdata) {
	
    if (resdata && resdata!="") {
        data = resdata;
        

        jQuery.each(data, function(i, item) {
            auction_id = item.id;
            auction_time = item.lt;
            pausestatus = item.p;
            
            var ufor='n';

            if (auction_time) {
                //alert(auction_time);
                if(auction_time==2 && enableTimerDelayer==true){
                    //jQuery('#counter_index_page_' + auction_id).css('color', '#E80000');
                    if(typeof(buildtimerlist)=='function'){
                        var oncearr=['ON','CE','!'];
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).html(buildtimerlist(oncearr));
                    }else{
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).html('GOING ONCE');
                    }
                    
                    if(jQuery('#counter_index_page_' + auction_id).hasClass('timeending')==false){
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).addClass('timeending');
                    }
                    if(jQuery('#counter_index_page_' + auction_id).hasClass('timelast')==false){
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).addClass('timelast');
                    }
                    ufor='o';

                }else if(auction_time==1 && enableTimerDelayer==true){
                    //jQuery('#counter_index_page_' + auction_id).css('color', '#E80000');
                    if(typeof(buildtimerlist)=='function'){
                        var twicearr=['TW','IC','E!'];
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).html(buildtimerlist(twicearr));
                    }else{
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).html('GOING TWICE');
                    }
                    if(jQuery('#counter_index_page_' + auction_id).hasClass('timeending')==false){
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).addClass('timeending');
                    }
                    if(jQuery('#counter_index_page_' + auction_id).hasClass('timelast')==false){
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).addClass('timelast');
                    }
                    ufor='t';
                }else if (auction_time==0) {
                    //jQuery('#counter_index_page_' + auction_id).css('color', '#000000');
                    if(typeof(buildtimerlist)=='function'){
                        var soldarr=['SO','LD','!'];
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).html(buildtimerlist(soldarr));
                    }else{
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).html('SOLD');
                    }
                    jQuery('#image_main_' + auction_id).attr('onclick', '');
                    jQuery('#image_main_' + auction_id).attr('name','');
                    jQuery('#image_main_' + auction_id).text('SOLD');
                    if(jQuery('#counter_index_page_' + auction_id).hasClass('timeending')){
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).removeClass('timeending');
                    }
                    if(jQuery('#counter_index_page_' + auction_id).hasClass('timelast')==true){
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).removeClass('timelast');
                    }

                    if(typeof(updateEndAuction)=='function'){
                        updateEndAuction(auction_id);
                    }
                    ufor='s';
                //document.getElementById('image_main_' + auction_id).src = "img/buttons/btn-sold_92.png";
                } else if (pausestatus==1) {
                    if(typeof(buildtimerlist)=='function'){
                        var pausearr=['PA','US','E!'];
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).html(buildtimerlist(pausearr));
                    }else{
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).html('Pause');
                    }
                    //document.getElementById('image_main_' + auction_id).src = "img/buttons/btn_placebid_92.png";
                    jQuery('#image_main_' + auction_id).attr('onclick', '');
                    jQuery('#image_main_' + auction_id).attr('name','');
                    jQuery('#image_main_' + auction_id).text('PAUSE');
                    if(jQuery('#counter_index_page_' + auction_id).hasClass('timeending')){
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).removeClass('timeending');
                    }
                    if(jQuery('#counter_index_page_' + auction_id).hasClass('timelast')==true){
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).removeClass('timelast');
                    }
                    ufor='p';
                } else {
                    if(enableTimerDelayer==true){
                        auction_time-=2;
                    }
                    
                    if(typeof(detailId)=='undefined'){
                        detailId=-1;
                    }
                    
                    if (auction_time<10) {
                        //jQuery('#counter_index_page_' + auction_id).css('color', '#E80000');
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).html(calc_counter_from_time(auction_time,auction_id==detailId));
                        if(jQuery('#counter_index_page_' + auction_id).hasClass('timelast')==false){
                            jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).addClass('timelast');
                        }
                    } else {
                        //jQuery('#counter_index_page_' + auction_id).css('color', '#000000');
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).html(calc_counter_from_time(auction_time,auction_id==detailId));
                        if(jQuery('#counter_index_page_' + auction_id).hasClass('timelast')==true){
                            jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).removeClass('timelast');
                        }
                    }
                    jQuery('#image_main_' + auction_id).show();
                    if(jQuery('#counter_index_page_' + auction_id).hasClass('timeending')){
                        jQuery('#counter_index_page_' + auction_id+',.counter_index_page_' + auction_id).removeClass('timeending');
                    }
                    
                }
                
                if(typeof(updateTimerStyle)=='function'){
                    updateTimerStyle(auction_id,ufor);
                }
                

                if(jQuery('#blink_img_'+auction_id).length){
                    if(auction_time>0 && auction_time<=15){
                        jQuery('#blink_img_'+auction_id).css('display', 'block');
                    }else{
                        jQuery('#blink_img_'+auction_id).css('display', 'none');
                    }
                }
               
            }
        });
    }
}

function showhide_auctype(value,type){    
    if(type=='over'){
        jQuery('#auction_type'+value).show();

    }else if(type=='out'){
        jQuery('#auction_type'+value).hide();
    }
}


//callback function to bring a hidden box back
function callback(){
    setTimeout(function(){
        jQuery("#effect:hidden").removeAttr('style').hide().fadeIn();
    }, 1000);
}


function updateUserBids(){
    jQuery.ajax({
        url: siteurl+"update_userbids.php",
        dataType: 'json',
        type:"GET",
        success: function(data) {
            jQuery('#free_bids_count').html(data.free_bids);
            jQuery('.free_bids_count').html(data.free_bids);
            jQuery('#bids_count').html(data.final_bids);
            jQuery('.bids_count').html(data.final_bids);
            jQuery('.totalbids').html(Number(data.free_bids)+Number(data.final_bids));
        }
    });
}


function number_format( number, decimals, dec_point, thousands_sep ) {

    var n = number, c = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals;

    var d = dec_point == undefined ? "." : dec_point;

    var t = thousands_sep == undefined ? "," : thousands_sep, s = n < 0 ? "-" : "";

    var i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;



    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");

}

function log(msg){
//console.log(msg);
}/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


