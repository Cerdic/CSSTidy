<?php

/**@file
 * Utility functions and classes for unit testing
 */

function globr($sDir, $sPattern, $nFlags = NULL) {
    $aFiles = glob("$sDir/$sPattern", $nFlags);
    $files = getDir($sDir);
    if (is_array($files)) {
        foreach( $files as $file ) {
            $aSubFiles = globr($file, $sPattern, $nFlags);
            $aFiles = array_merge($aFiles,$aSubFiles);
        }
    }
    return $aFiles;
}

function getDir($sDir) {
    $i=0;
    $aDirs = array();
    if(is_dir($sDir)) {
        if($rContents = opendir($sDir)) {
            while($sNode = readdir($rContents)) {
                if(is_dir($sDir.'/'.$sNode )) {
                    if($sNode !="." && $sNode !="..") {
                        $aDirs[$i] = $sDir.'/'.$sNode ;
                        $i++;
                    }
                }
            }
        }
    }
    return $aDirs;
}

class csstidy_test_csst
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
    
    /**
     * Runs test
     * @return TRUE if success
     */
    function run() {
        $css = new csstidy();
        $css->parse($this->css);
        $this->actual = var_export($css->css[41], true);
        return $this->expect === $this->actual;
    }
    
    /**
     * Renders the test
     */
    function render() {
        if ($this->actual === null) $this->run();
        $heading = '<strong>'. htmlspecialchars($this->test) .'</strong> test (<tt>'. htmlspecialchars($this->filename) .'</tt>)';
        if ($this->expect === $this->actual) {
            return;
            // comment the above return to show passes
            echo "<div><span style=\"color:green;\">Passed:</span> $heading</div>";
            return;
        }
        echo "<div><span style=\"color:red;\">Failed:</span> $heading</div>";
        echo '<pre>'. htmlspecialchars($this->css) .'</pre>';
        $diff = new Text_Diff('auto', array(explode("\n", $this->expect), explode("\n", $this->actual)));
        $renderer = new Text_Diff_Renderer_parallel();
        $renderer->original = 'Expected';
        $renderer->final    = 'Actual';
        echo $renderer->render($diff);
    }
}

class Text_Diff_Renderer_parallel extends Text_Diff_Renderer
{
    var $original = 'Original';
    var $final    = 'Final';
    var $_leading_context_lines = 10000;
    var $_trailing_context_lines = 10000;
    function _blockHeader() {}
    function _startDiff() {
        return '<table class="diff"><thead><tr><th>'. $this->original .'</th><th>'. $this->final .'</th></tr></thead><tbody>';
    }
    function _endDiff() {
        return '</tbody></table>';
    }
    function _context($lines) {
        return '<tr><td><pre>'. htmlspecialchars(implode("\n", $lines)) .'</pre></td>
          <td><pre>'. htmlspecialchars(implode("\n", $lines)) .'</pre></td></tr>';
    }
    function _added($lines) {
        return '<tr><td>&nbsp;</td><td class="added"><pre>'. htmlspecialchars(implode("\n", $lines)) .'</pre></td></tr>';
    }
    function _deleted($lines) {
        return '<tr><td class="deleted"><pre>'. htmlspecialchars(implode("\n", $lines)) .'</pre></td><td>&nbsp;</td></tr>';
    }
    function _changed($orig, $final) {
        return '<tr class="changed"><td><pre>'. htmlspecialchars(implode("\n", $orig)) .'</pre></td>
        <td><pre>'. htmlspecialchars(implode("\n", $final)) .'</pre></td></tr>';
    }
}
