<?php
/**
 * File containing the ezpContentXHTMLRenderer class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpContentXHTMLRenderer extends ezpRestContentRendererInterface
{
    /**
     * Construct
     *
     * @param ezpContent $content
     */
    public function __construct( ezpContent $content )
    {
        $this->content = $content;
    }

    /**
     * Returns string with rendered content
     *
     * @return string
     */
    public function render()
    {
        return eZNodeviewfunctions::generateNodeViewData( eZTemplate::factory(), $this->content->main_node, $this->content->main_node->attribute( 'object' ), 'eng-GB', 'full', 0 );
    }
}
