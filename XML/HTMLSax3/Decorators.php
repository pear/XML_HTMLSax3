<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Alexander Zhukov <alex@veresk.ru> Original port from Python |
// | Authors: Harry Fuecks <hfuecks@phppatterns.com> Port to PEAR + more  |
// | Authors: Many @ Sitepointforums Advanced PHP Forums                  |
// +----------------------------------------------------------------------+
//
// $Id: Decorators.php,v 1.2 2007/10/29 21:41:35 hfuecks Exp $
//
/**
 * Decorators for dealing with parser options
 * @package XML_HTMLSax3
 * @version $Id: Decorators.php,v 1.2 2007/10/29 21:41:35 hfuecks Exp $
 * @see XML_HTMLSax3::set_option()
 */

/**
 * Base decorator class to be extended
 * @package XML_HTMLSax3
 */
abstract class XML_HTMLSax3_Decorator
{
    /**
     * Original handler object
     * @var object
     * @access private
     */
    var $orig_obj;
    /**
     * Original handler method
     * @var string
     * @access private
     */
    var $orig_method;

    /**
     * XML_HTMLSax3_Decorator constructor
     * @param object $orig_obj Handler object being decorated
     * @param string $orig_method Original handler method
     */
    public function __construct($orig_obj, $orig_method)
    {
        $this->orig_obj = $orig_obj;
        $this->orig_method = $orig_method;
    }
}

/**
 * Trims the contents of element data from whitespace at start and end
 * @package XML_HTMLSax3
 * @access protected
 */
class XML_HTMLSax3_Trim extends XML_HTMLSax3_Decorator
{
    /**
     * Trims the data
     * @param XML_HTMLSax3 $parser
     * @param string $data Element data
     * @access protected
     * @return void
     */
    function trimData($parser, $data)
    {
        $data = trim($data);
        if ($data != '') {
            $this->orig_obj->{$this->orig_method}($parser, $data);
        }
    }
}

/**
 * Converts tag names to upper case
 * @package XML_HTMLSax3
 * @access protected
 */
class XML_HTMLSax3_CaseFolding extends XML_HTMLSax3_Decorator
{
    /**
     * Original open handler method
     * @var string
     * @access private
     */
    var $orig_open_method;
    /**
     * Original close handler method
     * @var string
     * @access private
     */
    var $orig_close_method;

    /**
     * XML_HTMLSax3_CaseFolding constructor
     * @param object $orig_obj Handler object being decorated
     * @param string $orig_method Original handler method
     * @param string $orig_open_method Original open handler method
     * @param string $orig_close_method Original close handler method
     */
    public function __construct($orig_obj, $orig_method, $orig_open_method, $orig_close_method)
    {
        parent::__construct($orig_obj, $orig_method);
        $this->orig_open_method = $orig_open_method;
        $this->orig_close_method = $orig_close_method;
    }

    /**
     * Folds up open tag callbacks
     * @param XML_HTMLSax3 $parser
     * @param string $tag Tag name
     * @param array $attrs Tag attributes
     * @param bool $empty
     * @access protected
     */
    function foldOpen($parser, $tag, $attrs = array(), $empty = false)
    {
        $this->orig_obj->{$this->orig_open_method}($parser, strtoupper($tag), $attrs, $empty);
    }

    /**
     * Folds up close tag callbacks
     * @param XML_HTMLSax3 $parser
     * @param string $tag Tag name
     * @param bool $empty
     * @access protected
     */
    function foldClose($parser, $tag, $empty = false)
    {
        $this->orig_obj->{$this->orig_close_method}($parser, strtoupper($tag), $empty);
    }
}

/**
 * Breaks up data by linefeed characters, resulting in additional
 * calls to the data handler
 * @package XML_HTMLSax3
 * @access protected
 */
class XML_HTMLSax3_Linefeed extends XML_HTMLSax3_Decorator
{
    /**
     * Breaks the data up by linefeeds
     * @param XML_HTMLSax3 $parser
     * @param string $data element data
     * @access protected
     */
    function breakData($parser, $data)
    {
        $data = explode("\n",$data);
        foreach ( $data as $chunk ) {
            $this->orig_obj->{$this->orig_method}($parser, $chunk);
        }
    }
}

/**
 * Breaks up data by tab characters, resulting in additional
 * calls to the data handler
 * @package XML_HTMLSax3
 * @access protected
 */
class XML_HTMLSax3_Tab extends XML_HTMLSax3_Decorator
{
    /**
     * Breaks the data up by linefeeds
     * @param XML_HTMLSax3 $parser
     * @param string $data Element data
     * @access protected
     */
    function breakData($parser, $data)
    {
        $data = explode("\t", $data);
        foreach ( $data as $chunk ) {
            $this->orig_obj->{$this->orig_method}($this, $chunk);
        }
    }
}

/**
 * Breaks up data by XML entities and parses them with html_entity_decode(),
 * resulting in additional calls to the data handler
 * @package XML_HTMLSax3
 * @access protected
 */
class XML_HTMLSax3_Entities_Parsed extends XML_HTMLSax3_Decorator
{
    /**
     * Breaks the data up by XML entities
     * @param XML_HTMLSax3 $parser
     * @param string $data Element data
     * @access protected
     */
    function breakData($parser, $data)
    {
        $data = preg_split('/(&.+?;)/', $data, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        foreach ( $data as $chunk ) {
            $chunk = html_entity_decode($chunk, ENT_NOQUOTES);
            $this->orig_obj->{$this->orig_method}($this, $chunk);
        }
    }
}

/**
 * Breaks up data by XML entities but leaves them unparsed,
 * resulting in additional calls to the data handler
 * @package XML_HTMLSax3
 * @access protected
 */
class XML_HTMLSax3_Entities_Unparsed extends XML_HTMLSax3_Decorator
{
    /**
     * Breaks the data up by XML entities
     * @param XML_HTMLSax3 $parser
     * @param string $data Element data
     * @access protected
     */
    function breakData($parser, $data)
    {
        $data = preg_split('/(&.+?;)/', $data, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        foreach ( $data as $chunk ) {
            $this->orig_obj->{$this->orig_method}($this, $chunk);
        }
    }
}

/**
 * Strips the HTML comment markers or CDATA sections from an escape.
 * If XML_OPTIONS_FULL_ESCAPES is on, this decorator is not used.
 * @package XML_HTMLSax3
 * @access protected
 */
class XML_HTMLSax3_Escape_Stripper extends XML_HTMLSax3_Decorator
{
    /**
     * Breaks the data up by XML entities
     * @param XML_HTMLSax3 $parser
     * @param string $data Element data
     * @access protected
     */
    function strip($parser, $data)
    {
        // Check for HTML comments first
        if ( substr($data,0,2) == '--' ) {
            $patterns = array(
                '/^\-\-/',          // Opening comment: --
                '/\-\-$/',          // Closing comment: --
            );
            $data = preg_replace($patterns,'',$data);

        // Check for XML CDATA sections (note: don't do both!)
        } else if ( substr($data,0,1) == '[' ) {
            $patterns = array(
                '/^\[.*CDATA.*\[/s', // Opening CDATA
                '/\].*\]$/s',       // Closing CDATA
                );
            $data = preg_replace($patterns,'',$data);
        }

        $this->orig_obj->{$this->orig_method}($this, $data);
    }
}
