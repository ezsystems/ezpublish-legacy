<?php
/**
 * File containing ezpLanguageSwitcherFunctionCollection class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class ezpLanguageSwitcherFunctionCollection
{
    public function fetchUrlAlias( $nodeId = null, $path = null, $locale )
    {
        if ( !$nodeId && !$path )
        {
            return array( 'result' => false );
        }

        if ( empty( $locale ) || !is_string( $locale ) )
        {
            return array( 'result' => false );
        }

        if ( is_numeric( $nodeId ) )
        {
            $destinationElement = eZURLAliasML::fetchByAction( 'eznode', $nodeId, $locale, false );
        }
        else if ( is_string( $path ) )
        {
            $nodeId = eZURLAliasML::fetchNodeIDByPath( $path );
            $destinationElement = eZURLAliasML::fetchByAction( 'eznode', $nodeId, $locale, false );
        }

        if ( empty( $destinationElement ) || ( !isset( $destinationElement[0] ) && !( $destinationElement[0] instanceof eZURLAliasML ) ) )
        {
            // Either no translation exists for $locale or $path was not pointing to a node
            return array( 'result' => false );
        }

        $currentLanguageCodes = eZContentLanguage::prioritizedLanguageCodes();
        array_unshift( $currentLanguageCodes, $locale );
        $currentLanguageCodes = array_unique( $currentLanguageCodes );
        $urlAlias = $destinationElement[0]->getPath( $locale, $currentLanguageCodes );
        return array( 'result' => $urlAlias );
    }
}

?>
