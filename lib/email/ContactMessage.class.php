<?php

class ContactMessage extends BaseMessage
{
  public function __construct($from, $name, $subject, $body)
  {
    parent::__construct("PPEdits Contact Form - " . $subject, $body);
    $this->setFrom(array('jafelds@gmail.com' => 'Jason "Wolfman2000" Felds'));
    $this->setTo(array('jafelds@gmail.com' => 'Jason "Wolfman2000" Felds'));
    $this->setBcc(array('jafelds@gmail.com' => 'Jason "Wolfman2000" Felds'));
    $this->setReplyTo(array($from => $name));
  }
}