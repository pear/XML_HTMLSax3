<?php
/***
 * $Id: SimpleTemplate.php,v 1.2 2004/06/02 14:33:38 hfuecks Exp $
 * Shows how HTMLSax can be used for template parsing
 */
require_once('XML/HTMLSax3.php');

class SimpleTemplate
{
    var $vars = array();

    var $output = '';

    public function __construct()
    {
    }

    function setVar($name, $value)
    {
        $this->vars[$name] = $value;
    }

    function display()
    {
        echo $this->output;
    }

    // Notice fourth argument
    function open(& $parser, $name, $attrs, $empty)
    {
        // Should check more carefully but this is just an example...
        if ($name == 'var') {
            if (isset($this->vars[$attrs['name']])) {
                $this->output.= $this->vars[$attrs['name']];
            }
        } else {
            $tag = "<$name";
            foreach ($attrs as $key => $value) {
                if (is_null($value)) {
                    $tag .= ' '.$key;
                } else {
                    $tag .= " $key=\"$value\"";
                }
            }
            if ($empty) {
                $tag .= '/>';
            } else {
                $tag .= '>';
            }
            $this->output .= $tag;
        }
    }

    // Notice fourth argument
    function close(& $parser, $name, $empty)
    {
        if (!$empty) {
            $this->output.= "</$name>";
        }
    }

    function data(& $parser, $data)
    {
        $this->output .= $data;
    }

    function escape(& $parser, $data)
    {
        $this->output .= "<!$data>";
    }

    function pi(& $parser, $target, $data)
    {
        $this->output .= "<?$target $data?>";
    }

    function jasp(& $parser, $data)
    {
        $this->output .= "<%$data%>";
    }
}


$tpl = new SimpleTemplate();

$tpl->setVar('title', 'HTMLSax as a Template Parser');

$para1 = <<<EOD

HTMLSax can be used as the basis for a template engine, 
as with <a href="http://wact.sf.net">WACT</a> and 
<a href="http://phpoot.sourceforge.jp/">PHPOOT</a>. For 
the most part is allows you to preserve the structure of
original template, preserving whitespace and so on with
one or two minor exceptions, such as whitespace between
attributes and the quotes used for attributes. Compare
the source template for this example with the output.

EOD;
$tpl->setVar('para1', $para1);

$para2 = <<<EOD

Notice also how the fourth argument to the open and close handlers
is used (see the PHP source) - this allows you to correctly
"rebuild" tags like &lt;div /&gt; vs. &lt;div&gt;&lt;/div&gt;

EOD;
$tpl->setVar('para2', $para2);

// Instantiate the parser
$parser = new XML_HTMLSax3();

// Register the handler with the parser
$parser->set_object($tpl);

// Set a parser option
$parser->set_option('XML_OPTION_FULL_ESCAPES');

// Set the handlers
$parser->set_element_handler('open', 'close');
$parser->set_data_handler('data');
$parser->set_escape_handler('escape');
$parser->set_pi_handler('pi');
$parser->set_jasp_handler('jasp');

// Parse the document
$parser->parse(file_get_contents('simpletemplate.tpl'));

$tpl->display();
