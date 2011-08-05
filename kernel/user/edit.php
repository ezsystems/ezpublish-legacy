<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];

if ( isset( $Params['UserID'] ) && is_numeric( $Params['UserID'] ) )
{
    $UserID = $Params['UserID'];
}
else
{
    $currentUser = eZUser::currentUser();
    $UserID      = $currentUser->attribute( 'contentobject_id' );
    if ( $currentUser->isAnonymous() )
    {
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }
}

if ( isset( $Params['UserParameters'] ) )
{
    $UserParameters = $Params['UserParameters'];
}
else
{
    $UserParameters = array();
}

if ( $Module->isCurrentAction( "ChangePassword" ) )
{
    return $Module->redirectTo( "user/password/" . $UserID  );
}

if ( $Module->isCurrentAction( "ChangeSetting" ) )
{
    return $Module->redirectTo( "user/setting/" . $UserID );
}

if ( $Module->isCurrentAction( "Cancel" ) )
{
    return $Module->redirectTo( '/content/view/sitemap/5/' );
}

$http = eZHTTPTool::instance();

if ( $Module->isCurrentAction( "Edit" ) || ( isset( $UserParameters['action'] ) && $UserParameters['action'] === 'edit' ) )
{
    $selectedVersion = $http->hasPostVariable( 'SelectedVersion' ) ? $http->postVariable( 'SelectedVersion' ) : 'f';
    $editLanguage = $http->hasPostVariable( 'ContentObjectLanguageCode' ) ? $http->postVariable( 'ContentObjectLanguageCode' ) : '';
    return $Module->redirectTo( '/content/edit/' . $UserID . '/' . $selectedVersion . '/' . $editLanguage );
}

$userAccount = eZUser::fetch( $UserID );
if ( !$userAccount )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$userObject = $userAccount->attribute( 'contentobject' );
if ( !$userObject instanceof eZContentObject  )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

if ( !$userObject->canEdit( ) )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}


$tpl = eZTemplate::factory();
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "http", $http );
$tpl->setVariable( "userID", $UserID );
$tpl->setVariable( "userAccount", $userAccount );
$tpl->setVariable( 'view_parameters', $UserParameters );
$tpl->setVariable( 'site_access', $GLOBALS['eZCurrentAccess'] );

$Result = array();
$Result['content'] = $tpl->fetch( "design:user/edit.tpl" );
$Result['path'] = array( array( 'text' =>  ezpI18n::tr( 'kernel/user', 'User profile' ),
                                'url' => false ) );


?>
