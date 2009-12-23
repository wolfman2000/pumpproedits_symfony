<?php

class BaseMessage extends Swift_Message
{
  public function __construct($subject, $body)
  {
    parent::__construct($subject, $body);
  }

  public function signature()
  {
    return 'Jason "Wolfman2000" Felds' . "\nSymfony Mailer";
  }
}
