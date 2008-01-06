<?php

class csstidy_harness extends UnitTestCase
{
    function getTests() {
        // __onlytest makes only one test get triggered
        foreach (get_class_methods(get_class($this)) as $method) {
            if (strtolower(substr($method, 0, 10)) == '__onlytest') {
                return array($method);
            }
        }
        return parent::getTests();
    }
}