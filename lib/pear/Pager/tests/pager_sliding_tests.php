<?php
// $Id: pager_sliding_tests.php 21 2010-10-28 06:25:35Z xxserg $

require_once 'simple_include.php';
require_once 'pager_include.php';

class PagerSlidingTests extends GroupTest {
    function PagerSlidingTests() {
        $this->GroupTest('Pager_Sliding Tests');
        $this->addTestFile('pager_sliding_test.php');
        $this->addTestFile('pager_sliding_notExpanded_test.php');
        $this->addTestFile('pager_sliding_noData_test.php');
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = &new PagerTests();
    $test->run(new HtmlReporter());
}
?>