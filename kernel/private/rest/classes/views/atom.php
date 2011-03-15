<?php
/**
 * File containing the the view for atom
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * View providing atomfeed of the output
 */
class ezpRestAtomView extends ezcMvcView
{
    public function __construct( ezcMvcRequest $request, ezcMvcResult $result )
    {
        parent::__construct( $request, $result );

        $result->content = new ezcMvcResultContent();
        $result->content->type = "application/atom+xml";
        $result->content->charset = "UTF-8";
    }

    public function createZones( $layout )
    {
        $zones = array();
        $zones[] = new ezcMvcFeedViewHandler( 'content', new ezpRestAtomDecorator, 'atom' );
        return $zones;
    }
}

?>
