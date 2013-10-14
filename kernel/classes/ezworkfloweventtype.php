<?php
/**
 * File containing the eZWorkflowEventType class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

//!! eZKernel
//! The class eZWorkflowEventType does
/*!

*/

class eZWorkflowEventType extends eZWorkflowType
{
    /**
     * Constructor
     *
     * @param string $typeString
     * @param string $name
     */
    public function __construct( $typeString, $name )
    {
        parent::__construct( "event", $typeString, ezpI18n::tr( 'kernel/workflow/event', "Event" ), $name );
    }

    /**
     * @deprecated
     * @param string $typeString
     * @param string $name
     */
    public function eZWorkflowEventType( $typeString, $name )
    {
        self::__construct( $typeString, $name );
    }

    static function registerEventType( $typeString, $class_name )
    {
        eZWorkflowType::registerType( "event", $typeString, $class_name );
    }
}

?>
