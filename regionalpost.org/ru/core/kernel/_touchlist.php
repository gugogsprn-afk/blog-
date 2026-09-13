<?php
    // application
    $cm['RequestProcessor'] = 'core/application/';
    
    // security
    $cm['UserValidator'] = 'core/security/';
    $cm['UserIdentityService'] = 'core/security/';
    $cm['Identity'] =  'core/security/';
    
    // templater
    
    $cm['MasterWebPage'] = 'core/webpages/';
    $cm['ErrorWebPage'] = 'core/webpages/';
    $cm['helpersWebPage'] = 'core/webpages/';
    $cm['searchWebPage'] = 'core/webpages/';
    $cm['catalogWebPage'] = 'core/webpages/';
    $cm['productWebPage'] = 'core/webpages/';
    $cm['orderWebPage'] = 'core/webpages/';
    $cm['pagesWebPage'] = 'core/webpages/';
    $cm['contactsWebPage'] = 'core/webpages/';
    $cm['favoritesWebPage'] = 'core/webpages/';
    $cm['paymentWebPage'] = 'core/webpages/';
    $cm['membersWebPage'] = 'core/webpages/';
    $cm['socialWebPage'] = 'core/webpages/';
    $cm['apiWebPage'] = 'core/webpages/';
    $cm['apimembersWebPage'] = 'core/webpages/';
    $cm['accountWebPage'] = 'core/webpages/';
    $cm['apiAccountWebPage'] = 'core/webpages/';
    
    // entity
    $cm['SiteEntity']= 'core/entity/';
    $cm['Uri'] = 'core/entity/';
    $cm['Slide'] =  'core/entity/'; 
    $cm['Category']  =  'core/entity/'; 
    $cm['Page']  =  'core/entity/'; 
    $cm['Product'] = 'core/entity/';
    $cm['AdProduct'] = 'core/entity/';
    $cm['Order'] = 'core/entity/';
    $cm['Banner'] = 'core/entity/';
    $cm['Member'] = 'core/entity/';
    $cm['Comment'] = 'core/entity/';
    $cm['Review'] = 'core/entity/';
    $cm['ItemFile'] = 'core/entity/';
    $cm['ProductProps'] = 'core/entity/';
    $cm['BookItem'] = 'core/entity/';
    $cm['Parcel'] = 'core/entity/';
    $cm['Review'] = 'core/entity/';
    
    $cm['ItemListHelper']= 'core/entity/';
    // object
    $cm['ConversePaymentClient'] = 'core/objects/';
    $cm['ArcaPaymentClient']  = 'core/objects/';
    $cm['RemoteRequest'] = 'core/objects/';
    $cm['Logger'] = 'core/objects/';
    $cm['TerminalServer'] = 'core/objects/';
    
    $cm['plugins'] = array(
        'Facebook'=>'core/plugins/facebook-sdk-v5/',
        'GuzzleHttp'=>'core/plugins/guzzlehttp/',
        'Monolog'=>'core/plugins/monolog/',
        'phpseclib'=>'core/plugins/phpseclib/',
        'Psr'=>'core/plugins/psr/',
        'React'=>'core/plugins/react/',
        'Google'=>'core/plugins/google/',
        'Google_'=>'core/plugins/google/api-client-v2.0-beta/'
    );
    
    
    // other non namespaced 
    // firebase
    $cm['BeforeValidException']         = 'core/plugins/firebase/php-jwt/Firebase/PHP-JWT/Exceptions/';
    $cm['SignatureInvalidException']    = 'core/plugins/firebase/php-jwt/Firebase/PHP-JWT/Exceptions/';
    $cm['ExpiredException']             = 'core/plugins/firebase/php-jwt/Firebase/PHP-JWT/Exceptions/';
    $cm['JWT']                          = 'core/plugins/firebase/php-jwt/Firebase/PHP-JWT/Authentication/';
            
    
    
    
    
    
    
    
    
// <!-- ? -->
?>