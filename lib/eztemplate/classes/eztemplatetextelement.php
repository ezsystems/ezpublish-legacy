<?php
//
// Definition of eZTemplateTextElement class
//
// Created on: <01-Mar-2002 13:50:45 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZTemplateTextElement eztemplatetextelement.php
  \ingroup eZTemplateElements
  \brief Represents a text element in the template tree.

 This class containst the text of a text element.
*/

class eZTemplateTextElement
{
    /*!
     Initializes the object with the text.
    */
    function eZTemplateTextElement( $text )
    {
        $this->Text = $text;
    }

    /*!
     Returns #text.
    */
    function name()
    {
        return "#text";
    }

    function serializeData()
    {
        return array( 'class_name' => 'eZTemplateTextElement',
                      'parameters' => array( 'text' ),
                      'variables' => array( 'text' => 'Text' ) );
    }

    /*!
     Appends the element text to $text.
    */
    function process( &$tpl, &$text )
    {
        $text .= $this->Text;
    }

    /*!
     Returns a reference to the element text.
    */
    function &text()
    {
        return $this->Text;
    }

    /// The element text
    var $Text;
}

?>
