<?php
/**
 * File containing Rest demo view
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpRestDemoView extends ezcMvcView
{
    function createZones( $layout )
    {
        $zones = array();
        // $zones[] = new ezcMvcPhpViewHandler( 'content', 'rest/rest/output.php' );
        $zones[] = new ezcMvcJsonViewHandler( 'content' );
        return $zones;
    }
}
?>