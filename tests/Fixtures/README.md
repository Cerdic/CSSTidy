# CSSTidy Fixtures

These tests are for CSSTidy's parsing algorithms. They take this form:

- `file.php`
  - Test name & settings
- `file.css`
  - CSS to parse
- `file.expected.css` OR `file.expected.php`
  - var_export() representation of csstidy->css[41]

Note carefully that EXPECT is for csstidy->css[41], not csstidy->css.  
This is because, by default, all declarations are placed inside the  
DEFAULT_AT section. For tests that need to make use of at selectors, use

- `file.full-expected.php`
  - var_export() representation of csstidy->css

...instead.

See also: CssTidyTest.php (the implementation of these tests) and
TestCase.php (the caller stub for PHPunit)
