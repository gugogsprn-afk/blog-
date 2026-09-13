<?php
 

    class ESecurityError extends Exception {
        
      const WRONG_PASS = 'Wrong login or password';
      const BLANK_PASS = 'Blank login or password';
      const ACCESS_DENIED = 'Access_denied';
      const UNKNOWN_ERROR = 'Unknown_error';
      const WRONG_CAPTCHA = 'Wrong captcha code';
      const NOT_ACTIVATED = 'Account not activated';
      const ACTIVATION_EXPIRED = 'Activation link expired';
      const ALREADY_ACTIVATED = 'Account already activated';
      const USER_NOT_FOUND = 'User not found';
      const REGISTER_ERROR = 'register_error';
      const PASS_RESET_ERROR = 'pass_reset_error';
      const PASS_CHANGE_ERROR = 'pass_change_error';
      const WRONG_LOGIN_PASS = 'wrong_login_pass';  
      const MEMBER_EXPIRED = 'Member expited';
      const MAIL_SEND_ERROR = 'Mail send error';
        
      
      public $ErrType = self::UNKNOWN_ERROR;

      public function __construct($err){
        $this->ErrType = $err;
        $msg = $this->getTMessage();
        parent::__construct($msg);
      }
      
      private function getTMessage() {
          $ret = '';
          switch ($this->ErrType) {
              case ESecurityError::ACCESS_DENIED:
                  $ret = 'Доступ запрещен';
                  break;
              case ESecurityError::ACTIVATION_EXPIRED:
                  $ret = 'Срок активации учётной записи истёк, регистрируйтесь заного';
                  break;
              case ESecurityError::ALREADY_ACTIVATED:
                  $ret = 'Учётная запись уже активирована';
                  break;
              case ESecurityError::BLANK_PASS:
                  $ret = 'Введите пароль';
                  break;
              case ESecurityError::NOT_ACTIVATED:
                  $ret = 'Учётная запись не активирована, пожалуйста активируйте.Активация выслана Вам на почту заполненной во время регистрации';
                  break;
              case ESecurityError::UNKNOWN_ERROR:
                  $ret = 'Доступ запрещен.';
                  break;
              case ESecurityError::USER_NOT_FOUND:
                  $ret = 'Учётная запись не найдена';
                  break;
              case ESecurityError::WRONG_CAPTCHA:
                  $ret = 'Неверный код безопасности';
                  break;
              case ESecurityError::WRONG_PASS:
                  $ret = 'Неверный пароль';
                  break;
              case ESecurityError::REGISTER_ERROR:
                  $ret = 'Ошибка регистрации';
                  break;
              case ESecurityError::PASS_RESET_ERROR:
                  $ret = 'Ошибка восстановления пароля';
                  break;
              case ESecurityError::PASS_CHANGE_ERROR:
                  $ret = 'Ошибка изменение пароля, пожалуйста попробуйте позже.';
                  break;
              case ESecurityError::WRONG_LOGIN_PASS:
                  $ret = 'Неверный логин или пароль';
                  break;
              case ESecurityError::MEMBER_EXPIRED:
                  $ret = 'Время использования учетной записи истек.';
                  break;
              case ESecurityError::MAIL_SEND_ERROR:
                  $ret = 'Ошибка во время отправления регистрационного письма, пожалуйста попробуйте позже';
                  break;
              default:
                  $ret = 'Неизвестная ошибка';
                  break;
          }
          return $ret;
          
      }
  
   }
    
   
//<!--?-->
?>