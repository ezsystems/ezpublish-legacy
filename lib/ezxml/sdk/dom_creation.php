<?php

print( "<h1>DOM creation</h1>
<p>A simple dom creation example:</p>

<pre class='example'>
include_once( 'lib/ezxml/classes/ezxml.php' );

\$doc = new eZDOMDocument();
\$doc->setName( 'SOAPTest' );

\$root =& \$doc->createElementNode( 'document' );
\$doc->setRoot( \$root );

\$book1 =& \$doc->createElementNode( 'book' );
\$book1->appendAttribute( \$doc->createAttributeNode( 'name', 'eZ publish bible' ) );
\$root->appendChild( \$book1 );

\$book2 =& \$doc->createElementNode( 'book' );
\$book2->appendAttribute( \$doc->createAttributeNode( 'name', 'eZ publish development' ) );
\$root->appendChild( \$book2 );

\$xml =& \$doc->toString();

print( '&lt;pre&gt;'. nl2br( htmlspecialchars( \$xml ) ) . '&lt;/pre&gt;' );
</pre>

<p>This will print something like this:</p>

<pre class='example'>
&lt;?xml version='1.0' charset='UTF-8'?&gt;

&lt;document&gt;

  &lt;book name='eZ publish bible' /&gt;

  &lt;book name='eZ publish development' /&gt;

&lt;/document&gt;
</pre>
" );

?>
