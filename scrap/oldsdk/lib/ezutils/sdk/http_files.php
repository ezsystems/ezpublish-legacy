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

<h1>Fetching files</h1>

<p>
Files that are posted by clients are normally accessed by using the global _FILE variable.
Each posted file has a name which is defined in the HTML form, you access the file
with that name, and get a eZHTTPFile object if the file exists.
</p>

<h2>The HTML form</h2>
<pre class="example">
&lt;form type="post"&gt;
&lt;input type="file" name="MyFile" /&gt;
&lt;/form&gt;
</pre>

<h2>The code</h2>
<pre class="example">
if ( eZHTTPFile::canFetch( "MyFile" ) )
    $file =& eZHTTPFile::fetch( "MyFile" );
</pre>

<h2>Storing the file</h2>
<p>If we want to keep the file we must store it, or else the file will be removed when the script ends.
We store it by calling the store() function on the object.
</p>

<pre class="example">
// Store file in storage
$file->store();
// or in a subdir
$file->store( "myfiles" );
</pre>
