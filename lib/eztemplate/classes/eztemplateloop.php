<?php
//
// Definition of eZTemplateLoop class
//
// Created on: <23-Feb-2005 17:46:42 vs>
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


// private class, should not be used outside of this file
class eZTemplateLoopSequence
{
    function eZTemplateLoopSequence( &$array )
    {
        $this->ArrayRef =& $array;
        $this->CurVal   =  current( $this->ArrayRef );
    }

    function val()
    {
        return $this->CurVal;
    }

    function next()
    {
        if( ( $this->CurVal =& next( $this->ArrayRef ) ) === false )
        {
            reset( $this->ArrayRef );
            $this->CurVal = current( $this->ArrayRef );
        }
    }

    var $ArrayRef;
    var $CurVal;
}

/*!
  \class eZTemplateLoop eztemplateloop.php
  \ingroup eZTemplateFunctions
  \brief Code common for the loop functions
*/
class eZTemplateLoop
{
    function eZTemplateLoop( $functionName, &$functionParameters, &$functionChildren, &$functionPlacement,
                             &$tpl, &$textElements, &$rootNamespace, &$currentNamespace )
    {
        $this->skipDelimiter         = false;
        $this->skipSequenceIncrement = true;
        $this->delimiter             = null;
        $this->initialized           = true;
        $this->sequenceVarName       = null;
        $this->sequence              = null;


        $this->functionName       =  $functionName;
        $this->functionParameters =& $functionParameters;
        $this->functionChildren   =& $functionChildren;

        $this->tpl                =& $tpl;
        $this->textElements       =& $textElements;
        $this->rootNamespace      =& $rootNamespace;
        $this->currentNamespace   =& $currentNamespace;
        $this->functionPlacement  =& $functionPlacement;

        $this->initialized = $this->processFunctionParameters();
    }

    /*!
    \return true on success, false otherwise.
    */
    function processFunctionParameters()
    {
        $params =& $this->functionParameters;

        if ( !isset( $params['sequence_array'] ) || !count( $params['sequence_array'] ) )
            return true;

        $this->parseParamVarName( $params, 'sequence_var', $seqVarName );

        if ( !$seqVarName )
        {
            $tpl->error( $this->functionName, "Empty sequence variable name." );
            return false;
        }

        $seqArray = $this->tpl->elementValue( $params['sequence_array'],
                                              $this->rootNamespace, $this->currentNamespace, $this->functionPlacement );

        $this->sequence        =& new eZTemplateLoopSequence( $seqArray );
        $this->sequenceVarName =  $seqVarName;

        return true;
    }

    /*!
    \return true if the object has been correctly initialized, false otherwise
    */
    function initialized()
    {
        return $this->initialized;
    }

    function setSequenceVar()
    {
        if ( $this->hasSequence() )
            $this->tpl->setVariable( $this->sequenceVarName,  $this->sequence->val() );
    }

    function resetIteration()
    {
        $this->skipDelimiter         = false;
        $this->skipSequenceIncrement = false;
    }


    /*!
     Sets sequence variable in the template (if sequence was specified)
     */
    function incrementSequence()
    {
        if ( $this->hasSequence() && !$this->skipSequenceIncrement )
            $this->sequence->next();
    }

    function hasSequence()
    {
        return !is_null( $this->sequence );
    }

    function cleanup()
    {
        if ( $this->hasSequence() )
            $this->tpl->unsetVariable( $this->sequenceVarName );
    }

    /*
    \return true if the caller loop should break, false otherwise
    */
    function processChildren()
    {
        foreach ( array_keys( $this->functionChildren ) as $childKey )
        {
            $child =& $this->functionChildren[$childKey];
            $this->skipDelimiter = false;

            if ( $child[0] == EZ_TEMPLATE_NODE_FUNCTION ) // check child type
            {
                $childFunctionName  =& $child[2];

                if ( $childFunctionName == 'break' )
                    return true;
                elseif ( $childFunctionName == 'continue' )
                {
                    $this->skipSequenceIncrement = true;
                    break;
                }
                elseif ( $childFunctionName == 'skip' )
                {
                    $this->skipSequenceIncrement = true;
                    $this->skipDelimiter = true;
                    break;
                }
                elseif ( $childFunctionName == 'delimiter' )
                {
                    if ( is_null( $this->delimiter ) )
                        $this->delimiter =& $child;
                    continue;
                }
            }

            $rslt =& $this->tpl->processNode( $child, $this->textElements, $this->rootNamespace, $this->currentNamespace );

            // break/continue/skip might be found in a child function's (usually {if}) children
            if ( is_array( $rslt ) )
            {
                if ( array_key_exists( 'breakFunctionFound', $rslt ) )
                    return true;
                elseif ( array_key_exists( 'continueFunctionFound', $rslt ) )
                {
                    $this->skipSequenceIncrement = true;
                    break;
                }
                elseif ( array_key_exists( 'skipFunctionFound', $rslt ) )
                {
                    $this->skipSequenceIncrement = true;
                    $this->skipDelimiter = true;
                    break;
                }
            }

        } // foreach

        return false;
    }

    /*!
    \return true if the caller loop should break, false otherwise
    */
    function processDelimiter( $loopCondition )
    {
        if ( !( !is_null( $this->delimiter ) && !$this->skipDelimiter ) )
            return false;

        // Check the 'while' condition again
        if ( !$loopCondition )
            return true;

        $delimiterChildren =& $this->delimiter[1];
        foreach ( array_keys( $delimiterChildren ) as $key )
            $this->tpl->processNode( $delimiterChildren[$key], $this->textElements, $this->rootNamespace, $this->currentNamespace );

        return false;
    }

    /*!
    Parses the given function parameter that is supposed to contain a variable name.
    Extracted variable name is stored to $dst.
    \return false if specified parameter is not found or it is wrong, otherwise true is returned.
    */
    function parseParamVarName( &$functionParameters, $paramName, &$dst )
    {
        $dst = null;

        if ( !isset( $functionParameters[$paramName] ) || !count( $functionParameters[$paramName] ) )
            return false;

        list( $varNsName, $varNsType, $varName ) = $functionParameters[$paramName][0][1];

        if ( $varNsType != EZ_TEMPLATE_NAMESPACE_SCOPE_LOCAL || $varNsName )
        {
            $tpl->error( '', 'Loop variables can be defined in root namespace only (e.g. $foo, but not $#foo or $:foo.)' );
            return false;
        }

        $dst = $varName;
        return true;
    }

    /*!
    Parses value the given function parameter and stores it to $dst.
    \return false if specified parameter is not found or it is wrong, otherwise true is returned.
    */
    function parseParamValue( &$functionParameters, $paramName, &$dst,
                              &$tpl, &$rootNamespace, &$currentNamespace, &$functionPlacement )
    {
        $dst = null;

        if ( !isset( $functionParameters[$paramName] ) || !count( $functionParameters[$paramName] ) )
            return false;

        $dst = $tpl->elementValue( $functionParameters[$paramName], $rootNamespace, $currentNamespace, $functionPlacement );
        return true;
    }

    var $functionName;
    var $functionParameters;
    var $functionChildren;
    var $functionPlacement;

    var $skipDelimiter;
    var $skipSequenceIncrement;
    var $delimiter;

    var $tpl;
    var $textElements;
    var $rootNamespace;
    var $currentNamespace;

    var $initialized;
    var $sequence;
    var $sequenceVarName;
}

?>
