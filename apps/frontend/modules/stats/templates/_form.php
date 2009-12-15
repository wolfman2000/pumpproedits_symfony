<?php
slot('mpart', true);
slot('legend', 'Select your .edit file.');
include_partial("global/form_base", array('form' => $form, 'route' => "@edit_stat_post"));
