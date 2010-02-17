<?php
$params['form'] = $form;
$params['route'] = $route;
$params['mpart'] = true;
slot('legend', $legend);
include_partial("global/form_base", $params);
