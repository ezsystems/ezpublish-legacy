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
        if( ( $this->CurVal = next( $this->ArrayRef ) ) === false )
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
  \brief Code common for the loop functions in processed mode.
*/
class eZTemplateLoop
{
    function eZTemplateLoop( $functionName, &$functionParameters, &$functionChildren, &$functionPlacement,
                             &$tpl, &$textElements, &$rootNamespace, &$currentNamespace )
    {
        $this->SkipDelimiter         = false;
        $this->SkipSequenceIncrement = true;
        $this->Delimiter             = null;
        $this->Initialized           = true;
        $this->SequenceVarName       = null;
        $this->Sequence              = null;
        $this->LoopVariablesNames    = array();


        $this->FunctionName       =  $functionName;
        $this->FunctionParameters =& $functionParameters;
        $this->FunctionChildren   =& $functionChildren;

        $this->Tpl                =& $tpl;
        $this->TextElements       =& $textElements;
        $this->RootNamespace      =& $rootNamespace;
        $this->CurrentNamespace   =& $currentNamespace;
        $this->FunctionPlacement  =& $functionPlacement;

        $this->Initialized = $this->processFunctionParameters();
    }

    /*!
    \return true on success, false otherwise.
    */
    function processFunctionParameters()
    {
        $params =& $this->FunctionParameters;

        if ( !isset( $params['sequence_array'] ) || !count( $params['sequence_array'] ) )
            return true;

        $this->parseParamVarName( 'sequence_var', $seqVarName );

        if ( !$seqVarName )
        {
            $this->Tpl->error( $this->FunctionName, "Empty sequence variable name." );
            return false;
        }

        $this->initLoopVariable( $seqVarName );

        $seqArray = $this->Tpl->elementValue( $params['sequence_array'],
                                              $this->RootNamespace, $this->CurrentNamespace, $this->FunctionPlacement );

        $this->Sequence        = new eZTemplateLoopSequence( $seqArray );
        $this->SequenceVarName = $seqVarName;

        return true;
    }

    /*!
     * \return true if the object has been correctly initialized, false otherwise
     */
    function initialized()
    {
        return $this->Initialized;
    }

    /*! Export current loop sequence value to the template variable
     *  specified in loop parameters.
     */
    function setSequenceVar()
    {
        if ( !$this->hasSequence() )
            return;

        $this->Tpl->setVariable( $this->SequenceVarName,  $this->Sequence->val(), $this->RootNamespace );
    }

    /*!
     * Should be called each time a new iteration is started.
     *  Resets some internal variables.
     */
    function resetIteration()
    {
        $this->SkipDelimiter         = false;
        $this->SkipSequenceIncrement = false;
    }

    /*!
     * Increment current sequence value.
     */
    function incrementSequence()
    {
        if ( $this->hasSequence() && !$this->SkipSequenceIncrement )
            $this->Sequence->next();
    }

    /*!
     * Returns true if sequence has been specified for the loop in its parameters.
     */
    function hasSequence()
    {
        return !is_null( $this->Sequence );
    }


    /*!
     * Destroys template variables defined by the loop.
     */
    function cleanup()
    {
        // destroy loop variable(s)
        foreach ( $this->LoopVariablesNames as $varName )
            $this->Tpl->unsetVariable( $varName, $this->RootNamespace );
    }

    /*
     * Processes loop children, i.e. all tags and text that is
     * between start and end tags of the loop.
     * Besides, does special handling of {break}, {skip}, {continue} and {delimiter} tags.
     *
     * \return true if the caller loop should break, false otherwise
     */
    function processChildren()
    {
        foreach ( array_keys( $this->FunctionChildren ) as $childKey )
        {
            $child =& $this->FunctionChildren[$childKey];
            $this->SkipDelimiter = false;

            if ( $child[0] == EZ_TEMPLATE_NODE_FUNCTION ) // check child type
            {
                $childFunctionName  =& $child[2];

                if ( $childFunctionName == 'break' )
                    return true;
                elseif ( $childFunctionName == 'continue' )
                {
                    $this->SkipSequenceIncrement = true;
                    break;
                }
                elseif ( $childFunctionName == 'skip' )
                {
                    $this->SkipSequenceIncrement = true;
                    $this->SkipDelimiter = true;
                    break;
                }
                elseif ( $childFunctionName == 'delimiter' )
                {
                    if ( is_null( $this->Delimiter ) )
                        $this->Delimiter =& $child;
                    continue;
                }
            }

            $rslt = $this->Tpl->processNode( $child, $this->TextElements, $this->RootNamespace, $this->CurrentNamespace );

            // break/continue/skip might be found in a child function's (usually {if}) children
            if ( is_array( $rslt ) )
            {
                if ( array_key_exists( 'breakFunctionFound', $rslt ) )
                    return true;
                elseif ( array_key_exists( 'continueFunctionFound', $rslt ) )
                {
                    $this->SkipSequenceIncrement = true;
                    break;
                }
                elseif ( array_key_exists( 'skipFunctionFound', $rslt ) )
                {
                    $this->SkipSequenceIncrement = true;
                    $this->SkipDelimiter = true;
                    break;
                }
            }

        } // foreach

        return false;
    }

