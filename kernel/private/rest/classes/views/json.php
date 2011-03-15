<?php
/**
 * File containing REST JSON view
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpRestJsonView extends ezcMvcView
{
    public function __construct( ezcMvcRequest $request, ezcMvcResult $result )
    {
        parent::__construct( $request, $result );

        $result->content = new ezcMvcResultContent();
        $result->content->type = "application/json";
        $result->content->charset = "UTF-8";
    }

    public function createZones( $layout )
    {
        $zones = array();
        $zones[] = new ezcMvcJsonViewHandler( 'content' );
        return $zones;
    }
}
?>
