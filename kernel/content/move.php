<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  This script is just a wrapper for action.php with action set to 'MoveNodeRequest'
  and has been created for moving operation to be simply invoked using URI like /content/move/NODE_ID.
*/


$Module = $Params['Module'];
$NodeID = $Params['NodeID'];

$Module->setCurrentAction( 'MoveNodeRequest', 'action' );
$Module->setActionParameter( 'NodeID', $NodeID, 'action' );
return $Module->run( 'action', array( $NodeID ) );

?>
