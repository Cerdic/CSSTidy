<?php

require_once 'class.Text_Diff_Renderer_parallel.php';

class csstidy_csst extends SimpleExpectation
{
    /** Filename of test */
    var $filename;
    
    /** Test name */
    var $test;
    
    /** CSS for test to parse */
    var $css = '';
    
    /** Expected var_export() output of $css->css[41] (no at block) */
    var $expect = '';
    
    /** Expected var_export() output of $css->css (with at block) */
    var $fullexpect = '';
    
    /** Actual result */
    var $actual;
    
    /**
     * Loads this class from a file
     */
    function load($filename) {
        $this->filename = $filename;
        $fh = fopen($filename, 'r');
        $state = null;
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
                    continue;
                case '--CSS--':
                    $this->css    .= $line . "\n";
                    continue;
                case '--EXPECT--':
                    $this->expect .= $line . "\n";
                    continue;
                case '--FULLEXPECT--':
                    $this->fullexpect .= $line . "\n";
                    continue;
            }
        }
        $this->expect = trim($this->expect, "\n"); // trim trailing/leading newlines
        fclose($fh);
    }
    
    function test($filename) {
        $this->load($filename);
        $css = new csstidy();
        $css->parse($this->css);
        $this->actual = var_export($css->css[41], true);
        return $this->expect === $this->actual;
    }
    
    function testMessage() {
        $message = $this->test . ' test at '. htmlspecialchars($this->filename);
        return $message;
    }
    
    /**
     * Renders the test
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
