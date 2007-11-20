<?php
//
// Definition of eZTemplateDelimitFunction class
//
// Created on: <01-Mar-2002 13:49:07 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
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
                                         $tpl, $parameters, $privateData )
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
    function process( $tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $nspace, $current_nspace )
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
    public $LName;
    /// The name of the right delimiter tag
    public $RName;
}

?>
