<?php
/**
 * File containing the eZDiffContainerObjectEngine class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
