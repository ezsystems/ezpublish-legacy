<?php
/**
 * File containing the eZURLAliasFilter class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
     * @params object The eZContentObjectTreeNode in which the eZContentObject is published
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
