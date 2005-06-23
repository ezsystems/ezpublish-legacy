<?php
//
// Definition of eZTemplateDelimitFunction class
//
// Created on: <01-Mar-2002 13:49:07 amos>
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
  \class eZTemplateDelimitFunction eztemplatedelimitfunction.php
  \ingroup eZTemplateFunctions
  \brief Displays left and right delimiter in templates

  This class iss a template function for outputting the left and right delimiters.
  Since the left and right delimiters are always parsed by the template engine
  it's not possible to output these characters. By registering an instance of this
  class as template functions you can get these characters with {ldelim} and {rdelim}.

  The name of these functions can also be controlled by passing the names to the
  constructor.

  Example:
\code
$tpl->registerFunctions( new eZTemplateDelimitFunction() );
// or custom names
$tpl->registerFunctions( new eZTemplateDelimitFunction( "l", "r" ) );
// alternatively
$obj = new eZTemplateDelimitFunction();
$tpl->registerFunction( "ldelim", $obj );
$tpl->registerFunction( "rdelim", $obj );
\endcode
*/

class eZTemplateDelimitFunction
{
    /*!
     Initializes the object with a name for the left and right delimiter.
     Default is ldelim for left and rdelim for right.
    */
    function eZTemplateDelimitFunction()
    {
        $this->LName = 'ldelim';
        $this->RName = 'rdelim';
    }

    /*!
     Returns an array of the function names, required for eZTemplate::registerFunctions.
    */
    function functionList()
    {
        return array( $this->LName, $this->RName );
    }

    /*!
     Returns an array with hints for the template compiler.
    */
    function functionTemplateHints()
    {
        return array(
            $this->LName => array( 'parameters' => false, 'static' => false, 'tree-transformation' => true ),
            $this->RName => array( 'parameters' => false, 'static' => false, 'tree-transformation' => true )
        );
    }

    function templateNodeTransformation( $functionName, &$node,
                                         &$tpl, $parameters, $privateData )
    {
        $newNodes = array();

        if ( $functionName == $this->LName )
        {
            $newNodes = array ( eZTemplateNodeTool::createTextNode( $tpl->leftDelimiter() ) );
        }
        else
        {
            $newNodes = array ( eZTemplateNodeTool::createTextNode( $tpl->rightDelimiter() ) );
        }
        return $newNodes;
    }

    /*!
     Outputs the left or right delimiter if the function names match.
    */
    function process( &$tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $nspace, $current_nspace )
    {
        switch ( $functionName )
        {
            case $this->LName:
            {
                $textElements[] = $tpl->leftDelimiter();
            } break;
            case $this->RName:
            {
                $textElements[] = $tpl->rightDelimiter();
            } break;
        }
    }

    /*!
     Returns false, telling the template parser that this is a single tag.
    */
    function hasChildren()
    {
        return false;
    }

    /// The name of the left delimiter tag
    var $LName;
    /// The name of the right delimiter tag
    var $RName;
}

?>
