<?php
/*
Plugin Name: G28 B2BKing
Plugin URI: #
Description: Adiciona funcionalidades extras ao plugin B2BKing
Version: 0.2.6
Author: Guilherme Pereira - G28
Author URI: #
Text Domain: g28-b2bkingext
Domain Path: /languages
*/

use G28\B2bkingext\Startup;

if ( ! defined( 'ABSPATH' ) ) exit;

require "vendor/autoload.php";

$startup = Startup::getInstance();
$startup->run( __FILE__ );