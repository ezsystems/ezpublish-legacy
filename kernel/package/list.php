<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$module = $Params['Module'];
$offset = (int)$Params['Offset'];

$repositoryID = 'local';
if ( $Params['RepositoryID'] )
    $repositoryID = $Params['RepositoryID'];

if ( $module->isCurrentAction( 'InstallPackage' ) )
{
    return $module->redirectToView( 'upload' );
}

$removeList = array();
if ( $module->isCurrentAction( 'RemovePackage' ) or
     $module->isCurrentAction( 'ConfirmRemovePackage' ) )
{
    if ( $module->hasActionParameter( 'PackageSelection' ) )
    {
        $removeConfirmation = $module->isCurrentAction( 'ConfirmRemovePackage' );
        $packageSelection = $module->actionParameter( 'PackageSelection' );
        foreach ( $packageSelection as $packageID )
        {
            $package = eZPackage::fetch( $packageID );
            if ( $package )
            {
                if ( $removeConfirmation )
                {
                    $package->remove();
                }
                else
                {
                    $removeList[] = $package;
                }
            }
        }
        if ( $removeConfirmation )
            return $module->redirectToView( 'list' );
    }
}

if ( $module->isCurrentAction( 'ChangeRepository' ) )
{
    $repositoryID = $module->actionParameter( 'RepositoryID' );
}

if ( $module->isCurrentAction( 'CreatePackage' ) )
{
    return $module->redirectToView( 'create' );
}

$tpl = eZTemplate::factory();

$viewParameters = array( 'offset' => $offset );

$tpl->setVariable( 'module_action', $module->currentAction() );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'remove_list', $removeList );
$tpl->setVariable( 'repository_id', $repositoryID );

$Result = array();
$Result['content'] = $tpl->fetch( "design:package/list.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/package', 'Packages' ) ) );

?>
