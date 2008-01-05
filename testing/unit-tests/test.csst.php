<?php

require_once 'class.csstidy_csst.php';

/**
 * Performs the csst tests in csst/ folder
 */
class csstidy_test_csst extends UnitTestCase
{
    function testAll() {
        foreach (globr(dirname(__FILE__) . '/csst', '*.csst') as $filename) {
            $expectation = new csstidy_csst();
            $result = $this->assert($expectation, $filename, '%s');
            // this is necessary because SimpleTest doesn't support
            // HTML messages; this probably should be in the reporter
            if (!$result) echo $expectation->render();
        }
    }
}
