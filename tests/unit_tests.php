<?php
/**
* Requires SimpleTest version 1.0Alpha8 or higher.
* Unit Tests using the SimpleTest framework:
* http://www.lastcraft.com/simple_test.php
* @package XML
* @version $Id: unit_tests.php,v 1.3 2004/06/02 14:23:48 hfuecks Exp $
*/
if (!defined('SIMPLE_TEST')) {
    define('SIMPLE_TEST', 'simpletest/');     // Add to php.ini path (should be the default).
}
require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'mock_objects.php');
require_once(SIMPLE_TEST . 'reporter.php');

if (!defined('XML_HTMLSAX3')) {
    define('XML_HTMLSAX3', '../');
}
require_once(XML_HTMLSAX3 . 'HTMLSax3.php');
require_once(XML_HTMLSAX3 . 'HTMLSax3/States.php');
require_once(XML_HTMLSAX3 . 'HTMLSax3/Decorators.php');

$test = &new GroupTest('XML::HTMLSax3 Tests');
$test->addTestFile('xml_htmlsax_test.php');
$test->run(new HtmlReporter());
?>