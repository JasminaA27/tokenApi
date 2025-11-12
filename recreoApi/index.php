<?php
// Este archivo sirve como punto de entrada público para recreoApi
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/RecreoApiController.php';

$controller = new RecreoApiController();
$controller->publicView();
?>