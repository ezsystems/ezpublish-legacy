<?php
//
// Definition of eZTemplateSequenceFunction class
//
// Created on: <05-Mar-2002 13:55:25 amos>
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

/*!
  \class eZTemplateSequenceFunction eztemplatesectionfunction.php
  \ingroup eZTemplateFunctions
  \brief Wrapped array looping in templates using function "sequence"

  This class allows for creating arrays which are looped independently
  of a section. This is useful if you want to create multiple sequences.

\code
// Example of template code
{* Init the sequence *}
{sequence name=seq loop=array(2,5,7)}

{* Use it *}
{$seq:item}

{* Iterate it *}
{sequence name=seq}

\endcode
*/

class eZTemplateSequenceFunction
{
    /*!
     Initializes the function with the function name $inc_name.
    */
    function eZTemplateSequenceFunction( $inc_name = "sequence" )
    {
        $this->SequenceName = $inc_name;
    }

    /*!
     Returns an array of the function names, required for eZTemplate::registerFunctions.
    */
    function &functionList()
    {
        return array( $this->SequenceName );
    }

    /*!
     Either initializes the sequence or iterates it.
    */
    function process( &$tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        $params = $functionParameters;
        $loop = null;
        if ( isset( $params["loop"] ) )
        {
            $loop = $tpl->elementValue( $params["loop"], $rootNamespace, $currentNamespace, $functionPlacement );
        }
        if ( $loop !== null and !is_array( $loop ) )
        {
            $tpl->error( $func_name, "Can only loop arrays" );
            return;
        }

        $name = "";
        if ( !isset( $params["name"] ) )
        {
            $tpl->missingVariable( $func_name, "name" );
            return;
        }
        $name = $tpl->elementValue( $params["name"], $rootNamespace, $currentNamespace, $functionPlacement );

        $seq_var =& $GLOBALS["eZTemplateSequence-$name"];
        if ( !is_array( $seq_var ) )
            $seq_var = array();
        if ( $loop !== null )
        {
            $seq_var["loop"] = $loop;
            $seq_var["iteration"] = 0;
            $seq_var["index"] = 0;
        }
        else
        {
            $index =& $seq_var["index"];
            $iteration =& $seq_var["iteration"];
            ++$iteration;
            ++$index;
            if ( $index >= count( $seq_var["loop"] ) )
                $index = 0 ;
        }
        $tpl->setVariable( "item", $seq_var["loop"][$seq_var["index"]], $name );
        $tpl->setVariable( "iteration", $seq_var["iteration"], $name );
    }

    /*!
     Returns false, telling the template parser that this is a single tag.
    */
    function hasChildren()
    {
        return false;
    }

    /// Name of sequence function
    var $SequenceName;
}

?>
