<?php
//
// Created on: <09-Dec-2002 12:43:53 wy>
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

<h1>Datatypes in eZ publish 3</h1>

<p>
Datatypes are the most useful building blocks in eZ publish 3. To create any attribute in any class, you
need to choose a suitable datatype to represent your attribute. The concept of eZ publish datatypes is
not exactly the same as datatypes in common programming languages although eZ publish has some basic
datatypes such as integer, float and textline which is actually a string type. Datatypes in eZ publish are
defined as fundamental and common elements which can build complex products on the Internet.
</p>

<h2>Overview of available datatypes</h2>

<p>
eZ publish 3 comes with the following fundamental datatypes.
<ul>
<li>Text line</li>
<li>Integer</li>
<li>Float</li>
<li>Text field</li>
<li>XML Text field</li>
<li>User account</li>
<li>Image</li>
<li>Media</li>
<li>Check box</li>
<li>Enum</li>
<li>Option</li>
<li>Email</li>
<li>Author</li>
<li>Price</li>
<li>Date field</li>
<li>Time</li>
<li>Datetime</li>
<li>Object relation</li>
<li>ISBN</li>
<li>URL</li>
<li>BinaryFile</li>
</ul>
</p>

<h2>Creating a new datatype</h2>

<p>
When you are creating or editing a class object, you should decide which datatypes are going to be used by
your attributes. Although the datatypes that come with eZ publish are powerful enough for creating complex content
classes, you might still need to create your own datatypes for specific cases. Creating new datatypes requires
having some basic knowledge of how eZ publish datatypes work and which classes are necessary to be changed or added.
In the following tutorial, how to create a new datatype is shown step by step using the datatype Email as an example.
The Email datatype is used to save email address and implements some check rules to verify that input email
is of correct format.
</p>

<h3>Step 1: Build the file structure for your new datatype.</h3>

<p>
To make the datatype Email, you should first create the folder ezemail under ./kernel/classes/datatypes/, then create
the file called ezemailtype.php under the newly created folder. Note that the name of the folder and PHP file is important
since it will be used to locate the file. The basic structure is that if you added a datatype x, it should be stored like
this: ./kernel/classes/datatypes/ezx/ezxtype.php.
</p>

<h3>Step 2: Write the datatype file inherited from file ./kernel/classes/ezdatatype.php.</h3>
<p>
All datatype files should inherit from the file ./kernel/classes/ezdatatype.php and implement some functions which the
super class has defined but not implemented. Let's work through the code of ezemailtype.php to see how this class was
implemented.
</p>

<pre class="example">
&lt;?php

// Include the super class file
include_once( "kernel/classes/ezdatatype.php" );

// Include the file which will be used to validate email
include_once( "lib/ezutils/classes/ezmail.php" );

// Define the name of datatype string
define( "EZ_DATATYPESTRING_EMAIL", "ezemail" );

class eZEmailType extends eZDataType
{
    /*!
     Construction of the class, note that the second parameter in eZDataType is the actual name
     showed in the datatype dropdown list.
    */
    function eZEmailType( )
    {
        $this->eZDataType( EZ_DATATYPESTRING_EMAIL, "Email" );
    }

    /*!
     Validates the input and returns true if the input was valid for this datatype. Here you could
     add special rules for validating email.
     Parameters $http holds the class object eZHttpTool which has functions to fetch and check http input.
     Parameters $base holds the base name of http variable, in this case the base name will be 'ContentObjectAttribute'.
     Parameters $contentObjectAttribute holds the attribue object.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $email =& $http->postVariable( $base . '_data_text_' . $contentObjectAttribute->attribute( 'id' ) );
            $classAttribute =& $contentObjectAttribute->contentClassAttribute();
            if( $classAttribute->attribute( "is_required" ) == true )
            {
                if( $email == "" )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'content/datatypes',
                                                                         'A valid email account is required.',
                                                                         'eZEmailType' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
            }
            if( $email != "" )
            {
                $isValidate =  eZMail::validate( $email );
                if ( ! $isValidate )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'content/datatypes',
                                                                         'Email address is not valid.',
                                                                         'eZEmailType' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
            }
        }
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
     An email could be easily stored as variable characters, we use data_text filed in database to save it.
     In the template, the textfile name of email input is something like 'ContentObjectAttribute_data_text_idOfTheAttribute',
     therefore we fetch the http variable '$base . "_data_text_" . $contentObjectAttribute->attribute( "id" )'.
     Again, parameters $base holds the base name of http variable and is 'ContentObjectAttribute' in this example.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data =& $http->postVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) );
            $contentObjectAttribute->setAttribute( "data_text", $data );
        }
        return false;
    }

    /*!
     Store the content. Since the content has been stored in function fetchObjectAttributeHTTPInput(),
     this function is with empty code.
    */
    function storeObjectAttribute( &$contentObjectattribute )
    {
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }

