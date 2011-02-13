<?php
//
// Definition of eZFunctionHandler class
//
// Created on: <06-Oct-2002 16:25:10 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZFunctionHandler ezfunctionhandler.php
  \brief The class eZFunctionHandler does

*/

class eZFunctionHandler
{
    static function moduleFunctionInfo( $moduleName )
    {
        if ( !isset( $GLOBALS['eZGlobalModuleFunctionList'] ) )
        {
            $GLOBALS['eZGlobalModuleFunctionList'] = array();
        }
        if ( isset( $GLOBALS['eZGlobalModuleFunctionList'][$moduleName] ) )
        {
            return $GLOBALS['eZGlobalModuleFunctionList'][$moduleName];
        }
        $moduleFunctionInfo = new eZModuleFunctionInfo( $moduleName );
        $moduleFunctionInfo->loadDefinition();

        return $GLOBALS['eZGlobalModuleFunctionList'][$moduleName] = $moduleFunctionInfo;
    }

    /*!
     \static
     Execute alias fetch for simplified fetching of objects
    */
    static function executeAlias( $aliasFunctionName, $functionParameters )
    {
        $aliasSettings = eZINI::instance( 'fetchalias.ini' );
        if ( $aliasSettings->hasSection( $aliasFunctionName ) )
        {
            $moduleFunctionInfo = eZFunctionHandler::moduleFunctionInfo( $aliasSettings->variable( $aliasFunctionName, 'Module' ) );
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

    static function execute( $moduleName, $functionName, $functionParameters )
    {
        $moduleFunctionInfo = eZFunctionHandler::moduleFunctionInfo( $moduleName );
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
