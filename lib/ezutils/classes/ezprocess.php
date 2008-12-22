<?php
//
// Definition of eZProcess class
//
// Created on: <16-Apr-2002 10:53:33 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
  \class eZProcess ezprocess.php
  \ingroup eZUtils
  \brief Executes php scripts with parameters safely

*/
class eZProcess
{
    static function run( $file, $Params = array(), $params_as_var = false )
    {
        return eZProcess::instance()->runFile( $Params, $file, $params_as_var );
    }

    /*!
     Helper function, executes the file.
     */
    function runFile( $Params, $file, $params_as_var )
    {
        $Result = null;
        if ( $params_as_var )
        {
            foreach ( $Params as $key => $dummy )
            {
                if ( $key != "Params" and
                     $key != "this" and
                     $key != "file" and
                     !is_numeric( $key ) )
                {
                    ${$key} = $Params[$key];
                }
            }
        }

        if ( file_exists( $file ) )
        {
            $includeResult = include( $file );
            if ( empty( $Result ) &&
                 $includeResult != 1 )
            {
                $Result = $includeResult;
            }
        }
        else
            eZDebug::writeWarning( "PHP script $file does not exist, cannot run.",
                                   "eZProcess" );
        return $Result;
    }

    static function instance()
    {
        if ( empty( $GLOBALS['eZProcessInstance'] ) )
        {
            $GLOBALS['eZProcessInstance'] = new eZProcess();
        }
        return $GLOBALS['eZProcessInstance'];
    }
}

?>
