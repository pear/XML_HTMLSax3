x<?php
require_once 'tests/unit_tests.php';

class ElementsTest extends ParserTestCase {

    function testEmptyElement() {
        $this->listener->expects('startHandler', array('*', 'tag', array(),FALSE));
        $this->listener->expects('endHandler', array('*', 'tag',FALSE));
        $this->listener->expectNever('dataHandler');
        $this->parser->parse('<tag></tag>');
    }
    function testElementWithContent() {
        $this->listener->expects('startHandler', array('*', 'tag', array(),FALSE));
        $this->listener->expects('dataHandler', array('*', 'stuff'));
        $this->listener->expects('endHandler', array('*', 'tag',FALSE));
        $this->parser->parse('<tag>stuff</tag>');
    }
    function testMismatchedElements() {
        // $this->listener->expectArgumentsAt(0, 'startHandler', array('*', 'b', array(),FALSE));
        // $this->listener->expectArgumentsAt(1, 'startHandler', array('*', 'i', array(),FALSE));
        // $this->listener->expectArgumentsAt(0, 'endHandler', array('*', 'b',FALSE));
        // $this->listener->expectArgumentsAt(1, 'endHandler', array('*', 'i',FALSE));
        $this->listener->expects($this->exactly(2))->method('startHandler');
        $this->listener->expects($this->exactly(2))->method('endHandler');
        $this->parser->parse('<b><i>stuff</b></i>');
    }
    function testCaseFolding() {
        $this->listener->expects('startHandler', array('*', 'TAG', array(),FALSE));
        $this->listener->expects('dataHandler', array('*', 'stuff'));
        $this->listener->expects('endHandler', array('*', 'TAG',FALSE));
        $this->parser->set_option('XML_OPTION_CASE_FOLDING');
        $this->parser->parse('<tag>stuff</tag>');
    }
    function testEmptyTag() {
        $this->listener->expects('startHandler', array('*', 'tag', array(),TRUE));
        $this->listener->expectNever('dataHandler');
        $this->listener->expects('endHandler', array('*', 'tag',TRUE));
        $this->parser->parse('<tag />');
    }
    function testAttributes() {
        $this->listener->expects(
                'startHandler',
                array('*', 'tag', array("a" => "A", "b" => "B", "c" => "C"),FALSE));
        $this->parser->parse('<tag a="A" b=\'B\' c = "C">');
    }
    function testEmptyAttributes() {
        $this->listener->expects(
                'startHandler',
                array('*', 'tag', array("a" => NULL, "b" => NULL, "c" => NULL),FALSE));
        $this->parser->parse('<tag a b c>');
    }
    function testNastyAttributes() {
        $this->listener->expects(
                'startHandler',
                array('*', 'tag', array("a" => "&%$'?<>", "b" => "\r\n\t\"", "c" => ""),FALSE));
        $this->parser->parse("<tag a=\"&%$'?<>\" b='\r\n\t\"' c = ''>");
    }
    function testAttributesPadding() {
        $this->listener->expects(
                'startHandler',
                array('*', 'tag', array("a" => "A", "b" => "B", "c" => "C"),FALSE));
        $this->parser->parse("<tag\ta=\"A\"\rb='B'\nc = \"C\"\n>");
    }
}

