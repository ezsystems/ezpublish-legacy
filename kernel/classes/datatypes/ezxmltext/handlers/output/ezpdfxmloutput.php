<?php
/**
 * File containing the eZPDFXMLOutput class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class eZPDFXMLOutput extends eZXHTMLXMLOutput
{

    function eZPDFXMLOutput( &$xmlData, $aliasedType, $contentObjectAttribute = null )
    {
        $this->eZXHTMLXMLOutput( $xmlData, $aliasedType, $contentObjectAttribute );

        $this->OutputTags['table']['initHandler'] = 'initHandlerTable';
        $this->OutputTags['li']['initHandler'] = 'initHandlerLi';
    }

    function initHandlerTable( $element, &$attributes, &$sibilingParams, &$parentParams )
    {
        $ret = array();

        if( !isset( $attributes['width'] ) )
            $attributes['width'] = '100%';

        if( !isset( $attributes['border'] ) )
            $attributes['border'] = 1;

        return $ret;
    }

    function initHandlerLi( $element, &$attributes, &$sibilingParams, &$parentParams )
    {
        if( !isset( $sibilingParams['list_count'] ) )
            $sibilingParams['list_count'] = 1;
        else
            $sibilingParams['list_count']++;

        $ret = array( 'tpl_vars' => array( 'list_count' => $sibilingParams['list_count'],
                                           'tag_name' => $element->parentNode->nodeName ) );
        return $ret;
    }

    public $TemplatesPath = 'design:content/datatype/pdf/ezxmltags/';
}

?>
