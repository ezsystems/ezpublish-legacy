<?php
//
// Created on: <06-Jun-2002 13:25:12 bf>
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

<h1>Content objects</h1>

<p>
This is an introduction on how to use the core classes of eZ publish
at a low level. With these classes you can create content object and
read their contents ammong other things.
</p>
<h2>Fetching a Content Object</h2>
<p>
Before you can fetch a content object you need to know which id this
content object has. Every content object in eZ publish has it's own
unique id. This id is used to identify the different objects.
</p>
<p>
When you know which id your content object has you can fetch the object
directly with the <b>eZContentObject::fetch()</b> function. The example
below shows how you can fetch a content object, the variable <b>$contentObject</b>
is a eZContentObject object.
</p>

<pre class="example">
include_once( "kernel/classes/ezcontentobject.php" );

// fetch object with id 42
$contentObject = eZContentObject::fetch( 42 );
</pre>


<h2>Reading the data</h2>

<h3>Object versions</h3>
<p>
Every content object consits of one or more versions. Normally you want
to fetch the content from the current version. This is done with the
function <b>$contentObject->currentVersion()</b>. In the code snippet
below the variable <b>$currentVersion</b> stores a reference to the
current document version of <b>$contentObject</b>. Versions of content
objects are returned as instances of eZContentObjectVersion.
</p>

<pre class="example">
// Fetch the current version of the document
$currentVersion =& $contentObject->currentVersion();
</pre>

<h3>Object translations</h3>
<p>
When you have the version object you can get the data. The data is returned
with the <b>$currentVersion->attributes()</b> function. This function needs
a parameter where you specify the language code, e.g. en_GB for the english
translation of the object.
</p>

<pre class="example">
// fetch the english translation of the current version
$attributes =& $currentVersion->attributes( "en_GB" );
</pre>

<p>
To get the available translations you use the function
<b>$currentVersion->translations()</b>. This returns an array
of eZContentObjectTranslation objects.
</p>

<h3>Reading the attributes</h3>
<p>
You can get the attributes from both eZContentObjectVersion and the
eZContentObjectTranslation objects.
</p>

<p>
The $attributes variable is an array of eZContentObjectAttribute objects.
Each attribute has each own contents. For example an article can have the
following attributes:
</p>
<ul>
	<li>Title - string</li>
	<li>Intro - text</li>
	<li>Body - text</li>
</ul>

<p>
The code snippet below loops all the attributes and prints the attribute
name, datatype and contents. <b>NOTE:</b> Here we print the contents of
the attribute directly. This can be a complex type, this is just a simplified
example.
</p>
<pre class="example">
// Loop each attribute
foreach ( $attributes as $attribute )
{
    print( "Attribute name:" . $attribute->contentClassAttributeName() );
	// fetch the content attribute class object, to get the datatype
    $contentClassAttribute =& $attribute->contentClassAttribute();
    print( "Datatype: " . $contentClassAttribute->attribute( "data_type_string" ) );

	// print the content of the attribute
    print( "Content : " . $attribute->content() );
}
</pre>

<h2>Storing changes in an object</h2>
<p>
Since all content which is stored in objects are located in the attribute
you need to fetch the attributes for the version and translation you want to
store changes in. When you have the attributes you can store the new content
on the attributes with the function $attribute->store().
</p>

<pre class="example">

$attribute->setContent( "355" );

if ( $attribute->isValid() )
{
    $attribute->store();
}
</pre>

<h2>Creating</h2>



<?php
/*
include_once( "kernel/classes/ezcontentobject.php" );

// check current user
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

$user =& eZUser::currentUser();

// user creation test

$user =& eZUser::create( 100 );
$user->setAttribute( "login", "bf" );
$user->setAttribute( "email", "bf@ez.no" );
$user->setAttribute( "password", "ikkeno" );



print( "Current user id: " . $user->attribute( "id" ) . "<br>" );
eZDebug::writeNotice( $user, "User account"  );

//$contentObject =& eZContentObject::fetch( 1 );

print( "content object with id: " . $contentObject->attribute("id") . "<br>" );
print( "current version is: " . $contentObject->attribute("current_version") . "<br>"  );

$currentVersion =& $contentObject->currentVersion();

$translations =& $currentVersion->translations();

print( "available translations: <br/>" );
foreach ( $translations as $translation )
{
    print( $translation->languageCode() . "<br>" );
}


print( "version id: " . $currentVersion->attribute("id") . "<br/>" );

$attributes =& $currentVersion->attributes();

print( "<h2>Object contents:</h2>" );
foreach ( $attributes as $attribute )
{
    print( "Attribute id: " . $attribute->attribute( "id" ) . ", Class name:" . $attribute->contentClassAttributeName( ) . "<br/>" );
    $contentClassAttribute =& $attribute->contentClassAttribute();
    print( "Datatype: " . $contentClassAttribute->attribute( "data_type_string" )  . "<br/>" );

    $attribute->setContent( "355" );

    print( "Content : " . $attribute->content() . "<br>" );
    $attribute->store();
}

$versions = $contentObject->versions();

*/

?>
