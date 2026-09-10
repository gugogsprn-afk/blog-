<?php
    define('CURPATH', dirname(realpath(__FILE__)).'/');
    require_once "_app.php";
    $application = new HttpApplication();
    
   
	
    try {
        $application->start();
        if ($application->isStarted()) {
            $Factory = new PageFactory();
            $Page = $Factory->CreatePage(HttpContext::current()->request()->AppName);
            $Page->Initilize();
            $Page->preVoid();
            $Page->InvokeMethod(HttpContext::current()->request()->Voider);
            $Page->Display();
        }
    } catch (EInvalidInputError $e) {
        $application->onError(EPageError::INVALID_INPUT,$e->getMessage(),$e->InvalidFields);
    } catch (ESecurityError $e) {
        $application->onError(EPageError::SECURITY_ERROR,$e->getMessage(),$e->errType);
    } catch (ETemplateError $e) {
        $application->onError(EPageError::TEMPLATE_ERROR,$e->getMessage(),null);
    } catch (EPageError $e) {
        $application->onError($e->errType ,$e->getMessage(),$e->getData());
    } catch (Exception $e) {
        $application->onError(EPageError::INTERNAL_SERVER_ERROR,$e->getMessage(),null);
    }
   
    $application->end();
?>