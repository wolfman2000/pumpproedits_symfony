<?php
$params['form'] = $form;
$params['route'] = '@rate_add_post';
slot('legend', 'Only the rating is required.');
include_partial("global/form_base", $params);
