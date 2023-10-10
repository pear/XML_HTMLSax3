<?php
/***
 * $Id: WordDoc.php,v 1.4 2004/06/02 14:33:38 hfuecks Exp $
 * Shows HTMLSax parsing Word generated HTML
 */
require_once('XML/HTMLSax3.php');

class MyHandler {

    public function __construct() {
    }

    function escape($parser,$data) {
        echo('<pre>'.$data."\n\n\n</pre>");
    }
}

$h = new MyHandler();

// Instantiate the parser
$parser = new XML_HTMLSax3();

$parser->set_object($h);
$parser->set_escape_handler('escape');

if ( isset($_GET['strip_escapes']) ) {
    $parser->set_option('XML_OPTION_STRIP_ESCAPES');
}
?>
<h1>Parsing Word Documents</h1>
<p>Shows HTMLSax parsing a simple Word generated HTML document and the impact of the option 'XML_OPTION_STRIP_ESCAPES' which can be set like;
<pre>
$parser->set_option('XML_OPTION_STRIP_ESCAPES');
</pre>
</p>
<p>Word generates some strange XML / HTML escape sequences like &lt;![endif]&gt; - now (3.0.0+) handled by HTMLSax correctly.</p>
<p>
    <a href="<?php echo $_SERVER['PHP_SELF']; ?>">XML_OPTION_STRIP_ESCAPES = 0</a> :
    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?strip_escapes=1">XML_OPTION_STRIP_ESCAPES = 1</a>
</p>
<p>Starting to parse...</p>
<?php
// Parse the document
$parser->parse(file_get_contents('worddoc.htm'));
?>
<p>Parsing completed</p>
