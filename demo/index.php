<?php 

require_once '../kk.php';

$app = new \kk\App('.');

$app->handle(new \kk\auth\tasks\AuthTask());
