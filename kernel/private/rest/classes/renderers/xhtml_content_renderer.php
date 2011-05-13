<?php
/**
 * File containing the ezpContentXHTMLRenderer class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class ezpContentXHTMLRenderer extends ezpRestContentRendererInterface
{
    /**
     * Creates an instance of a ezpContentXHTMLRenderer for given content
     *
     * @param ezpContent $content
     */
    public function __construct( ezpContent $content, ezpRestMvcController $controller )
    {
        $this->content = $content;
        $this->controller = $controller;
    }

    /**
     * Returns string with rendered content
     *
     * @return string
     */
    public function render()
    {
        $tpl = eZTemplate::factory();
        $ini = eZINI::instance( 'rest.ini' );

        $nodeViewData = eZNodeviewfunctions::generateNodeViewData( $tpl, $this->content->main_node, $this->content->main_node->attribute( 'object' ), $this->content->activeLanguage, 'rest', 0 );

        $tpl->setVariable( 'module_result', $nodeViewData );

        $routingInfos = $this->controller->getRouter()->getRoutingInformation();
        $templateName = $ini->variable( $routingInfos->controllerClass . '_'. $routingInfos->action . '_OutputSettings', 'Template' );

        return $tpl->fetch( 'design:' . $templateName );
    }
}
