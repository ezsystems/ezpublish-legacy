<?php
/**
 * File containing the eZURLOperatorFullPath class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

class eZURLOperatorFullPath extends eZURLOperator
{
    function urlTransformation( $operatorName, &$node, $tpl, &$resourceData, $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        switch ( $operatorName )
        {
            case 'ezurl':
            case 'ezroot':
                // In the case first parameter has been omitted, it is required to register the default value to prevent notices.
                if ( !isset( $parameters[1] ) )
                {
                    $parameters[1] = array( array( 1, 'double', false ) );
                }

                // Forcing second parameter to be 'full'.
                $parameters[2] = array( array( 1, 'full', false ) );
        }

        return parent::urlTransformation( $operatorName, $node, $tpl, $resourceData, $element, $lastElement, $elementList, $elementTree, $parameters );
    }
}

?>
