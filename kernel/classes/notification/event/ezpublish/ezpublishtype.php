<?php
//
// Definition of eZPublishType class
//
// Created on: <12-May-2003 13:29:25 sp>
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

/*! \file ezpublishtype.php
*/

/*!
  \class eZPublishType ezpublishtype.php
  \brief The class eZPublishType does

*/
define( 'EZ_NOTIFICATIONTYPESTRING_PUBLISH', 'ezpublish' );

class eZPublishType extends eZNotificationEventType
{
    /*!
     Constructor
    */
    function eZPublishType()
    {
        $this->eZNotificationEventType( EZ_NOTIFICATIONTYPESTRING_PUBLISH );
    }

    function initializeEvent( &$event, $params )
    {
        eZDebugSetting::writeDebug( 'kernel-notification', $params, 'params for type' );
        $event->setAttribute( 'data_int1', $params['object'] );
        $event->setAttribute( 'data_int2', $params['version'] );
    }

    function &eventContent( &$event )
    {
        return eZContentObjectVersion::fetchVersion( $event->attribute( 'data_int2' ), $event->attribute( 'data_int1' ) );
    }
}

eZNotificationEventType::register( EZ_NOTIFICATIONTYPESTRING_PUBLISH, 'ezpublishtype' );

?>
