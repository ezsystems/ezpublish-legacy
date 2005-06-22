<?php
//
// Definition of eZProcess class
//
// Created on: <16-Apr-2002 10:53:33 amos>
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
  \class eZProcess ezprocess.php
  \ingroup eZUtils
  \brief Executes php scripts with parameters safely

*/

include_once( "lib/ezutils/classes/ezdebug.php" );

class eZProcess
{
    function eZProcess()
    {
    }

    function run( $file, $Params = array(), $params_as_var = false )
    {
        if ( isset( $this ) and
             get_class( $this ) == "ezprocess" )
            $instance =& $this;
        else
            $instance =& eZProcess::instance();
        return $instance->runFile( $Params, $file, $params_as_var );
    }

    /*!
     Helper function, executes the file.
     */
    function &runFile( &$Params, $file, $params_as_var )
    {
        if ( $params_as_var )
        {
            foreach ( $Params as $key => $dummy )
            {
                if ( $key != "Params" and
                     $key != "this" and
                     $key != "file" and
                     !is_numeric( $key ) )
                    ${$key} =& $Params[$key];
            }
        }

        if ( file_exists( $file ) )
        {

            $includeResult = include( $file );
            if ( !isset( $Result ) and $includeResult != 1 )
                $Result = $includeResult;
        }
        else
            eZDebug::writeWarning( "PHP script $file does not exist, cannot run.",
                                   "eZProcess" );
        return $Result;
    }

    function &instance()
    {
        $instance =& $GLOBALS["eZProcessInstance"];
        if ( get_class( $instance ) != "ezprocess" )
        {
            $instance = new eZProcess();
        }
        return $instance;
    }
}

?>
