<?php
/**
 * File containing the eZDiffEngine class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZDiffEngine ezdiffengine.php
  \abstract
  \ingroup eZDiff
  \brief eZDiff provides an access point the diff system

  The eZDiffEngine class is an abstract class, providing interface and shared code
  for the different available DiffEngine.
*/
class eZDiffEngine
{
    /*!
      This method must be overridden for each implementation of eZDiffEngine. This is the function
      which created the object containing the detected changes in the data set.
    */
    function createDifferenceObject( $fromData, $toData )
    {
    }

    /// \privatesection
    public $DiffMode;
}

?>
