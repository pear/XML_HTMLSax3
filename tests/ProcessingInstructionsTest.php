<?php

require_once 'tests/unit_tests.php';

class ProcessingInstructionsTest extends ParserTestCase
{
    function testAllPi()            // Not correct on whitespace.
    {
        $this->listener->expects($this->once())
            ->method('piHandler')
            ->with(array('*', 'php', ' print "Hello"; '));
        // $this->listener->expectNever('dataHandler');
        // $this->listener->expectNever('startHandler');
        // $this->listener->expectNever('endHandler');
        $this->parser->parse('<?php print "Hello"; ?>');
    }
    function testNestedPi()            // Not correct on whitespace.
    {
        $this->listener->expects($this->once())
            ->method('piHandler')
            ->with(array('*', 'php', ' print "Hello"; '));
        // $this->listener->expectArgumentsAt(0, 'dataHandler', array('*', 'a'));
        // $this->listener->expectArgumentsAt(1, 'dataHandler', array('*', 'b'));
        $this->listener->expects($this->exactly(2))->method('dataHandler');
        // $this->listener->expectNever('startHandler');
        // $this->listener->expectNever('endHandler');
        $this->parser->parse('a<?php print "Hello"; ?>b');
    }
    function testEscapeHandler()
    {
        $this->listener->expects($this->once())
            ->method('escapeHandler')
            ->with(array('*', 'doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN"'));
        $this->parser->parse('<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">');
    }
    function testNestedEscapeHandler()
    {
        $this->listener->expects($this->once())
            ->method('escapeHandler')
            ->with(array('*', 'doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN"'));
        // $this->listener->expectArgumentsAt(0, 'dataHandler', array('*', 'a'));
        // $this->listener->expectArgumentsAt(1, 'dataHandler', array('*', 'b'));
        $this->listener->expects($this->exactly(2))->method('dataHandler');
        $this->parser->parse('a<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">b');
    }
}
