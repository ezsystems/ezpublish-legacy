<?php
//
// Definition of eZSOAPRequest class
//
// Created on: <29-May-2002 14:39:03 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

?>

<h1>Plain parsing</h1>

<p>
Parsing XML documents with eZ xml&trade; is simple, all you need to do is
pass the XML document to the <b>domTree()</b> function. The DOM object tree
will then be returned.
</p>

<p>
Below is the XML document used in this parser introduction.
</p>

<pre class="example">
&lt;?xml version='1.0'?&gt;
&lt;doc&gt;
  &lt;article&gt;
    &lt;paragraph name='Introduction'&gt;Paragraph contents&lt;/paragraph&gt;
    &lt;paragraph name='Story'&gt;Story paragraph contents&lt;/paragraph&gt;
  &lt;/article&gt;
&lt;/doc&gt;
</pre>

<p>
The code snippet below shows how you can parse an XML document and create a DOM
tree. The DOM tree is then used to manipulate the document.
</p>

<pre class="example">
// instantiate the XML parser
$xml = new eZXML();

$testDoc = "&lt;?xml version='1.0'?&gt;
&lt;doc&gt;
  &lt;article&gt;
    &lt;paragraph name='Introduction'&gt;Paragraph contents&lt;/paragraph&gt;
    &lt;paragraph name='Story'&gt;Story paragraph contents&lt;/paragraph&gt;
  &lt;/article&gt;
&lt;/doc&gt;";

// $dom now contains the DOM object tree, false is returned if parsing was
// unsuccessful
$dom =& $xml->domTree( $testDoc );
</pre>

<h2>Finding nodes</h2>
<p>
To find special nodes in the document you can use the <b>elementsByName()</b>
function. This will return an array of DOM nodes. The code snippet below
shows how you can find all paragraphs in the XML document.
</p>

<pre class="example">
// $paragraphs is an array of paragraph DOM nodes
$paragraphs =& $dom->elementsByName( "paragraph" );
</pre>

<h2>Fetching content</h2>
<p>
When you have all the nodes you need you can access the attributes and
content of these items. All nodes have a name, this should in this example
be <b>paragraph</b> as we only fetched nodes by that name. The function
<b>attributeValue()</b> is used to fetch the value of an attribute on the
current node. To fetch the contents of a node directly you can use the
<b>textContent()</b> function. Note that textcontent only works if it's
a plain text node. Normally you would have to check all the children of the
node if you expect subnodes of mixed type.
</p>

<pre class="example">
foreach ( $paragraphs as $paragraph )
{
    // get the name of the item, should be paragraph
    print( "New " . $paragraph->name() );
    // print the value of the name attribute
    print( "Name: " . $paragraph->attributeValue( "name" ) );
    // get the text content of the DOM node
    print( "Content: " . $paragraph->textContent() );
}
</pre>

<p>
This is the result of the above code:
</p>

<pre class="example">
New paragraph:
Name: Introduction
Content: Paragraph contents

New paragraph:
Name: Story
Content: Story paragraph contents
</pre>

<?php
// include_once( "lib/ezxml/classes/ezxml.php" );

// $xml = new eZXML();
// $testDoc = "< ?xml version='1.0'? >
// <doc>
//   <article>
//     <paragraph name='Introduction'>Paragraph contents</paragraph>
//     <paragraph name='Story'>Story paragraph contents</paragraph>
//   </article>
// </doc>";

// $dom =& $xml->domTree( $testDoc );

// print( "<b>XML document to parse:</b><br>" );

// print( nl2br( htmlspecialchars( $testDoc ) ) );

// print( "<br><b>Results from parsing:</b><br>" );
// $paragraphs =& $dom->elementsByName( "paragraph" );

// foreach ( $paragraphs as $paragraph )
// {
//     // get the name of the item, should be paragraph
//     print( "<br/>New " . $paragraph->name() . ":<br/>" );

//     // print the value of the name attribute
//     print( "Name: " . $paragraph->attributeValue( "name" ) . "<br/>" );
//     print( "Content: " . $paragraph->textContent() . "<br/>" );
// }

?>
