<?php

/**
 * OFI PHP Framework
 * Crafted with ❤ By Teguh Rijanandi
 * ALL Rights Reserved
 * 
 * OFI is bassed from Nur Khofifah (OFI) name
 * PHP is a programming language
 * framework is an abstraction in which software 
 *           providing generic functionality
 * 
 * Contact : teguhrijanandi02@gmail.com
 */

// check is vendor folder exists?
if(!is_dir(dirname(__FILE__) . '/vendor')) {
    throw new Exception("Vendor folder not found, you must run composer instal first!");
}

require 'vendor/autoload.php';

/**
 * Run The Application
 */

$core = new ofi\ofi_php_framework\Core();
$core->run();
