<?php
//
// Definition of eZNotificationTransport class
//
// Created on: <13-May-2003 12:01:34 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

/*! \file eznotificationtransport.php
*/

/*!
  \class eZNotificationTransport eznotificationtransport.php
  \brief The class eZNotificationTransport does

*/
include_once( 'kernel/classes/notification/ezmailnotificationtransport.php' );
class eZNotificationTransport
{
    /*!
     Constructor
    */
    function eZNotificationTransport()
    {
    }

    function &instance( $transport = false, $forceNewInstance = false )
    {
        $ini =& eZINI::instance( 'notification.ini' );
        if ( $transport == false )
        {
            $transport = $ini->variable( 'TransportSettings', 'DefaultTransport' );
        }
        $transportImpl =& $GLOBALS['eZNotificationTransportGlobalInstance_' . $transport ];
        $class =& get_class( $transportImpl );

        $fetchInstance = false;
        if ( !preg_match( '/.*?transport/', $class ) )
            $fetchInstance = true;

        if ( $forceNewInstance  )
        {
            $fetchInstance = true;
        }

        if ( $fetchInstance )
        {
            $extraPluginPathArray = $ini->variableArray( 'TransportSettings', 'TransportPluginPath' );
            $pluginPathArray = array_merge( array( 'kernel/classes/notification/' ),
                                            $extraPluginPathArray );
            foreach( $pluginPathArray as $pluginPath )
            {
                $transportFile = $pluginPath . $transport . 'notificationtransport.php';
                if ( file_exists( $transportFile ) )
                {
                    include_once( $transportFile );
                    $className = $transport . 'notificationtransport';
                    $impl = new $className( );
                    break;
                }
            }
        }
        if ( $impl === null )
        {
            $impl = new eZNotificationTransport();
            eZDebug::writeError( 'Transport implementation not supported: ' . $transport, 'eZNotificationTransport::instance' );
        }
        return $impl;
    }

    function send( $address = array(), $subject, $body, $transportData = null )
    {
        return true;
    }
}

?>
