<?php
//
// Definition of eZURLAliasFilter class
//
// Created on: <05-Oct-2007 09:03:31 jr>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.2.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

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