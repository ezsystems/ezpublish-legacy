<?php
//
// Created on: <29-May-2002 15:40:36 bf>
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

<h1>Parsing with namespaces</h1>

<p>
Namespaces are used to make it possible to identify nodes in different contexts.
If you study the XML document below you will find that there are two <b>name</b>
nodes, in different contexts. The first node refers to the name of the book, the
second refers to the name of the author. They are separated by the use of namespaces.
</p>

<pre class="example">
&lt;?xml version='1.0'?&gt;
&lt;ez:doc xmlns:ez='http://ez.no/'&gt;
  &lt;ez:book xmlns:book='http://ez.no/book/'&gt;
    &lt;book:name title='the eZ publish book' /&gt;
    &lt;book:author xmlns:author='http://ez.no/book/author/'&gt;
      &lt;author:name firstName='Bilbo' lastName='Baggins' /&gt;
    &lt;/book:author&gt;
  &lt;/ez:book&gt;
&lt;/ez:doc&gt;
</pre>

<h2>Fetching nodes from namespaces</h2>
<p>
The code snippet below shows how you can extract two DOM nodes called <b>name</b>.
The nodes are identified by their namespace and name. You use the <b>elementsByNameNS()</b>
function which takes the node name and namespace as parameters.
</p>

<pre class="example">
// fetch the name node from the http://ez.no/book/ namespace
$names =& $dom->elementsByNameNS( "name", "http://ez.no/book/" );
$name =& $names[0];
// print the value of the title attribute from the first name node found
print( $name->attributeValue( "title" ) );

// fetch the name node from the http://ez.no/book/author/ namespace
$names =& $dom->elementsByNameNS( "name", "http://ez.no/book/author/" );
$name =& $names[0];
// print the first name and last name from the first author name node found
print( $name->attributeValue( "firstName" ) . "  " . $name->attributeValue( "lastName" ) );
</pre>

<p>
This is the result of the above code:
</p>

<pre class="example">
the eZ publish book
Bilbo Baggins
</pre>

<?php

// include_once( "lib/ezxml/classes/ezxml.php" );

// print( "<h3>eZ xml&trade; parsing with namespace :</h3>" );

// $xml = new eZXML();

// $testDoc = "< ?xml version='1.0'? >
// <ez:doc xmlns:ez='http://ez.no/'>
// <ez:book xmlns:book='http://ez.no/book/'>
// <book:name title='the eZ publish book' />
// <book:author xmlns:author='http://ez.no/book/author/'>
// <author:name firstName='Bilbo' lastName='Baggins' />
// </book:author>
// </ez:book>
// </ez:doc>";


// print( "<b>Parsing:</b><br>" );

// print( nl2br( htmlspecialchars( $testDoc ) ) );

// $dom =& $xml->domTree( $testDoc );

// print( "<br><b>Fetching book:name node:</b><br>" );
// $names =& $dom->elementsByNameNS( "name", "http://ez.no/book/" );
// $name =& $names[0];
// print( $name->attributeValue( "title" ) . "<br>" );

// print( "<br><b>Fetching author:name node:</b><br>" );
// unset( $names );
// unset( $name );
// $names =& $dom->elementsByNameNS( "name", "http://ez.no/book/author/" );
// $name =& $names[0];

// print( $name->attributeValue( "firstName" ) . "  " . $name->attributeValue( "lastName" ) . "<br>" );

?>
