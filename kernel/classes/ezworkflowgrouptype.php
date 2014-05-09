<?php
/**
 * File containing the eZWorkflowGroupType class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

//!! eZKernel
//! The class eZWorkflowGroupType does
/*!

*/

class eZWorkflowGroupType extends eZWorkflowType
{
    function eZWorkflowGroupType( $typeString, $name )
    {
        $this->eZWorkflowType( "group", $typeString, ezpI18n::tr( 'kernel/workflow/group', "Group" ), $name );
    }

    static function registerGroupType( $typeString, $class_name )
    {
        eZWorkflowType::registerType( "group", $typeString, $class_name );
    }
}

?>
