<?php

require_once 'tests/unit_tests.php';

class CommentsTest extends ParserTestCase
{
    function testSimple()
    {
        $this->listener->expects($this->once())->method('escapeHandler')->with(array('*', ' A comment '));
        $this->parser->set_option('XML_OPTION_STRIP_ESCAPES');
        $this->parser->parse('<!-- A comment -->');
    }
    function testNasty()
    {
        $this->listener->expects($this->once())->method('escapeHandler')->with(array('*', ' <tag></tag><?php ?><' . '% %> '));
        $this->parser->set_option('XML_OPTION_STRIP_ESCAPES');
        $this->parser->parse('<tag><!-- <tag></tag><?php ?><' . '% %> --></tag>');
    }
    function testFullEscapes()
    {
        $this->listener->expects($this->once())->method('escapeHandler')->with(array('*', '-- A comment --'));
        $this->parser->parse('a<!-- A comment -->b');
    }
    function testWordEscape()
    {
        $this->listener->expects($this->once())->method('escapeHandler')->with(array('*', '[endif]'));
        $this->parser->set_option('XML_OPTION_STRIP_ESCAPES');
        $this->parser->parse('a<![endif]>b');
    }
    function testWordEscapeNasty()
    {
        $this->listener->expects($this->once())->method('escapeHandler')->with(array('*', '[if gte mso 9]><xml></xml><![endif]'));
        $this->parser->set_option('XML_OPTION_STRIP_ESCAPES');
        $this->parser->parse('a<!--[if gte mso 9]><xml></xml><![endif]-->b');
    }
    /**
    * Parser should probably report some kind of error here.
    */
    function testBadlyFormedComment()
    {
        $this->listener->expects($this->once())->method('escapeHandler')->with(array('*', ' This is badly formed>b'));
        $this->parser->set_option('XML_OPTION_STRIP_ESCAPES');
        $this->parser->parse('a<!-- This is badly formed>b');
    }
    /**
    * Parser should probably report some kind of error here.
    */
    function testBadlyFormedCDATA()
    {
        $this->listener->expects($this->once())->method('escapeHandler')->with(array('*', ' This is badly formed>b'));
        $this->parser->set_option('XML_OPTION_STRIP_ESCAPES');
        $this->parser->parse('a<![CDATA[ This is badly formed>b');
    }
}
