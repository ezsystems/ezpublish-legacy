<?php
//
// Created on: <09-Dec-2002 12:43:53 wy>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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
Datatypes are the most useful building bricks in eZ publish 3. To create any attribute in any class, you
need to choose a suitable datatype to represent your attribute. The concept of eZ publish datatype is
not exactly as data type as defined in every programming language although it does contain some basic
datatypes such as integer, float and textline which is actually string type. Datatypes in eZ publish are
defined as fundamental and common elements which could build complex publishing products in internet.
</p>

<p>
<u><font size=2' color="gray">Overview of available datatypes</font></u>
</p>

<p>
eZ publish 3 comes with following fundamental datatypes.
<ul>
<li>Text line</li>
<li>Interger</li>
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

<p>
<u><font size=2' color="gray">Creating new datatype</font></u>
</p>

<p>
When you are creating or editing a class object, you should decide which datatypes are going to be used by
your attributes. Although datatypes comes with eZ publish are powerful enough for creating complex content
classes, you probably still need to create your own datatypes for specific cases. Creating new datatypes required
having some basic knowledge of how eZ publish datatype works and which classes are necessary to be changed or added.
In the following tutorial, how to create a new datatype is showed step by step using datatype Email as an example.
The Email datatype is used to save email address and will implement some check rules to verity that input email
with correct format.
</p>

<h3>Step 1: Build the file structure for your new datatype.</h3>

<p>
To make the datatype Email, you should first create the folder ezemail under ./kernel/classes/datatypes/, then create
the file called ezemailtype.php under the new created folder ezemail. Note that the name of folder and php file is important
since it will be used to locate the file. The basic structure is that if you added a datatype x, it should be stored like
this: ./kernel/classes/datatypes/ezx/ezxtype.php.
</p>

<h3>Step 2: Write the datatype file inherited from file ./kernel/classes/ezdatatype.php.</h3>
<p>
All datatype files should inherit from file ./kernel/classes/ezdatatype.php and implemented some functions which the super
class has defined but not implemented. Let's workthrough the code of ezemailtype.php to see how this class was
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
     In the template, the textfiled name of email input is something like 'ContentObjectAttribute_data_text_idOfTheAttribute',
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
     Returns the meta data used for storing search indeces.
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
}
</pre>
<p>
As the code illustrates, it must include the php file ezdatatype.php and inherits from eZDataType. The class should implement functions
validateObjectAttributeHTTPInput, fixupObjectAttributeHTTPInput, fetchObjectAttributeHTTPInput, storeObjectAttribute. Function
validateObjectAttributeHTTPInput which validates the datatype input and function fetchObjectAttributeHTTPInput which gets the datatype
input and stores are the most important function which need to be rewritten for every new datatype while other functions may have the
same content. At the end of the file, the new datatype should be registered in the system by calling register method in class eZDataType.
In this example, the following line
<pre class="example">
eZDataType::register( EZ_DATATYPESTRING_EMAIL, "ezemailtype" );
</pre>
was added.
</p>
<h3>Step 3: Add associated template files for content class.</h3>

<p>
Not every datatypes need to have template file in class level since most datatypes could use standard UI
supplied with eZ publish. It is only necessary when the datatype has specific constraints needs to be defined
during class editing. Examples are setting default value for this datatype, adding customer action buttons, etc.
If so, the datatype file should implement function storeClassAttribute, validateClassAttributeHTTPInput,
fixupClassAttributeHTTPInput, fetchClassAttributeHTTPInput which defined in the super class ezdatatype.php. These
functions deals with how to fetch, validate input and then store them in database.
Note that template file should be located at ./design/standard/templates/class/datatype/edit/ezx.tpl.
Since datatype Email does not have specification in class level and therefore nothing need to be done.
</p>

<h3>Step 4: Add associated template files for content object.</h3>
<p>
For every datatype, two template files required to be added. One templeate file for content object editing and
one template file for content object viewing. In this example, we add one ezemail.tpl shows below under
./design/standard/templates/content/datatype/edit/. This is just a normal textfield with corresponding name we defined
in eZEmailtype.php. Users will use this textfield to edit or input email address.
<pre class="example">
&lt;input type="text" size="10" name="ContentObjectAttribute_data_text_{$attribute.id}" value="{$attribute.data_text}" /&gt;
</div>
</pre>
and another ezemail.tpl under ./design/standard/templates/content/datatype/view/, this template file will show the
result of the saved email address.
<pre class="example">
{$attribute.data_text}
</pre>
</p>

<h3>Step 5: Add the new datatype to the siti.ini file.</h3>

<p>
The last step is to add the new created datatype to siti.ini file such as it could be found in the datatype
dropdown list and ready to be used. Open the siti.ini file under ./settings/ with a text editor and find the line with
<pre class='example'>
[DataTypeSettings]
AvailableDataTypes=ezstring;ezinteger;eztext;ezdate;ezfloat;ezuser;ezimage;ezboolean;
ezoption;ezprice;ezxmltext;ezobjectrelation;ezenum;ezauthor;ezmedia;ezbinaryfile;ezurl;
ezemail;ezdatetime;eztime;ezisbn
</pre>
Add the name of the new datatype to somewhere in the list above and remember to separate them with ';'. In this example,
we should add ezemail. You can check the result by refresh your browser and then go to the class editing page.. If everything
goes fine, you should find Email on the dropdown list.
</p>

<p>
<u><font size=2' color="gray">Create more complex datatypes</font></u>
</p>
<p>
In this tutorial, a simple datatype Email which uses only one field in database to store its content was created. To create
more complex datatypes, extra tables may need to be added and extra classes may need to be written. Please refer to eZEnum
type for more detail about how complex datatypes were created.
</p>
