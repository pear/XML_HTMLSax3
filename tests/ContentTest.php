<?php

require_once 'tests/unit_tests.php';

class ContentTest extends ParserTestCase
{
    function testSimple()
    {
        $this->listener->expects($this->once())->method('dataHandler')->with(array('*', 'stuff'));
        $this->parser->parse('stuff');
    }
    function testPreservingWhiteSpace()
    {
        $this->listener->expects($this->once())->method('dataHandler')->with(array('*', " stuff\t\r\n "));
        $this->parser->parse(" stuff\t\r\n ");
    }
    function testTrimmingWhiteSpace()
    {
        $this->listener->expects($this->once())->method('dataHandler')->with(array('*', "stuff"));
        $this->parser->set_option('XML_OPTION_TRIM_DATA_NODES');
        $this->parser->parse(" stuff\t\r\n ");
    }
}
