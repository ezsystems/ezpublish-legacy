<?php

print( "<h1>DOM creation with namespaces</h1>

<pre class='example'>
include_once( 'lib/ezxml/classes/ezxml.php' );

\$doc = new eZDOMDocument();
\$doc-&gt;setName( 'SOAPTest' );

// set the default namespace
\$root =& \$doc-&gt;createElementNodeNS( 'http://ez.no/', 'document' );

// connect the http://ez.no/ namespace to the ez prefix
\$root-&gt;setPrefix( 'ez' );
\$doc-&gt;setRoot( \$root );

\$ezBooks =& \$doc-&gt;createElementNode( 'bookshelf' );
\$ezBooks-&gt;setPrefix( 'ez' );

\$root-&gt;appendChild( \$ezBooks );

// define a new namespace http://ez.no/books/ with the prefix ezbooks
\$ezBooks-&gt;appendAttribute( \$doc-&gt;createAttributeNamespaceDefNode( 'ezbooks', 'http://ez.no/books/' ) );

// define a new namespace http://books.com/ with the prefix otherbooks
\$ezBooks-&gt;appendAttribute( \$doc-&gt;createAttributeNamespaceDefNode( 'otherbooks', 'http://books.com/' ) );

\$book1 =& \$doc-&gt;createElementNode( 'book' );

// this book belongs to the http://ez.no/book/ namespace
\$book1-&gt;setPrefix( 'ezbook' );

\$book1-&gt;appendAttribute( \$doc-&gt;createAttributeNode( 'name', 'eZ publish bible' ) );
\$ezBooks-&gt;appendChild( \$book1 );

\$book2 =& \$doc-&gt;createElementNode( 'book' );
\$book2-&gt;setPrefix( 'otherbooks' );
\$book2-&gt;appendAttribute( \$doc-&gt;createAttributeNode( 'name', 'Definitive XML Schemas' ) );
\$ezBooks-&gt;appendChild( \$book2 );

\$xml =& \$doc-&gt;toString();

print( '&lt;pre&gt;'. nl2br( htmlspecialchars( \$xml ) ) . '&lt;/pre&gt;' );
</pre>

<p>This will print something like this:</p>

<pre class='example'>
&lt;?xml version='1.0' charset='UTF-8'?&gt;

&lt;ez:document xmlns:ez='http://ez.no/'&gt;

  &lt;ez:bookshelf xmlns:ezbooks='http://ez.no/books/'
                xmlns:otherbooks='http://books.com/'&gt;

    &lt;ezbook:book name='eZ publish bible' /&gt;

    &lt;otherbooks:book name='Definitive XML Schemas' /&gt;

  &lt;/ez:bookshelf&gt;

&lt;/ez:document&gt;
</pre>
");

?>
