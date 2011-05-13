<?php
/**
 * File containing the eZTipafriendRequest class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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
