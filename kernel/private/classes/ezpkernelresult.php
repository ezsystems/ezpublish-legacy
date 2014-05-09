<?php
/**
 * File containing the ezpKernelResult class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

/**
 * Struct containing the kernel result.
 */
class ezpKernelResult
{
    /**
     * Kernel result as an HTML string
     *
     * @var string
     */
    private $content;

    /**
     * Arbitrary attributes that can be used to make the result's state vary (e.g. meta information)
     *
     * @var array
     */
    private $attributes;

    /**
     * @param string $content The result's main content
     * @param array $defaultAttributes
     */
    public function __construct( $content = null, array $defaultAttributes = array() )
    {
        $this->content = $content;
        $this->attributes = $defaultAttributes;
    }

    /**
     * Sets the main content.
     *
     * @param string $content
     */
    public function setContent( $content )
    {
        $this->content = $content;
    }

    /**
     * Returns the result's main content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets an attribute
     *
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute( $name, $value )
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @param string $name
     * @param mixed $defaultValue Default value to return if the attribute doesn't exist.
     * @return mixed
     */
    public function getAttribute( $name, $defaultValue = null )
    {
        return isset( $this->attributes[$name] ) ? $this->attributes[$name] : $defaultValue;
    }

    /**
     * Checks if $name attribute is present
     *
     * @param string $name
     * @return bool
     */
    public function hasAttribute( $name )
    {
        return isset( $this->attributes[$name] );
    }

    /**
     * Returns all the attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Sets multiple attributes at once. Internal ones that are not inside $attributes won't be overridden.
     *
     * @param array $attributes
     */
    public function setAttributes( array $attributes )
    {
        $this->attributes = $attributes + $this->attributes;
    }

    /**
     * Replaces internal attributes entirely.
     *
     * @param array $attributes
     */
    public function replaceAttributes( array $attributes )
    {
        $this->attributes = $attributes;
    }
}