    /*!
     * If \c $loopCondition is true, shows delimiter (if one has been specified).
     *
     * \return true if the caller loop should break, false otherwise
     */
    function processDelimiter( $loopCondition )
    {
        if ( !( !is_null( $this->Delimiter ) && !$this->SkipDelimiter ) )
            return false;

        // Check the 'while' condition again
        if ( !$loopCondition )
            return true;

        $delimiterChildren =& $this->Delimiter[1];
        foreach ( array_keys( $delimiterChildren ) as $key )
            $this->Tpl->processNode( $delimiterChildren[$key], $this->TextElements, $this->RootNamespace, $this->CurrentNamespace );

        return false;
    }

    /*!
     * Parses the given function parameter that is supposed to contain a variable name.
     * Extracted variable name is stored to $dst.
     *
     * @param $paramName Parameter name.
     * @param $dst       Where to store parameter value.
     * @return           false if specified parameter is not found or it is wrong, otherwise true is returned.
     */
    function parseParamVarName( $paramName, &$dst )
    {
        $dst = null;

        if ( !isset( $this->FunctionParameters[$paramName] ) ||
             !count( $this->FunctionParameters[$paramName] ) )
            return false;

        list( $varNsName, $varNsType, $varName ) = $this->FunctionParameters[$paramName][0][1];

        if ( $varNsType != EZ_TEMPLATE_NAMESPACE_SCOPE_LOCAL || $varNsName )
        {
            $this->Tpl->error( $this->FunctionName,
                               'Loop variables can be defined in root namespace only (e.g. $foo, but not $#foo or $:foo.)' );
            return false;
        }

        $dst = $varName;
        return true;
    }

    /*!
     * Parses given function parameter and makes sure that it is not a proxy object ({section} loop iterator).
     *
     * @param  $paramName      Parameter name.
     * @param  $dst            Where to store parameter value.
     * @param  $isProxyObject  boolean true is stored here if value of the parameter is a proxy object.
     * @return                 false if specified parameter is not found or it is wrong, otherwise true is returned.
     */
    function parseScalarParamValue( $paramName, &$dst, &$isProxyObject )
    {
        $dst = null;

        if ( !isset( $this->FunctionParameters[$paramName] ) || !count( $this->FunctionParameters[$paramName] ) )
            return false;

        // get parameter value
        $dst = $this->Tpl->elementValue( $this->FunctionParameters[$paramName], $this->RootNamespace,
                                         $this->CurrentNamespace, $this->FunctionPlacement, false, true );

        // check if a proxy object ({section} loop iterator) was involved in the parameter value
        if ( isset( $this->FunctionParameters[$paramName]['proxy-object-found'] ) )
        {
            $isProxyObject = true;
            unset( $this->FunctionParameters[$paramName]['proxy-object-found'] ); // just not to leave garbage
        }
        else
            $isProxyObject = false;

        return true;
    }

    /*!
     * Parses value the given function parameter and stores it to $dst.
     *
     * @param  $paramName      Parameter name.
     * @param  $dst            Where to store parameter value.
     * @return                 false if specified parameter is not found or it is wrong, otherwise true is returned.
     */
    function parseParamValue( $paramName, &$dst )
    {
        $dst = null;

        if ( !isset( $this->FunctionParameters[$paramName] ) || !count( $this->FunctionParameters[$paramName] ) )
            return false;

        $dst = $this->Tpl->elementValue( $this->FunctionParameters[$paramName], $this->RootNamespace,
                                         $this->CurrentNamespace, $this->FunctionPlacement );
        return true;
    }

    /*!
     * Checks if the given loop variable already exists. If it doesn't, store its name for later cleanup.
     * Otherwise shows a warning message.
     *
     * @see eZTemplateLoop::$loopVariablesNames
     */
    function initLoopVariable( $varName )
    {
        if ( $this->Tpl->hasVariable( $varName, $this->RootNamespace ) )
            $this->Tpl->warning( $this->FunctionName, "Variable '$varName' already exists." );
        else
            $this->LoopVariablesNames[] = $varName;
    }

    ///
    /// \privatesection
    ///

    var $FunctionName;
    var $FunctionParameters;
    var $FunctionChildren;
    var $FunctionPlacement;

    var $SkipDelimiter;
    var $SkipSequenceIncrement;
    var $delimiter;

    var $Tpl;
    var $TextElements;
    var $RootNamespace;
    var $CurrentNamespace;

    var $Initialized;
    var $Sequence;
    var $SequenceVarName;
    /*!
     * Before we create a new loop variable, we check if it already exists.
     * If it doesn't, we store its name in this array, so that we know
     * which variables to destroy after the loop execution finishes.
     */
    var $LoopVariablesNames;
}

?>
