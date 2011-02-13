<?php
//
// Definition of eZTipafriendRequest class
//
// Created on: <16-Dec-2004 17:25:49 sp>
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
  \class eZTipafriendRequest eztipafriendrequest.php
  \brief The class eZTipafriendRequest does

*/
class eZTipafriendRequest extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZTipafriendRequest( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( 'email_receiver' => array( 'name' => 'EmailReceiver',
                                                                    'datatype' => 'string',
                                                                    'default' => '',
                                                                    'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ) ),
                      'keys' => array( 'email_receiver' ),
                      'class_name' => 'eZTipafriendRequest',
                      'sort' => array( 'created' => 'desc' ),
                      'name' => 'eztipafriend_request' );
    }

    static function create( $receiver )
    {
        $row = array( "email_receiver" => $receiver,
                      "created" => time() );
        return new eZTipafriendRequest( $row );
    }

    static function checkReceiver( $receiver )
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
        $receiver = $db->escapeString( $receiver );
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
    static function cleanup()
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
