<?php
//
// Definition of Template_operators class
//
// Created on: <31-Jul-2002 16:40:44 amos>
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

$DocResult = array();
$DocResult['title'] = 'Template Operators';

?>

<h1>Template operators</h1>

<p>Overview of available template operators in the kernel</p>

<h2>URL operators</h2>

<p>The URL operators takes a URL and modifies it in some way, they allow for easy change
of sitedesigns. The syntax of all the operators are similar with only their name varying.
The nVH setup is prepended to the URL if one is used to allow seamless VH/nVH setups.
They take one parameter, which defaults to <i>double</i>, which specifies the quotes which are wrapped
around the url.</p>
<p>The quote values are:</p>
<ul>
  <li>double - Uses double (<i>"</i>) quotes</li>
  <li>single - Uses single (<i>'</i>) quotes</li>
  <li>no - Uses no quotes</li>
</ul>

<p>These operators will also be available as template functions which means that they can wrap
around multiple template elements, either that or a <i>concat</i> operator will be created.</p>
<pre class="example">
Using it as a function.

{ezdesign}stylesheets/{$style_name}.css{/ezdesign}

Using it with concat

{concat("stylesheets/",$style_name,".css")|ezdesign}
</pre>

<h3>ezdesign</h3>

<p>
Prepends the current sitedesign to the url, if the file does not exist in that design the
standard sitedesign is used instead.
</p>
<pre class="example">
{"stylesheets/style.css"|ezdesign}

becomes

"/design/mydesign/stylesheets/style.css"
</pre>

<h3>ezimage</h3>
<p>
Prepends the current sitedesign to the url with the <i>image</i> subdirectory,
if the file does not exist in that design the standard sitedesign is used instead.
</p>
<pre class="example">
{"search.png"|ezimage(single)}

becomes

'/design/mydesign/images/search.png'
</pre>

<h3>ezurl</h3>
<p>
Only prepends the nVH directory if one exists.
</p>

<h3>exturl</h3>
<p>
Not done yet, the exact nature of this operator is not clear.
</p>
