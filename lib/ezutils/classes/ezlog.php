<?php
//
// Definition of eZLog class
//
// Created on: <17-Mar-2003 11:00:54 wy>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \defgroup eZUtils Utility classes */

/*!
  \class eZLog ezlog.php
  \ingroup eZUtils
*/


include_once( "lib/ezutils/classes/ezsys.php" );

class eZLog
{
    /*!
      Creates a new log object.
    */
    function eZLog( )
    {
    }

    /*!
     \private
     Writes file name $name and storage directory $dir to storage log
    */
    function writeStorageLog( $name, $dir )
    {
        $ini =& eZINI::instance();
        $varDir = $ini->variable( 'FileSettings', 'VarDir' );
        $logDir = $ini->variable( 'FileSettings', 'LogDir' );
        $logName = "storage.log";
        $fileName = $varDir . "/" . $logDir . "/" . $logName;
        if ( !file_exists( $logDir ) )
        {
            include_once( 'lib/ezutils/classes/ezdir.php' );
            eZDir::mkdir( $logDir, 0775, true );
        }
        $oldumask = @umask( 0 );
        $fileExisted = @file_exists( $fileName );
        $logFile = @fopen( $fileName, "a" );
        if ( $logFile )
        {
            $time = strftime( "%b %d %Y %H:%M:%S", strtotime( "now" ) );
            $logMessage = "[ " . $time . " ] [" . $dir . $name . "]\n";
            @fwrite( $logFile, $logMessage );
            @fclose( $logFile );
            if ( !$fileExisted )
                @chmod( $fileName, 0664 );
            @umask( $oldumask );
        }
    }
}

?>
