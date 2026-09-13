<?php
    // BE V1.5 EN / RTL support
    define('ADMINDIR', 'miban/');
    define('CURPATH', dirname(realpath(__FILE__)).'/');
    require_once '../_app.php';
    
    $application = new HttpApplication();
    
    try {
       $application->start();
       // Carry login across EN / RU / FR admin panels.
       AdminSso::acceptIfPresent();
       if (!(HttpContext::current()->Identity() && HttpContext::current()->Identified())) {
            $Page = new LoginWebPage();
            $Page->Initilize();
            $Page->Index();
            if (!(HttpContext::current()->Identity() && HttpContext::current()->Identified())) {
                $Page->Display();
                exit();
            }
        }
    } catch (Exception $ex) {
        throw $ex; // TODO: login exception?
        exit();
    }
    
    
    try {
        $Factory = new PageFactory();
        $Page = $Factory->CreatePage(HttpContext::current()->request()->AppName);
        if ($Page->isPublicMethod(HttpContext::current()->request()->Voider)!==true) {
            throw new EPageError(EPageError::METHOD_NOT_FOUND);
        }
        if ($Page->checkPermissions()!==true) {
            throw new ESecurityError(ESecurityError::ACCESS_DENIED);
        }
        $Page->Initilize();
        $Page->InvokeMethod(HttpContext::current()->request()->Voider);
        $Page->Display();
    } catch (EInvalidInputError $e) {
        $application->onError(EPageError::INVALID_INPUT,$e->getMessage(),$e->InvalidFields);
    } catch (ESecurityError $e) {
        $application->onError(EPageError::SECURITY_ERROR,$e->getMessage(),$e->errType);
    } catch (ETemplateError $e) {
        $application->onError(EPageError::TEMPLATE_ERROR,$e->getMessage(),null);
    } catch (EPageError $e) {
        $application->onError($e->errType ,$e->getMessage(),null);
    } catch (Exception $e) {
        $application->onError(EPageError::INTERNAL_SERVER_ERROR,$e->getMessage(),null);
    }

    $application->end();
?>