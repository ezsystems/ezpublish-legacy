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
  \class eZKeywordRule ezgeneralruletype.php
  \brief The class eZKeywordRule does

*/
include_once( "kernel/notification/eznotificationrule.php" );
include_once( "lib/ezdb/classes/ezdb.php" );

define( "EZ_NOTIFICATIONRULESTRING_KEYWORD", "ezkeyword" );

class eZKeywordRule extends eZNotificationRuleType
{
    /*!
     Constructor
    */
    function eZKeywordRule()
    {
        $this->eZNotificationRuleType( EZ_NOTIFICATIONRULESTRING_KEYWORD, "keyword" );
    }

    /*!
     Check whether or not the content object \a $contentObject satisfies notification rule \a $notificationRule which
     rule type is "keyword".
    */
    function match( &$contentObject, &$notification )
    {
        $contentObjectID = $contentObject->attribute( "id" );
        $db =& eZDB::instance();
        $keyword = $notification->attribute( "keyword" );
        $res = array();
        $res =& $db->arrayQuery( "SELECT DISTINCT contentobject_id
                                  FROM ezsearch_word, ezsearch_object_word_link
                                  WHERE word = '$keyword'
                                        AND contentobject_id = $contentObjectID
                                        AND ezsearch_word.id = word_id " );
        if ( count( $res ) != 0  )
        {
            return true;
        }
        return false;
    }
}
eZNotificationRuleType::register( EZ_NOTIFICATIONRULESTRING_KEYWORD, "ezkeywordrule" );
?>
