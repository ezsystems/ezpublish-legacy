<?php
//
// Created on: <16-May-2002 14:17:46 bf>
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
<h1>URI management</h1>

<p>
To provide a unified access to the request URI the class eZURI can be used. It makes
sure that single elements can be accessed and iterated over in an easy way.
All work on elements is either relative (default) or absolute. Relative access
allows one to see the URI as multiple operations, for instance the URI /print/object/1
sees that the print mode should be enabled, increases the index and passes the uri object
to the underlying object system, no need for the object system to detect print mode by
itself.
</p>

<h2>Accessing elements</h2>
<p>The class allows us to concentrate on each element one at a time. We then increase
the index to work on the next value. Base and tail elements are also available.
</p>

<pre class="example">
// Create the object
$uri =&amp; eZURI::instance( "/test/of/uri" );

// Print the first element
print( $uri->element() ); // Prints "test"
// and the next
$uri->increase();
print( $uri->element() ); // Prints "of"

// Print base and elements
$uri->increase();
print( $uri->base() ); // Prints "/test/of"
print( $uri->elements() ); // Prints "uri"
</pre>

<p>
The common way to use this class is to pass it the $REQUEST_URI.
</p>

<pre class="example">
// Remove uri parameters
ereg( "([^?]+)", $REQUEST_URI, $regs );
$REQUEST_URI = $regs[1];
// Create the object
$uri =&amp; eZURI::instance( $REQUEST_URI );
</pre>
