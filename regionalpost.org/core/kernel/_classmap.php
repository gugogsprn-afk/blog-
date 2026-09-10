<?php
    
    $cm = array();
    
    // application
    $cm['HttpApplication'] = 'core/application/';
    $cm['HttpContext'] = 'core/application/';
    $cm['PageFactory'] = 'core/application/';
    $cm['CultureInfo'] = 'core/application/';
    $cm['Configuration'] = 'core/application/';
    $cm['Dictionary']  = 'core/application/';
    
    // database
    $cm['DatabaseProvider'] = 'core/database/';
    $cm['DatabaseProxyBase'] = 'core/database/';
    $cm['MysqliDatabaseProxy'] = 'core/database/';
    $cm['DataAdapter'] = 'core/database/';
    $cm['DataCollectionAdapter'] = 'core/database/';
    $cm['QueryBuilder'] = 'core/database/';
    
    
    // Objects
    $cm['EntityBase'] = 'core/objects/';
    $cm['FileManager'] = 'core/objects/';
    $cm['ImageManager'] = 'core/objects/';
    $cm['cookieManager'] = 'core/objects/';
    $cm['SiteMapManager'] = 'core/objects/';
    $cm['Metaphone'] = 'core/objects/';
	$cm['Encryptor'] = 'core/objects/';
    
    // templater
    $cm['ArrayObjectExt'] = 'core/templater/';
    $cm['TemplateParser'] = 'core/templater/';
    $cm['WebPageBase'] = 'core/templater/';
    $cm['Template'] = 'core/templater/';
    $cm['ValueComparer'] = 'core/templater/';
    
    $cm['EPageError'] = 'core/templater/';
    $cm['ETemplateError'] = 'core/templater/';
    $cm['EInvalidInputError'] = 'core/templater/';
    $cm['EDataBaseError'] = 'core/templater/';
    $cm['ESecurityError'] = 'core/templater/';
    
    
    // mailer
    $cm['PHPMailer'] =  'core/mailer/';
    $cm['POP3'] =  'core/mailer/';
    $cm['SMTP'] =  'core/mailer/';
    
   
    // security
    $cm['IdentityServiceBase'] =  'core/security/';
    $cm['UserValidatorBase'] = 'core/security/';
    
// <!-- ? -->
?>