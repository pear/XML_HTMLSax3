<?php

require_once 'tests/unit_tests.php';

class ElementsTest extends ParserTestCase
{
    function testEmptyElement()
    {
        $this->listener->expects('startHandler', array('*', 'tag', array(), false));
        $this->listener->expects('endHandler', array('*', 'tag', false));
        $this->listener->expectNever('dataHandler');
        $this->parser->parse('<tag></tag>');
    }
    function testElementWithContent()
    {
        $this->listener->expects('startHandler', array('*', 'tag', array(), false));
        $this->listener->expects('dataHandler', array('*', 'stuff'));
        $this->listener->expects('endHandler', array('*', 'tag', false));
        $this->parser->parse('<tag>stuff</tag>');
    }
    function testMismatchedElements()
    {
        // $this->listener->expectArgumentsAt(0, 'startHandler', array('*', 'b', array(), false));
        // $this->listener->expectArgumentsAt(1, 'startHandler', array('*', 'i', array(), false));
        // $this->listener->expectArgumentsAt(0, 'endHandler', array('*', 'b', false));
        // $this->listener->expectArgumentsAt(1, 'endHandler', array('*', 'i', false));
        $this->listener->expects($this->exactly(2))->method('startHandler');
        $this->listener->expects($this->exactly(2))->method('endHandler');
        $this->parser->parse('<b><i>stuff</b></i>');
    }
    function testCaseFolding()
    {
        $this->listener->expects('startHandler', array('*', 'TAG', array(), false));
        $this->listener->expects('dataHandler', array('*', 'stuff'));
        $this->listener->expects('endHandler', array('*', 'TAG', false));
        $this->parser->set_option('XML_OPTION_CASE_FOLDING');
        $this->parser->parse('<tag>stuff</tag>');
    }
    function testEmptyTag()
    {
        $this->listener->expects('startHandler', array('*', 'tag', array(), true));
        $this->listener->expectNever('dataHandler');
        $this->listener->expects('endHandler', array('*', 'tag', true));
        $this->parser->parse('<tag />');
    }
    function testAttributes()
    {
        $this->listener->expects(
            'startHandler',
            array('*', 'tag', array("a" => "A", "b" => "B", "c" => "C"), false)
        );
        $this->parser->parse('<tag a="A" b=\'B\' c = "C">');
    }
    function testEmptyAttributes()
    {
        $this->listener->expects(
            'startHandler',
            array('*', 'tag', array("a" => null, "b" => null, "c" => null), false)
        );
        $this->parser->parse('<tag a b c>');
    }
    function testNastyAttributes()
    {
        $this->listener->expects(
            'startHandler',
            array('*', 'tag', array("a" => "&%$'?<>", "b" => "\r\n\t\"", "c" => ""), false)
        );
        $this->parser->parse("<tag a=\"&%$'?<>\" b='\r\n\t\"' c = ''>");
    }
    function testAttributesPadding()
    {
        $this->listener->expects(
            'startHandler',
            array('*', 'tag', array("a" => "A", "b" => "B", "c" => "C"), false)
        );
        $this->parser->parse("<tag\ta=\"A\"\rb='B'\nc = \"C\"\n>");
    }
}
