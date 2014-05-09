<?php
/**
 * File containing the ezpRestContentRendererInterface interface
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

abstract class ezpRestContentRendererInterface
{
    /**
     * @var Container for ezpContent object
     */
    protected $content;

    /**
     * @var Container for ezpRestMvcController object
     */
    protected $controller;

    /**
     * Creates an instance of a ezpRestContentRendererInterface for given content
     *
     * @abstract
     * @param ezpContent $content
     *
     */
    abstract public function __construct( ezpContent $content, ezpRestMvcController $controller );

    /**
     * Returns string with rendered content
     *
     * @abstract
     * @return string
     */
    abstract public function render();
}
