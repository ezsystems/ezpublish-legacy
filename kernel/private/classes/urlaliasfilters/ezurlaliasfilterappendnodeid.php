<?php
/**
 * File containing the eZURLAliasFilter class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * This class is an URL alias filter to be run with the eZURLAliasFilter system
 *
 * It appends the value of the nodeID in the URL so the contents can be for
 * example indexed by Google for its Google News
 */
class eZURLAliasFilterAppendNodeID extends eZURLAliasFilter
{
    /**
     * Empty constructor
     */
    public function __construct() {}

    /**
     * Append the node ID of the object being published
     * So its URL alias will look like :
     * someurlalias-<nodeID>
     *
     * @param string The text of the URL alias
     * @param object The eZContentObject object being published
     * @param object The eZContentObjectTreeNode in which the eZContentObject is published
     * @return string The transformed URL alias with the nodeID
     */
    public function process( $text, &$languageObject, &$caller )
    {
        if( !$caller instanceof eZContentObjectTreeNode )
        {
            eZDebug::writeError( 'The caller variable was not an eZContentObjectTreeNode', __METHOD__ );
            return $text;
        }

        $ini = eZINI::instance( 'site.ini' );
        $applyOnClassList = $ini->variable( 'AppendNodeIDFilterSettings', 'ApplyOnClass' );

        $classIdentifier = $caller->attribute( 'class_identifier' );

        if( in_array( $classIdentifier, $applyOnClassList ) )
        {
            $separator  = eZCharTransform::wordSeparator();
            $text .= $separator . $caller->attribute( 'node_id' );
        }

        return $text;
    }
}
?>
