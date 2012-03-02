<?php
/**
 * File containing the ezpMultivariateTestHandler class.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class ezpMultivariateTestHandler implements ezpMultivariateTestHandlerInterface
{

    /**
     * Checks wheter multivariate testing is enabled or not
     *
     * @return bool
     */
    public function isEnabled()
    {
        return ( eZINI::instance( 'content.ini' )->variable( 'TestingSettings', 'MultivariateTesting' ) === 'enabled' );
    }

    /**
     * Executes multivatriate test secnarios
     *
     * @param int $nodeID
     * @return int
     */
    public function execute( $nodeID )
    {
        $currentSiteAccess = eZSiteAccess::current();

        $testScenario = ezpMultivariateTestScenario::fetchEnabledByNodeID( $nodeID );
        if ( $testScenario instanceof ezpMultivariateTestScenario
                && in_array( $currentSiteAccess['name'], eZINI::instance( 'content.ini' )->variable( 'TestingSettings', 'EnabledForSiteAccessList' ) ) )
        {
            $node = $testScenario->getRandomNode();

            if ( $node instanceof eZContentObjectTreeNode )
                $nodeID = $node->attribute( 'node_id' );
        }

        return $nodeID;
    }
}