    /*!
     Returns the meta data used for storing search indices.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }

    /*!
     Returns the text.
    */
    function title( &$contentObjectAttribute )
    {
        return  $contentObjectAttribute->attribute( "data_text" );
    }
}
eZDataType::register( EZ_DATATYPESTRING_EMAIL, "ezemailtype" );
?&gt;
</pre>

<p>
As the code illustrates, it must include the PHP file ezdatatype.php and inherit from eZDataType. The class should
implement the functions validateObjectAttributeHTTPInput, fixupObjectAttributeHTTPInput, fetchObjectAttributeHTTPInput,
and storeObjectAttribute. The function validateObjectAttributeHTTPInput, which validates the datatype input, and the
function fetchObjectAttributeHTTPInput, which gets the datatype input and stores it, are the most important functions
that need to be rewritten for every new datatype. Other functions may have the same content. At the end of the file,
the new datatype should be registered in the system by calling the register method in the class eZDataType.
</p>

<p>
In this example, the following line
<pre class="example">
eZDataType::register( EZ_DATATYPESTRING_EMAIL, "ezemailtype" );
</pre>
was added.
</p>
<h3>Step 3: Add associated template files for the content class.</h3>

<p>
Not every datatype needs to have a template file in class level since most datatypes can use standard UI
supplied with eZ publish. It is only necessary when the datatype has specific constraints that need to be defined
during class editing. Examples are setting a default value for the datatype, adding customer action buttons, etc.
If so, the datatype file should implement the functions storeClassAttribute, validateClassAttributeHTTPInput,
fixupClassAttributeHTTPInput, and fetchClassAttributeHTTPInput which are defined in the super class ezdatatype.php.
These functions deal with how to fetch and validate input and then store it in the database.
Note that the template file should be located at ./design/standard/templates/class/datatype/edit/ezx.tpl.
The datatype Email does not have a specification in class level and therefore nothing needs to be done in this case.
</p>

<h3>Step 4: Add associated template files for content object.</h3>
<p>
For every datatype, two template files must to be added. One template file for content object editing and
one for content object viewing. In this example, we add one ezemail.tpl (shown below) under
./design/standard/templates/content/datatype/edit/. This is just a normal text field with corresponding name we defined
in eZEmailtype.php. Users will use this text field to edit or input email address.
</p>

<p>
<pre class="example">
&lt;input type="text" size="10" name="ContentObjectAttribute_data_text_{$attribute.id}" value="{$attribute.data_text}" /&gt;
</pre>
</p>

<p>
The second ezemail.tpl is added under ./design/standard/templates/content/datatype/view/, this template file will show the
result of the saved email address.
</p>

<p>
<pre class="example">
{$attribute.data_text}
</pre>
</p>

<h3>Step 5: Add the new datatype to the site.ini file.</h3>

<p>
The last step is to add the newly created datatype to the content.ini file so it will be displayed in the datatype
dropdown list. Open the content.ini file under ./settings/ with a text editor and find the line with
</p>

<p>
<pre class='example'>
[DataTypeSettings]
# A list of directories to check for datatypes
RepositoryDirectories[]=kernel/classes/datatypes
ExtensionDirectories[]

AvailableDataTypes[]=ezstring
AvailableDataTypes[]=eztext
AvailableDataTypes[]=ezxmltext
AvailableDataTypes[]=ezdate
AvailableDataTypes[]=ezdatetime
AvailableDataTypes[]=eztime
AvailableDataTypes[]=ezboolean
AvailableDataTypes[]=ezinteger
AvailableDataTypes[]=ezfloat
AvailableDataTypes[]=ezenum
AvailableDataTypes[]=ezobjectrelation
AvailableDataTypes[]=ezimage
AvailableDataTypes[]=ezbinaryfile
AvailableDataTypes[]=ezmedia
AvailableDataTypes[]=ezauthor
AvailableDataTypes[]=ezurl
AvailableDataTypes[]=ezemail
AvailableDataTypes[]=ezoption
AvailableDataTypes[]=ezprice
AvailableDataTypes[]=ezuser
AvailableDataTypes[]=ezisbn
</pre>
</p>

<p>
Add a new line with the name of the new datatype to somewhere under [DataTypeSettings] . In this example,
we should add line "AvailableDataTypes[]=ezemail" under [DataTypeSettings]. You can check the result by refreshing
your browser and then go to the class editing page. If everything goes fine, you should find Email on the dropdown list.
</p>

<h2>Create more complex datatypes</h2>
<p>
In this tutorial, a simple datatype Email, which uses only one field in the database to store its content, was created.
To create more complex datatypes, extra tables may need to be added and extra classes may need to be written. Please refer
to the eZEnum type for more detail about how complex datatypes can be created.
</p>
