<?php
/**
 * File containing the eZTemplateRoot class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*! \defgroup eZTemplateElements Template elements
    \ingroup eZTemplate
*/

/*!
  \class eZTemplateRoot eztemplateroot.php
  \ingroup eZTemplateElements
  \brief Represents a root element of the template tree.

  This starts the template tree and is the base of template includes.

  It has a list of child elements and runs process() on each child.
*/

class eZTemplateRoot
{
    /*!
     Initializes the object.
    */
    function eZTemplateRoot( $children = array() )
    {
        $this->Children = $children;
    }

    /*!
     Returns #root as the name.
    */
    function name()
    {
        return "#root";
    }

    function serializeData()
    {
        return array( 'class_name' => 'eZTemplateRoot',
                      'parameters' => array( 'children' ),
                      'variables' => array( 'children' => 'Children' ) );
    }

    /*!
     Runs process() on all child elements.
    */
    function process( $tpl, &$text, $nspace, $current_nspace )
    {
        foreach( array_keys( $this->Children ) as $key )
        {
            $this->Children[$key]->process( $tpl, $text, $nspace, $current_nspace );
        }
    }

    /*!
     Removes all children.
    */
    function clear()
    {
        $this->Children = array();
    }

    /*!
     Returns a reference to the child array.
    */
    function &children()
    {
        return $this->Children;
    }

    /*!
     Appends the child $node to the child array.
    */
    function appendChild( &$node )
    {
        $this->Children[] =& $node;
    }

    /// The child array
    public $Children = array();
}

?>
