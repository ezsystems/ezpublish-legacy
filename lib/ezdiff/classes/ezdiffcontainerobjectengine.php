<?php
/**
 * File containing the eZDiffContainerObjectEngine class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZDiffContainerObjectEngine ezdiffcontainerobjectengine.php
  \ingroup eZDiff
  \brief Creates an object containing two versions of a content object.

  The eZDiffEngine class is an abstract class, providing interface and shared code
  for the different available DiffEngine.
*/

class eZDiffContainerObjectEngine extends eZDiffEngine
{
    function eZDiffContainerObjectEngine()
    {
    }

    /*!
      Create containerobject containig content from two versions
    */
    function createDifferenceObject( $old, $new )
    {
        $container = new eZDiffContainerObject();
        $container->setOldContent( $old );
        $container->setNewContent( $new );

        return $container;
    }
}

?>
