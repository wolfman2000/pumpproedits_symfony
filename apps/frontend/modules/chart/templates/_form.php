<?php
$params['form'] = $form;
$params['route'] = $route;
$params['mpart'] = true;
slot('legend', 'Select your .edit file.');
include_partial("global/form_base", $params);
