# XML_HTMLSax3

All notable changes to this project will be documented in this file.

--------------------------------------------------------------------------------

## [4.0.0] - 2023-07-27
* Add travis [CloCkWeRX]
* Swap tests to PHPUnit [CloCkWeRX]
* Drop PHP4 reference syntax [CloCkWeRX]
* Add composer.json and License file based on package.xml info [g-scalvini]
* PSR-2 compliancy - Convert line separator from CRLF to LF [g-scalvini]
* [BK - Drop PHP 4 support] PHP 7 compatibility - Remove PHP 4 constructors [alex-vlasov]
* [BK - Drop PHP 4 support] Fix typos and remove dead code for PHP before v4.3 [alex-vlasov]

## [3.0.0] - 2007-12-01
* Fixed bug #1850  HTMLtoXHTML.php does not produce XHTML [dufuz]
* Fixed bug #11607 Requesting License change, emails to listed authors bounce [cdake]
* Fixed bug #12159 not clarified license [hfuecks]
* This package is now dual licensed under PHP license v3.01 and LGPL 3.0

## [3.0.0RC1] - 2004-06-02
* Re PEAR version naming rules, you now include XML/HTMLSax3.php and the main class is called XML_HTMLSax3
* Now able to parse Word generated HTML - fixed bug with parsing of XML escape sequences
* API break (minor): no longer extends PEAR
* API break (minor): attributes with no value (like option selected) are now populated with NULL instead of TRUE
* API break (minor): replaced XML_OPTION_FULL_ESCAPES with XML_OPTION_STRIP_ESCAPES - by default you now get back the complete escape sequence
* Added some more examples

## [2.1.2] - 2003-12-05
* Bug fixed (thanks Jeff) where badly formed attributes resulted in infinite loop
* Added additional boolean argument to open and close handler calls to spot empty tags like br/ - should not break exising APIs
* Added XML_OPTION_FULL_ESCAPES which (when = 1) passes through the complete content in an XML escape, allowing comment / cdata reconstruction

## [2.1.1] - 2003-10-08
* Reporting of byte index with get_current_position() more accurate on opening tags (thanks to Alexander Orlov at x-code.com)
* All parser options now available to PHP versions lt 4.3.x, using implementation of html_entity_decode in PHP

## [2.1.0] - 2003-09-10
* Well (unit) tested with SimpleTest

## [2.0.2] - 2003-08-11
* API is backwards compatible apart from the renaming of parser options
* Performance dramatically increased. Not much slower than Expat
* Better handling of XML comments and CDATA
* Option to trigger additional data handler calls for linefeeds and tabs
* Option to trigger additional data handler calls for XML entities and parse them if required.
* Added public get_current_position() and get_length() methods

## [1.1] - 2003-06-26
* Bug fixes to Attribute_Parser to cope with newline, tag, forward slash and whitespace issues.

## [1.0] - 2003-06-08
* Modifications to file structure to place Attributes_Parser.php and State_Machine.php in subdirectory HTMLSax
* XML_HTMLSax.php includes Attributes_Parser.php and State_Machine.php using require_once()

## [0.9.0rc2] - 2003-05-18
* First release under PEAR
* Changed package name to XML_HTMLSax
* Added patch from John Luxford to parse single quoted attributes
* Modified State_Machine to be a simple variable store

## [0.9.0rc1] - 2003-05-09
A summary of the main differences between this version of HTML_Sax and HTMLSax2002082201 are as follows;
* Instead of extending HTMLSax with your own &quot;handlers&quot; class, you now use the set_object() method to pass an instance of the class to HTMLSax.
* Class method callbacks are specified using the following methods;
* set_element_handler(&apos;startHandler&apos;,&apos;endHandler&apos;) &lt;tag&gt; and &lt;/tag&gt;
* set_data_handler(&apos;dataHandler&apos;) for contents of an element
* set_pi_handler(&apos;piHandler&apos;) for &lt;?php ?&gt;, &lt;?xml ?&gt; etc.
* set_escape_handler(&apos;) for anything beginning with &lt;!
* set_jasp_handler() - set listener for &lt;% %&gt; tags
* Attributes which no value are created and set to true
* Comments are handled and may contain entities; &lt; &gt;
* The callback handlers will all be passed an instance of HTMLSax in the same way as the native PHP XML Expat extension
* Setting of parser options is handled specifically by the set_option() method. Available options are:
  - skipWhiteSpace; instruct the parser to ignore whitespace characters
  - trimDataNodes; trim whitespace inside character data
  - breakOnNewLine; newline characters found in character data are treated as new events triggering another data callback
  - caseFolding; converts element names to uppercase
