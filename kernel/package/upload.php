<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$module = $Params['Module'];

if ( !eZPackage::canUsePolicyFunction( 'import' ) )
    return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

$package = false;
$installElements = false;
$errorList = array();

if ( $module->isCurrentAction( 'UploadPackage' ) )
{
    if ( eZHTTPFile::canFetch( 'PackageBinaryFile' ) )
    {
        $file = eZHTTPFile::fetch( 'PackageBinaryFile' );
        if ( $file )
        {
            $packageFilename = $file->attribute( 'filename' );

            $package = eZPackage::import( $packageFilename, $packageName );
            if ( $package instanceof eZPackage )
            {
                if ( $package->attribute( 'install_type' ) != 'install' or
                     !$package->attribute( 'can_install' ) )
                {
                    return $module->redirectToView( 'view', array( 'full', $package->attribute( 'name' ) ) );
                }
                else if ( $package->attribute( 'install_type' ) == 'install' )
                {
                    return $module->redirectToView( 'install', array( $package->attribute( 'name' ) ) );
                }
            }
            else if ( $package == eZPackage::STATUS_ALREADY_EXISTS )
            {
                $errorList[] = array( 'description' => ezpI18n::tr( 'kernel/package', 'Package %packagename already exists, cannot import the package', false, array( '%packagename' => $packageName ) ) );
            }
            else if ( $package == eZPackage::STATUS_INVALID_NAME )
            {
                $errorList[] = array( 'description' => ezpI18n::tr( 'kernel/package', 'The package name %packagename is invalid, cannot import the package', false, array( '%packagename' => $packageName ) ) );
            }
            else
            {
                eZDebug::writeError( "Uploaded file is not an eZ Publish package" );
            }
        }
        else
        {
            eZDebug::writeError( "Failed fetching upload package file" );
        }
    }
    else
    {
        eZDebug::writeError( "No uploaded package file was found" );
    }
}
else if ( $module->isCurrentAction( 'UploadCancel' ) )
{
    $module->redirectToView( 'list' );
    return;
}

$tpl = eZTemplate::factory();

$tpl->setVariable( 'package', $package );
$tpl->setVariable( 'error_list', $errorList );

$Result = array();
$Result['content'] = $tpl->fetch( "design:package/upload.tpl" );
$Result['path'] = array( array( 'url' => 'package/list',
                                'text' => ezpI18n::tr( 'kernel/package', 'Packages' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/package', 'Upload' ) ) );

?>
