<?php

require_once 'class.Text_Diff_Renderer_parallel.php';

/**
 * CSSTidy CSST expectation, for testing CSS parsing.
 */
class csstidy_csst extends SimpleExpectation
{
    /** Filename of test */
    var $filename;
    
    /** Test name */
    var $test;
    
    /** CSS for test to parse */
    var $css = '';
    
    /** Settings for csstidy */
    var $settings = array();
    
    /** Expected var_export() output of $css->css[41] (no at block) */
    var $expect = '';
    
    /** Boolean whether or not to use $css->css instead for $expect */
    var $fullexpect = false;
    
    /** Actual result */
    var $actual;
    
    /**
     * Loads this class from a file.
     * @param $filename String filename to load
     */
    function load($filename) {
        $this->filename = $filename;
        $fh = fopen($filename, 'r');
        $state = '';
        while (($line = fgets($fh)) !== false) {
            $line = rtrim($line, "\n\r"); // normalize newlines
            if (substr($line, 0, 2) == '--') {
                // detected section
                $state = $line;
                continue;
            }
            if ($state === null) continue;
            switch ($state) {
                case '--TEST--':
                    $this->test    = trim($line);
                    break;
                case '--CSS--':
                    $this->css    .= $line . "\n";
                    break;
                case '--FULLEXPECT--':
                    $this->fullexpect = true; // no break!
                case '--EXPECT--':
                    $this->expect .= $line . "\n";
                    break;
                case '--SETTINGS--':
                    list($n, $v) = array_map('trim',explode('=', $line, 2));
                    $v = eval("return $v;");
                    $this->settings[$n] = $v;
                    break;
            }
        }
        $this->expect = trim($this->expect, "\n"); // trim trailing/leading newlines
        fclose($fh);
    }
    
    /**
     * Implements SimpleExpectation::test().
     * @param $filename Filename of test file to test.
     */
    function test($filename = false) {
        if ($filename) $this->load($filename);
        $css = new csstidy();
        $css->set_cfg($this->settings);
        $css->parse($this->css);
        if ($this->fullexpect) {
            $this->actual = var_export($css->css, true);
        } elseif (isset($css->css[41])) {
            $this->actual = var_export($css->css[41], true);
        } else {
            $this->actual = 'Key 41 does not exist';
        }
        return $this->expect === $this->actual;
    }
    
    /**
     * Implements SimpleExpectation::testMessage().
     */
    function testMessage() {
        $message = $this->test . ' test at '. htmlspecialchars($this->filename);
        return $message;
    }
    
    /**
     * Renders the test with an HTML diff table.
     */
    function render() {
        $message = '<pre>'. htmlspecialchars($this->css) .'</pre>';
        $diff = new Text_Diff('auto', array(explode("\n", $this->expect), explode("\n", $this->actual)));
        $renderer = new Text_Diff_Renderer_parallel();
        $renderer->original = 'Expected';
        $renderer->final    = 'Actual';
        $message .= $renderer->render($diff);
        return $message;
    }
}
