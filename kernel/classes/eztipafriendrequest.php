<?php
//
// Definition of eZTipafriendRequest class
//
// Created on: <16-Dec-2004 17:25:49 sp>
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

/*! \file eztipafriendrequest.php
*/

/*!
  \class eZTipafriendRequest eztipafriendrequest.php
  \brief The class eZTipafriendRequest does

*/
include_once( "lib/ezdb/classes/ezdb.php" );

class eZTipafriendRequest extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZTipafriendRequest( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function definition()
    {
        return array( "fields" => array( "email_receiver" => array( 'name' => "EmailReceiver",
                                                                    'datatype' => 'string',
                                                                    'default' => '',
                                                                    'required' => true ),
                                         "created" => array( 'name' => "Created",
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ) ),
                      "keys" => array( "email_receiver" ),
                      "class_name" => "eZTipafriendRequest",
                      "sort" => array( "created" => "desc" ),
                      "name" => "eztipafriend_request" );
    }

    function create( $receiver )
    {
        $row = array( "email_receiver" => $receiver,
                      "created" => time() );
        return new eZTipafriendRequest( $row );
    }

    function checkReceiver( $receiver )
    {
        eZTipafriendRequest::cleanup();
        $ini = eZINI::instance();
        $timeFrame = 1;
        if ( $ini->hasVariable( 'TipAFriend', 'TimeFrame' ) )
            $timeFrame = $ini->variable( 'TipAFriend', 'TimeFrame' );
        $time = time() - $timeFrame * 3600;
        $requestsPerTimeframe = 1;
        if ( $ini->hasVariable( 'TipAFriend', 'MaxRequestsPerTimeframe' ) )
            $requestsPerTimeframe = $ini->variable( 'TipAFriend', 'MaxRequestsPerTimeframe' );

        $db = eZDB::instance();
        $countResult = $db->arrayQuery( "SELECT count(*) as count
                                         FROM eztipafriend_request
                                         WHERE email_receiver = '$receiver'
                                           AND created > $time " );
        $count = 0;
        if ( isset(  $countResult[0]['count'] ) )
            $count = $countResult[0]['count'];
        if ( $count >= $requestsPerTimeframe )
            return false;
        return true;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function cleanup()
    {
        $ini = eZINI::instance();
        $db = eZDB::instance();
        $timeFrame = 1;
        if ( $ini->hasVariable( 'TipAFriend', 'TimeFrame' ) )
            $timeFrame = $ini->variable( 'TipAFriend', 'TimeFrame' );
        $time = time() - $timeFrame * 3600;

        $db->query( "DELETE FROM eztipafriend_request
                      WHERE created < $time " );
    }

}

?>
