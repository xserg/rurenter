<?php
// $Id: simple_include.php 21 2010-10-28 06:25:35Z xxserg $
//
// This testsuite requires SimpleTest.
// You can find it here:
// http://www.lastcraft.com/simple_test.php
//
if (!defined('SIMPLE_TEST')) {
    define('SIMPLE_TEST', '../simpletest/');
}

require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');
require_once(SIMPLE_TEST . 'mock_objects.php');
?>