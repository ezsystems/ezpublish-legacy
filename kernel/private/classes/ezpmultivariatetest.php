<?php
/**
 * File containing the ezpMultivariateTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class ezpMultivariateTest
{
    /**
     * Container for an instance of the ezpMultivariateTestHandlerInterface class
     *
     * @var ezpMultivariateTestHandlerInterface
     */
    protected $handler;

    /**
     * Construct
     *
     * @param ezpMultivariateTestHandlerInterface $handler
     */
    public function __construct( ezpMultivariateTestHandlerInterface $handler  )
    {
        $this->handler = $handler;
    }

    /**
     * Returns an instance of the ezpMultivariateTestHandlerInterface class
     *
     * @static
     * @return ezpMultivariateTestHandlerInterface|null
     */
    public static function getHandler()
    {
        $testHandlerClass = eZINI::instance( 'content.ini' )->variable( 'TestingSettings', 'MultivariateTestingHandlerClass' );
        $testHandler = class_exists( $testHandlerClass ) ? new $testHandlerClass : null;

        if ( $testHandler instanceof ezpMultivariateTestHandlerInterface )
            return $testHandler;

        return null;
    }

    /**
     * Checks wheter multivariate testing is enabled or not
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->handler->isEnabled();
    }

    /**
     * Executes multivatriate test secnarios
     *
     * @param $nodeID
     * @return int
     */
    public function execute( $nodeID )
    {
        return $this->handler->execute( $nodeID );
    }
}
