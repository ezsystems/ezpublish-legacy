<?php
//
// Definition of eZFunctionHandler class
//
// Created on: <06-Oct-2002 16:25:10 amos>
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
    function &executeAlias( $aliasFunctionName, $functionParameters )
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

            $functionName =& $aliasSettings->variable( $aliasFunctionName, 'FunctionName' );

            $functionArray = array();
            if ( $aliasSettings->hasVariable( $aliasFunctionName, 'Parameter' ) )
            {
                $parameterTranslation =& $aliasSettings->variable( $aliasFunctionName, 'Parameter' );
                foreach( array_keys( $parameterTranslation ) as $functionKey )
                {
                    $functionArray[$functionKey] = $functionParameters[$parameterTranslation[$functionKey]];
                }
            }

            if ( $aliasSettings->hasVariable( $aliasFunctionName, 'Constant' ) )
            {
                $constantParameterArray =& $aliasSettings->variable( $aliasFunctionName, 'Constant' );
                foreach ( array_keys( $constantParameterArray ) as $constKey )
                {
                    if ( $moduleFunctionInfo->isParameterArray( $functionName, $constKey ) )
                        $functionArray[$constKey] = explode( ';', $constantParameterArray[$constKey] );
                    else
                        $functionArray[$constKey] = $constantParameterArray[$constKey];
                }
            }

            $paramstr = '';
            foreach ( array_keys( $functionArray ) as $key )
            {
                    $paramstr .= $key . '=' . $functionArray[$key] . "\n";
            }

//            eZDebug::writeNotice( "Executing fetchAlias with the following parameters:\n" . $paramstr,
//                                  'eZFunctionHandler::executeAlias' );

            return $moduleFunctionInfo->execute( $functionName, $functionArray );
        }
        eZDebug::writeWarning( 'Could not execute. Function ' . $aliasFunctionName. ' not found.' ,
                               'eZFunctionHandler::executeAlias' );
    }

    function &execute( $moduleName, $functionName, $functionParameters )
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
