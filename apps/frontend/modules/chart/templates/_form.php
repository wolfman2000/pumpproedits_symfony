<?php
$params['form'] = $form;
$params['route'] = '@chart_gen_post';
$params['mpart'] = true;
slot('legend', 'Select your .edit file.');
include_partial("global/form_base", $params);
