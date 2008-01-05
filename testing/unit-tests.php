<?php

/**@file
 * Script for unit testing, allows for more fine grained error reporting
 * when things go wrong.
 * @author Edward Z. Yang <admin@htmlpurifier.org>
 */

error_reporting(E_ALL);

require_once '../class.csstidy.php';

$simpletest_location = 'simpletest/';
if (file_exists('../test-settings.php')) include_once '../test-settings.php';

// PEAR
require_once 'Text/Diff.php';
require_once 'Text/Diff/Renderer.php';

require_once $simpletest_location . 'unit_tester.php';
require_once $simpletest_location . 'reporter.php';
// require_once $simpletest_location . 'mock_objects.php'; // we don't need it yet

require_once 'unit-tests.inc';

chdir(dirname(__FILE__));
$passed_tests = 0;
$failed_tests = 0;

?>
<html>
<title>CSSTidy unit tests</title>
<style type="text/css">
.box {color:white; font-weight:bold; padding: 8px; margin-top: 1em;}
.diff {margin-bottom: 1em;}
.diff th {width:50%;}
.diff pre {margin:0; padding:0;}
.diff .changed, .diff .deleted, .diff .added {background: #FF5;}
del {background: red; color: white; font-weight: bold;}
ins {background: green; color: white; font-weight: bold;}
</style>
<body>
<h1>CSSTidy unit tests</h1>
<?php

// allow only one directory deep, I guess otherwise, we need a recursive glob
foreach (globr('unit-tests/csst', '*.csst') as $filename) {
    $test = new csstidy_test_csst();
    $test->load($filename);
    if (!$test->run()) $failed_tests++;
    else $passed_tests++;
    $test->render();
}

// output test results
if (!$failed_tests) {
    echo '<div style="background:green;" class="box">';
} else {
    echo '<div style="background:red;" class="box">';
}

echo $passed_tests .' tests passed, ' . $failed_tests .' tests failed</div>'

?>
</body>
</html>