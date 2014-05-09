<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$roleID = $Params['RoleID'];

$role = eZRole::fetch( $roleID );
if ( $role )
{
    $newRole = $role->copy();
    return $Module->redirectToView( 'edit', array( $newRole->attribute( 'id' ) ) );
}
else
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

?>
