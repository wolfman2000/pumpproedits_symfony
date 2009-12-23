<?php

class RegisterConfirmationMessage extends BaseMessage
{
  public function __construct($to, $name, $confirm)
  {
    $subject = 'Pump Pro Edits - Registration Confirmation';
    $body = <<<EOL
According to our records, you have requested to become a member to
Pump Pro Edits at www.pumpproedits.com recently.

If you are that person, please go to the following URL:
http://www.pumpproedits.com/confirm/%s

Remember to also put your password in the form provided.

If you did not request to register, you may delete this email
and not worry about it.

EOL;
    parent::__construct($subject, sprintf($body, $confirm));
    $this->setFrom(array('jafelds@gmail.com' => 'Jason "Wolfman2000" Felds'));
    $this->setTo(array($to => $name));
  }
}