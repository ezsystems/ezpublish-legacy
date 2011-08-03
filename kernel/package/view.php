<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$module = $Params['Module'];
$viewMode = $Params['ViewMode'];
$packageName = $Params['PackageName'];
$repositoryID = false;
if ( isset( $Params['RepositoryID'] ) and $Params['RepositoryID'] )
    $repositoryID = $Params['RepositoryID'];

$package = eZPackage::fetch( $packageName, false, $repositoryID );
if ( !is_object( $package ) )
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$package->attribute( 'can_read' ) )
    return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );


if ( $module->isCurrentAction( 'Export' ) )
{
    return $module->run( 'export', array( $packageName ) );
}
else if ( $module->isCurrentAction( 'Install' ) )
{
    return $module->redirectToView( 'install', array( $packageName ) );
}
else if ( $module->isCurrentAction( 'Uninstall' ) )
{
    return $module->redirectToView( 'uninstall', array( $packageName ) );
}

$repositoryInformation = $package->currentRepositoryInformation();

$tpl = eZTemplate::factory();

$tpl->setVariable( 'package_name', $packageName );
$tpl->setVariable( 'repository_id', $repositoryID );

$Result = array();
$Result['content'] = $tpl->fetch( "design:package/view/$viewMode.tpl" );
$path = array( array( 'url' => 'package/list',
                      'text' => ezpI18n::tr( 'kernel/package', 'Packages' ) ) );
if ( $repositoryInformation and $repositoryInformation['id'] != 'local' )
{
    $path[] = array( 'url' => 'package/list/' . $repositoryInformation['id'],
                     'text' => $repositoryInformation['name'] );
}
$path[] = array( 'url' => false,
                 'text' => $package->attribute( 'name' ) );
$Result['path'] = $path;

?>
