<?php
//
// Definition of eZGeneralRule class
//
// Created on: <30-еб-2002 12:14:03 wy>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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

/*! \file ezgeneralrule.php
*/

/*!
  \class eZGeneralRule ezgeneralrule.php
  \brief The class eZGeneralRule does

*/
include_once( "kernel/notification/eznotificationrule.php" );

define( "EZ_NOTIFICATIONRULESTRING_GENERAL", "ezgeneral" );

class eZGeneralRule extends eZNotificationRuleType
{
    /*!
     Constructor
    */
    function eZGeneralRule()
    {
        $this->eZNotificationRuleType( EZ_NOTIFICATIONRULESTRING_GENERAL, "general" );
    }

    /*!
     Check whether or not the content object \a $contentObject satisfies notification rule \a $notificationRule which
     rule type is "general".
    */
    function match( &$contentObject, &$notificationRule )
    {
        $contentClass =& $contentObject->contentClass();
        $contentClassName = $contentClass->attribute( "name" );
        $notificationClassName = $notificationRule->attribute( "contentclass_name" );
        if ( ( $contentClassName == $notificationClassName ) or  $notificationClassName == "All" )
            return true;
        return false;
    }
}
eZNotificationRuleType::register( EZ_NOTIFICATIONRULESTRING_GENERAL, "ezgeneralrule" );
?>
