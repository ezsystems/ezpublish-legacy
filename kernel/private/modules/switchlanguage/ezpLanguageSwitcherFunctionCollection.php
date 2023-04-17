<?php
/**
 * File containing ezpLanguageSwitcherFunctionCollection class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class ezpLanguageSwitcherFunctionCollection
{
    public function fetchUrlAlias( $nodeId, $path, $locale )
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
