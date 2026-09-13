<?php

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    /**
     * Description of Logger
     *
     * @author Vahe
     */
    class Logger {

        const LOGTYPES_ERROR = 'err';
        const LOGTYPES_WARN = 'warn';
        const LOGTYPES_INFO = 'info';
        
        
        public static function log($entry,$logType,$useTime = false) {
            file_put_contents('tmp/log_'.$logType.'_'.($useTime?date("d.m.Y_His"):date("d.m.Y")).".log",date("d.m.Y_His").' - '.$entry.PHP_EOL,FILE_APPEND);
        }
        
        
        /**
         * 
         * @param Exception $ex
         * @param type $logType
         * @param type $useTime
         */
        public static function logError($ex,$logType,$useTime = false) {
            $stack = $ex->getTrace();
            if (array_isset($stack)) {
                $stack = print_r($stack,true);
            }
            file_put_contents('tmp/log_'.$logType.'_'.($useTime?date("d.m.Y_His"):date("d.m.Y")).".log",date("d.m.Y_His").' - '.$ex->getMessage().PHP_EOL.$stack ,FILE_APPEND);
        }
        
        
        public static function sendMail($entry,$subject) {
            $mailConf = Configuration::BulkLoad(array('site_company','mail_server','mail_support','mail_method'));
            $ok = false;
            $error = '';
            
            $messageContent =htmlspecialchars(mb_str_replace('\\','',$entry));
            
            try {
                $mail = new PHPMailer;
                switch ($mailConf['mail_method']) {
                    case 'sendmail':
                        $mail->IsSendmail();
                        break;
                    case 'mail':
                        $mail->IsMail();
                        break;
                    default:
                        $mail->IsSMTP();
                        break;
                }
                if (!empty($mailConf['mail_server'])) {
                    $mail->Host = $mailConf['mail_server']; 
                }

                $mail->IsHTML(false);   
                $mail->AddReplyTo($mailConf['mail_support'], $mailConf['site_company'].' automatic logger ');
                $mail->SetFrom($mailConf['mail_support'], $mailConf['site_company'].' automatic logger ');
                $mail->AddAddress($mailConf['mail_support']); 
                $mail->Subject = $subject;
                $mail->Body = $messageContent;
                $mail->CharSet="UTF-8";
                $ok = $mail->Send();
                $error =$mail->ErrorInfo;
            } catch (phpmailerException $ex) {
                file_put_contents('tmp/logger_mail_'.date("d.m.Y").".log",date("d.m.Y_His").' - LOG MAIL SEND ERROR '.$ex->getMessage().PHP_EOL.$ex->getTrace() ,FILE_APPEND);
               // throw new EPageError(EPageError::CUSTOM_ERROR,$ex->errorMessage());
            } catch (Exception $ex) {
                file_put_contents('tmp/logger_mail_'.date("d.m.Y").".log",date("d.m.Y_His").' - LOG MAIL SEND ERROR '.$ex->getMessage().PHP_EOL.$ex->getTrace() ,FILE_APPEND);
              //  throw new EPageError(EPageError::CUSTOM_ERROR, $ex->getMessage());
            }

            if ($ok===false) {
                file_put_contents('tmp/logger_mail_'.date("d.m.Y").".log",date("d.m.Y_His").' - LOG MAIL SEND ERROR '.$error ,FILE_APPEND);
            }
        }

    }

?>
