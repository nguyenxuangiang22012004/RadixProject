<?php
if (!defined('_INCODE')) die('Access Deined...');
$data = [
    'pageTitle' => getOption('about_title')
];

layout('header', 'client', $data);

layout('breadcrumb', 'client', $data);

require_once _WEB_PATH_ROOT.'/modules/home/contents/about.php';

require_once _WEB_PATH_ROOT.'/modules/home/contents/partner.php';

layout('footer', 'client');