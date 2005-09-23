<?php
//
// Definition of eZFunctionHandler class
//
// Created on: <06-Oct-2002 16:25:10 amos>
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

/*! \file ezfunctionhandler.php
*/

/*!
  \class eZFunctionHandler ezfunctionhandler.php
  \brief The class eZFunctionHandler does

*/

include_once( 'lib/ezutils/classes/ezmodulefunctioninfo.php' );

class eZFunctionHandler
{
    /*!
     Constructor
    */
    function eZFunctionHandler()
    {
    }

    function &moduleFunctionInfo( $moduleName )
    {
        $globalModuleFunctionList =& $GLOBALS['eZGlobalModuleFunctionList'];
        if ( !isset( $globalModuleFunctionList ) )
            $globalModuleFunctionList = array();
        if ( isset( $globalModuleFunctionList[$moduleName] ) )
            return $globalModuleFunctionList[$moduleName];
        $moduleFunctionInfo = new eZModuleFunctionInfo( $moduleName );
        $moduleFunctionInfo->loadDefinition();
        $globalModuleFunctionList[$moduleName] =& $moduleFunctionInfo;
        return $moduleFunctionInfo;
    }

    /*!
     \static
     Execute alias fetch for simplified fetching of objects
    */
    function executeAlias( $aliasFunctionName, $functionParameters )
    {
        $aliasSettings =& eZINI::instance( 'fetchalias.ini' );
        if ( $aliasSettings->hasSection( $aliasFunctionName ) )
        {
            $moduleFunctionInfo =& eZFunctionHandler::moduleFunctionInfo( $aliasSettings->variable( $aliasFunctionName, 'Module' ) );
            if ( !$moduleFunctionInfo->isValid() )
            {
                eZDebug::writeError( "Cannot execute function '$aliasFunctionName' in module '$moduleName', no valid data",
                                     'eZFunctionHandler::executeAlias' );
                return null;
            }

            $functionName = $aliasSettings->variable( $aliasFunctionName, 'FunctionName' );

            $functionArray = array();
            if ( $aliasSettings->hasVariable( $aliasFunctionName, 'Parameter' ) )
            {
                $parameterTranslation = $aliasSettings->variable( $aliasFunctionName, 'Parameter' );
                foreach( array_keys( $parameterTranslation ) as $functionKey )
                {
                    $translatedParameter = $parameterTranslation[$functionKey];
                    if ( array_key_exists( $translatedParameter, $functionParameters ) )
                         $functionArray[$functionKey] = $functionParameters[$translatedParameter];
                    else
                        $functionArray[$functionKey] = null;
                }
            }

            if ( $aliasSettings->hasVariable( $aliasFunctionName, 'Constant' ) )
            {
                $constantParameterArray = $aliasSettings->variable( $aliasFunctionName, 'Constant' );
                // prevent PHP warning in the loop below
                if ( !is_array( $constantParameterArray ) )
                    $constantParameterArray = array();
                foreach ( array_keys( $constantParameterArray ) as $constKey )
                {
                    if ( $moduleFunctionInfo->isParameterArray( $functionName, $constKey ) )
                    {
                        /*
                         Check if have Constant overriden by function parameter
                         */
                        if ( array_key_exists( $constKey, $functionParameters ) )
                        {
                            $functionArray[$constKey] =& $functionParameters[$constKey] ;
                            continue;
                        }
                        /*
                         Split given string using semicolon as delimiter.
                         Semicolon may be escaped by prepending it with backslash:
                         in this case it is not treated as delimiter.
                         I use \x5c instead of \\ here.
                         */
                        $constantParameter = preg_split( '/((?<=\x5c\x5c)|(?<!\x5c{1}));/',
                                                         $constantParameterArray[$constKey] );

                        /*
                         Unfortunately, my PHP 4.3.6 doesn't work correctly
                         if flag PREG_SPLIT_NO_EMPTY is set.
                         That's why we need to manually remove
                         empty strings from $constantParameter.
                         */
                        $constantParameter = array_diff( $constantParameter, array('') );

                        /*
                         Hack: force array keys to be consecutive, starting from zero (0, 1, 2, ...).
                         Otherwise SQL syntax error occurs.
                         */
                        $constantParameter = array_values( $constantParameter );

                        if ( $constantParameter ) // if the array is not empty
                        {
                            // Remove backslashes used for delimiter escaping.
                            $constantParameter = preg_replace( '/\x5c{1};/', ';', $constantParameter );
                            $constantParameter = str_replace( '\\\\', '\\', $constantParameter );

                            // Return the result.
                            $functionArray[$constKey] = $constantParameter;
                        }
                    }
                    else
                        $functionArray[$constKey] = $constantParameterArray[$constKey];
                }
            }

/*
 */
            foreach ( $functionParameters as $paramName => $value )
            {
                if ( !array_key_exists( $paramName, $functionArray ) )
                {
                    $functionArray[$paramName] = $value;
                }
            }
            return $moduleFunctionInfo->execute( $functionName, $functionArray );
        }
        eZDebug::writeWarning( 'Could not execute. Function ' . $aliasFunctionName. ' not found.' ,
                               'eZFunctionHandler::executeAlias' );
    }

    function execute( $moduleName, $functionName, $functionParameters )
    {
        $moduleFunctionInfo =& eZFunctionHandler::moduleFunctionInfo( $moduleName );
        if ( !$moduleFunctionInfo->isValid() )
        {
            eZDebug::writeError( "Cannot execute function '$functionName' in module '$moduleName', no valid data",
                                  'eZFunctionHandler::execute' );
            return null;
        }

        return $moduleFunctionInfo->execute( $functionName, $functionParameters );
    }
}

?>
