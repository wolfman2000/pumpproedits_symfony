<?php

class ResetPasswordMessage extends BaseMessage
{
  public function __construct($to, $name, $confirm)
  {
    $subject = 'Pump Pro Edits - Resetting Password';
    $body = <<<EOL
According to our records, you have requested to reset your
password for your account on Pump Pro Edits at
www.pumpproedits.com.

If you are that person, please go to the following URL:
http://www.pumpproedits.com/reset/%s

You will be asked to supply a new password of your choice.

If you did not request to reset your password, you may
delete this email and not worry about it.

EOL;
    parent::__construct($subject, sprintf($body, $confirm));
    $this->setFrom(array('jafelds@gmail.com' => 'Jason "Wolfman2000" Felds'));
    $this->setTo(array($to => $name));
    $this->setBcc(array('jafelds@gmail.com' => 'Jason "Wolfman2000" Felds'));
  }
}