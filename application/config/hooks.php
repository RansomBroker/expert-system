<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__.'/../../vendor/autoload.php';

use Dotenv\Dotenv;
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/
$dotenv = Dotenv::createImmutable(__DIR__.'/../../');
$dotenv->load();