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
$DocResult['title'] = 'Content Datatypes';

include_once( "lib/ezutils/classes/ezsys.php" );

$wwwDir = eZSys::wwwDir();

?>

<h1>Content datatypes</h1>

<p>
This document will give an introduction and overview of content datatypes. All examples
uses the datatype <b>ezstring</b> as reference.
</p>

<p>
The datatype has three responsibilites, two which are required and one which is optional.
They are class attribute definition, object attribute editing and object attribute viewing.
</p>

<h2>Class attribute definition</h2>
<p>
This is optional and may implement an UI for setting default data or defining
the validation for an object attribute. The class must then implement the functions
<i>storeClassAttribute</i>, <i>validateClassAttributeHTTPInput</i>, <i>fixupClassAttributeHTTPInput</i>,
<i>fetchClassAttributeHTTPInput</i>, which deals with input validation from HTTP and storing
of data in the database.
</p>
<p>Uses the template <b>design/standard/templates/class/datatype/edit/ezstring.tpl</b> for UI.</p>

<h2>Object attribute editing</h2>
<p>
Defines the validation of object attribute input using HTTP as well as storing of data in
the database. The class must then implement the functions
<i>validateObjectAttributeHTTPInput</i>, <i>fixupObjectAttributeHTTPInput</i>,
<i>fetchObjectAttributeHTTPInput</i>, <i>initializeObjectAttribute</i>.
</p>
<p>Uses the template <b>design/standard/templates/content/datatype/edit/ezstring.tpl</b> for UI.</p>

<h2>Object attribute viewing</h2>
<p>
The viewing does not require any functions in the class but only requires the template which
defines the look of the attribute.
</p>
<p>Uses the template <b>design/standard/templates/content/datatype/view/ezstring.tpl</b> for UI.</p>

<h1>Required PHP file</h1>
<p>
Each datatype consists of at least one php file which defines it's behaviour. It can also
have several other files to handle storage but that's up to each datatype.
</p>
<b>kernel/classes/datatypes/ezstring/ezstringtype.php</b> <a href="<?print( $wwwDir )?>/sdk/ref/view/class/eZStringType">[ Class ]</a>
<p>
The main datatype handler, contains the code for reading various input,
validating and storing.
</p>

<h1>Required templates</h1>

<b>design/standard/templates/content/datatype/view/ezstring.tpl</b>
<p>
The template used for viewing an attribute in a content object. The template variable <i>$attribute</i>
is passed to the template which will contain an <i>eZContentObjectAttribute</i> object.
</p>

<b>design/standard/templates/content/datatype/edit/ezstring.tpl</b>
<p>
The template used for editing an attribute in a content object. The template variable <i>$attribute</i>
is passed to the template which will contain an <i>eZContentObjectAttribute</i> object.
</p>

<b>design/standard/templates/class/datatype/edit/ezstring.tpl</b>
<p>
The template used for editing an attribute in a content class. Here default parameters and
other constraints may be setup for content objects. The template variable <i>$class_attribute</i>
is passed to the template which will contain an <i>eZContentClassAttribute</i> object.
</p>

<h1>Other related files</h1>
<p>
The following files are related to a given datatype, this includes classes, templates and other files.
</p>

<b>kernel/classes/ezdatatype.php</b> <a href="<?print( $wwwDir )?>/sdk/ref/view/class/eZDataType">[ Class ]</a>
<p>
The base class for all datatypes which they must inherit from and implement a couple of functions
for it to work properly. See the API reference documentation for more information.
</p>

<b>kernel/classes/ezcontentclass.php</b> <a href="<?print( $wwwDir )?>/sdk/ref/view/class/eZContentClass">[ Class ]</a>
<p>
The main class which is used for defining classes in the system.
</p>

<b>kernel/classes/ezcontentclassattribute.php</b> <a href="<?print( $wwwDir )?>/sdk/ref/view/class/eZContentClassAttribute">[ Class ]</a>
<p>
The class which defines an attribute which is connected to an eZContentClass.
</p>

<b>kernel/classes/ezcontentobject.php</b> <a href="<?print( $wwwDir )?>/sdk/ref/view/class/eZContentObject">[ Class ]</a>
<p>
The class which defines the instantion of an eZContentClass.
</p>

<b>kernel/classes/ezcontentobjectattribute.php</b> <a href="<?print( $wwwDir )?>/sdk/ref/view/class/eZContentObjectAttribute">[ Class ]</a>
<p>
The class which defines an attribute which is connected to an eZContentObject.
</p>

<b>kernel/classes/ezpersistentobject.php</b> <a href="<?print( $wwwDir )?>/sdk/ref/view/class/eZPersistentObject">[ Class ]</a>
<p>
The base class which all database related classes inherit from. It defines functions for
fetching, storing and removing data in a database using a <i>definition</i> structure.
</p>

<b>lib/ezutils/classes/ezhttptool.php</b> <a href="<?print( $wwwDir )?>/sdk/ref/view/class/eZHTTPTool">[ Class ]</a>
<p>
The utility class used for fetching HTTP post variables.
</p>

<b>lib/ezutils/classes/ezinputvalidator.php</b> <a href="<?print( $wwwDir )?>/sdk/ref/view/class/eZInputValidator">[ Class ]</a>
<p>
The main class which handles text input validation and correction. The datatype must return
a validation state which is defined in that class for the input validation to work properly.
</p>

<b>design/standard/templates/class/edit.tpl</b>
<p>
The template which specifies the whole content class editing view. It should not be necessary to
to change this to achieve a given datatype.
</p>

<b>design/standard/templates/content/edit.tpl</b>
<p>
The template which specifies the whole content object editing view. It should not be necessary to
to change this to achieve a given datatype.
</p>

<b>design/standard/templates/content/view/full.tpl</b>
<p>
One of the viewmodes for a content class.
</p>

<b>settings/site.ini</b>
<p>
The key <b>AvailableDataTypes</b> in group <b>DataTypeSettings</b> defines which datatypes
are available in the UI, add the datatype string id of your datatype if you want it to appear
amongst the other datatypes.
</p>
