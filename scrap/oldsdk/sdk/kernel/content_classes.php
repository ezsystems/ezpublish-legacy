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

$DocResult = array();
$DocResult["title"] = "Content classes";

?>

<h1>Content classes</h1>

<p>
This is an introduction on how to use the core classes of eZ publish
at a low level. With these classes you can define what elements an object
should be made up of. <b>Content Class</b> is the name of this definition.
</p>
<h2>Creating a Content Class</h2>
<p>
Before the system can be populated with actual content objects a <b>class</b>
is needed. A class consists primary of a name, an identifier and a list of attributes.
Classes like objects are versioned albeit in a minimalistic fashion. A class either
is defined which means there is only one working copy, or it is a temporary meaning that
it has just been created but is currently being worked on or it is modified which means that
someone is working on a temporary version and a defined version is already exists.
</p>
<p>
<b>Note: To make objects of a new class available to anonymous users you must edit the Anonymous role,
as this role has specified access limitation by class by default.</b>
</p>
<p>
Creating the class is done by calling the static <b>create()</b> function in <b>eZContentClass</b>,
it takes one parameter which is the ID of the user creating the class. Usually this is the current
user which is fetched with <b>eZUser::currentUser()</b>. Once the <b>create()</b> functions returns
we have an eZContentClass object which is a temporary version and has creator, modifier and dates
filled in. We then set the name and identifier ourselves.
</p>

<pre class="example">
include_once( "kernel/classes/ezcontentclass.php" );

// create new empty class
$user =& eZUser::currentUser();
$class = eZContentClass::create( $user->attribute( "id" ) );

// set the name and identifier
$class->setAttribute( "name", "new class1" );
$class->setAttribute( "identifier", "new_class1" );
</pre>

<p>
The class is now ready to be stored, we call <b>store()</b> to store a temporary version.
</p>

<pre class="example">
$class->store();
</pre>

<p>
To make the class more useful we must add some <i>class attributes</i>. Attributes are created
in the same was as classes but take two parameters, the ID of the class and the stringname of
the data type. For now we'll assume that a data type called <i>ezstring</i> exists.
As you see we also set the <i>name</i> and <i>identifier</i> of the attribute.
</p>

<pre class="example">
$classAttribute = eZContentClassAttribute::create( $class->attribute( "id" ),
                                                   "ezstring" );
$classAttribute->setAttribute( "name", "new attribute1" );
$classAttribute->setAttribute( "identifier", "new_attribute1" );
$classAttribute->store();
// Add to attribute list
$classAttributes = array();
$classAttributes[] =& $classAttribute;
</pre>

<p>
Now we're happy with our new class and want to make it appear as a defined class.
We do that by removing the temporary version, setting the version to 0 and storing it.
</p>

<pre class="example">
// Remove this class and all it's attributes (note the true parameter)
$class->remove( true );
// Change version to defined, again for all attributes as well
// this also fetches a list of temporary attributes
$class->setVersion( 0, true );
// Alternatively
$class->setVersion( 0, $classAttributes );

// Finally store the class and all it's attributes
$class->store( $classAttributes );
</pre>

<h2>Fetching the classes</h2>

<p>
Now that we've stored the class we can find it among the defined list. We do that by fetching
classes which has version equal to 0. We will then get an array with <b>eZContentClass</b> objects.
</p>

<pre class="example">
// fetch all defined classes
$classes =& eZContentClass::fetchList( 0 );
</pre>

<p>
Once we got the class list we can iterate over it and fetch the attributes and then print
out the id of each attribute.
</p>

<pre class="example">
foreach( $classes as $class )
{
  // We must fetch the attributes ourselves, they do not get fetched automatically
  $classAttributes =& $class->fetchAttributes();
  foreach( $classAttributes as $classAttribute )
  {
    print( $classAttribute->attribute( "id" ) );
  }
}
</pre>

<p>
Fetching a class directly can be done if the class <i>ID</i> is known. We then modify the class
name and store it. It's very important to update the modification date, that it's easy for
external clients to find out if an object has changed.
</p>

<pre class="example">
// Fetch class with ID 42 if it exists
$class =& eZContentClass::fetch( 42 );
if ( $class !== null )
{
  print( $class->attribute( "name" ) );
  $class->setAttribute( "name", "Some other name" );
  // Change modification date
  $class->setAttribute( "modified", eZDateTime::currentTimeStamp() );
  $class->store();
}
</pre>

<!-- These should be in the search doc
<h2>Useful links</h2>
<ul>
<li><a href="http://www.sims.berkeley.edu/~hearst/irbook/porter.html">Porter's algorithm</a></li>
<li><a href="http://www.tartarus.org/~martin/PorterStemmer/">Porter's algorithm #2</a></li>
<li><a href="http://snowball.sourceforge.net/">Snowball stemming project</a></li>
</ul>
-->
