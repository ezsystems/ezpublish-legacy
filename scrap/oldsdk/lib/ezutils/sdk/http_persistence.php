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

<h1>Object persistence</h1>

<p>
Getting objects to persist between page views is cumbersome since you have to input all
elements as HTTP form types and read them on the next view. By using eZHTTPPersistence
it's possible to fetch these data automatically. The class can also be used to split
selected items from unselected ones.
</p>

<h2>Filling in data from forms</h2>

<h3>The HTML form</h3>
<p>The HTML form is created by inputting the data elements as hidden inputs.</p>
<pre class="example">
&lt;form type="post"&gt;
&lt;input type="hidden" name="MyData_title[]" value="title1" /&gt;
&lt;input type="hidden" name="MyData_title[]" value="title2" /&gt;
&lt;input type="hidden" name="MyData_title[]" value="title3" /&gt;
&lt;/form&gt;
</pre>

<h3>The definition</h3>
<p>We then create a definition so that data is properly input.
<i>Fields</i> define the mapping from attributes to member variables and <i>keys</i>
contain an array of attributes which are defined as keys (keys are not overridden).
</p>

<pre class="example">
$my_def = array( "fields" =&gt; array( "id" => "ID",
                                    "title" => "Title" ),
                 "keys" =&gt; array( "id" ) );
</pre>

<h3>The code</h3>
<p>Finally we fetch the original objects from the DB and override the data with the form data</p>

<pre class="example">
// Fetch the objects from the DB
$objects =&ampt; fetch_objects();
// Fetch the HTTP data with "MyData" as the base
eZHTTPPersitence::fetch( "MyData", $my_def, $objects, eZHTTPTool::instance(), true );
</pre>

<h2>Splitting data</h2>

<h3>The HTML form</h3>
<p>The HTML form consists of a number of checkboxes with a specific name.</p>

<pre class="example">
&lt;form type="post"&gt;
&lt;input type="checkbox" name="MyData_id_checked[]" value="1" /&gt;
&lt;input type="checkbox" name="MyData_id_checked[]" value="5" /&gt;
&lt;input type="checkbox" name="MyData_id_checked[]" value="42" /&gt;
&lt;/form&gt;
</pre>

<h3>The code</h3>
<p>In the code we fetch all the existing objects and split them into two arrays,
the objects in the last array are then removed.</p>

<pre class="example">
// Fetch the objects from the DB
$objects =&ampt; fetch_objects();
// Fetch the HTTP data with "MyData" as the base
eZHTTPPersitence::splitSelected( "MyData", $objects, eZHTTPTool::instance(), "id",
                                 $keep_objs, $remove_objs );
foreach( $remove_objs as $obj )
{
    $obj->remove();
}
</pre>
