<?php
//
// Definition of eZTOCOperator class
//
// Created on: <24-Aug-2005 15:11:12 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file eztocoperator.php
*/

/*!
  \class eZTOCOperator eztocoperator.php
  \brief The class eZTOCOperator does

*/

class eZTOCOperator
{
    /*!
     Constructor
    */
    function eZTOCOperator()
    {
    }

    /*!
     \return an array with the template operator name.
    */
    function operatorList()
    {
        return array( 'eztoc' );
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type,
             this is needed for operator classes that have multiple operators.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    /*!
     See eZTemplateOperator::namedParameterList
    */
    function namedParameterList()
    {
        return array( 'eztoc' => array( 'dom' => array( 'type' => 'object',
                                                        'required' => true,
                                                        'default' => 0 ) ) );
    }
    /*!
     Executes the PHP function for the operator cleanup and modifies \a $operatorValue.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $dom = $namedParameters['dom'];
        if ( get_class( $dom ) == 'ezcontentobjectattribute' )
        {
            $this->ObjectAttributeId = $dom->attribute( 'id' );
            $content = $dom->attribute( 'content' );
            $xmlData = $content->attribute( 'xml_data' );

            $xml = new eZXML();
            $domTree =& $xml->domTree( $xmlData );

            $tocText = '';
            //$tocText = '<div class="toc">';
            //$tocText .= '<div class="toc-design">';
            if ( is_object( $domTree ) )
            {
                $this->HeaderCounter = array();

                $rootNode = $domTree->root();
                $tocText .= '<ul>';
                $tocText .= $this->handleSection( $rootNode );
                $tocText .= '</ul>';
            }
            //$tocText .= '</div>';
            //$tocText .= '</div>';
        }
        $operatorValue = $tocText;
    }

    function handleSection( $sectionNode, $sectionLevel = 0 )
    {
        // Reset level counter
        $this->HeaderCounter[$sectionLevel] = 0;

        $tocText = '';
        foreach ( $sectionNode->children() as $child )
        {
            if ( $child->name() == 'header' )
            {
                $level = $sectionLevel;

                $this->HeaderCounter[$level] += 1;
                $i = 1;
                $headerAutoName = "";
                while ( $i <= $level )
                {
                    if ( $i > 1 )
                    $headerAutoName .= "_";

                    $headerAutoName .= $this->HeaderCounter[$i];
                    $i++;
                }
                $tocText .= '<li><a href="#' . $this->ObjectAttributeId . '_' . $headerAutoName . '">' . $child->textContent() . '</a></li>';
            }

            if ( $child->name() == 'section' )
            {
                $tocText .= '<ul>';
                $tocText .= $this->handleSection( $child, $sectionLevel + 1 );
                $tocText .= '</ul>';
            }
        }

        return $tocText;
    }

    var $HeaderCounter = array();
    var $ObjectAttributeId;
}

?>
