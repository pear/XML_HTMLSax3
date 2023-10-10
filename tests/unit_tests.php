<?php
/**
* Requires SimpleTest version 1.0Alpha8 or higher.
* Unit Tests using the SimpleTest framework:
* http://www.lastcraft.com/simple_test.php
* @package XML
* @version $Id: unit_tests.php,v 1.3 2004/06/02 14:23:48 hfuecks Exp $
*/

// Include PHPUnit using composer
if (is_readable('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
} else {
    require_once 'PHPUnit/Autoload.php';
}
require_once 'XML/HTMLSax3.php';
require_once 'XML/HTMLSax3/States.php';
require_once 'XML/HTMLSax3/Decorators.php';

use PHPUnit\Framework\TestCase;

/**
* @package XML
* @version $Id: xml_htmlsax_test.php,v 1.3 2004/05/28 11:53:48 hfuecks Exp $
*/
class ListenerInterface {
    function __construct() { }
    function startHandler($parser, $name, $attrs) { }
    function endHandler($parser, $name) { }
    function dataHandler($parser, $data) { }
    function piHandler($parser, $target, $data) { }
    function escapeHandler($parser, $data) { }
    function jaspHandler($parser, $data) { }
}

class ParserTestCase extends TestCase
{
    var $parser;
    var $listener;

    function setUp() {
        $this->listener = $this->getMock('ListenerInterface', null, array($this));
        $this->parser = new XML_HTMLSax3();
        $this->parser->set_object($this->listener);
        $this->parser->set_element_handler('startHandler','endHandler');
        $this->parser->set_data_handler('dataHandler');
        $this->parser->set_escape_handler('escapeHandler');
        $this->parser->set_pi_handler('piHandler');
        $this->parser->set_jasp_handler('jaspHandler');
    }
}
