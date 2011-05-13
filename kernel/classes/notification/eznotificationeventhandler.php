<?php
/**
 * File containing the eZNotificationEventHandler class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZNotificationEventHandler eznotificationeventhandler.php
  \brief The class eZNotificationEventHandler does

*/

class eZNotificationEventHandler
{
    const EVENT_HANDLED = 0;
    const EVENT_SKIPPED = 1;
    const EVENT_UNKNOWN = 2;
    const EVENT_ERROR = 3;

    /*!
     Constructor
    */
    function eZNotificationEventHandler( $idString, $name )
    {
        $this->IDString = $idString;
        $this->Name = $name;
    }

    function attributes()
    {
        return array( 'id_string',
                      'name' );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function attribute( $attr )
    {
        if ( $attr == 'id_string' )
        {
            return $this->IDString;
        }
        else if ( $attr == 'name' )
        {
            return $this->Name;
        }

        eZDebug::writeError( "Attribute '$attr' does not exist", __METHOD__ );
        return null;
    }

    function handle( $event )
    {
        return true;
    }

    /*!
     Cleanup any specific tables or other resources.
    */
    function cleanup()
    {
    }

    function fetchHttpInput( $http, $module )
    {
        return true;
    }

    function storeSettings( $http, $module )
    {
        return true;
    }

    public $IDString = false;
    public $Name = false;
}

?>
