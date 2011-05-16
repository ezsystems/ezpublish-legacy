<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$Offset = $Params['Offset'];
if ( isset( $Params['UserParameters'] ) )
{
    $UserParameters = $Params['UserParameters'];
}
else
{
    $UserParameters = array();
}
$viewParameters = array( 'offset' => $Offset, 'namefilter' => false );
$viewParameters = array_merge( $viewParameters, $UserParameters );

$http = eZHTTPTool::instance();

$user = eZUser::currentUser();
$userID = $user->id();

if ( $http->hasPostVariable( 'RemoveButton' )  )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $access = $user->hasAccessTo( 'content', 'cleantrash' );
        if ( $access['accessWord'] == 'yes' || $access['accessWord'] == 'limited' )
        {
            $deleteIDArray = $http->postVariable( 'DeleteIDArray' );

            foreach ( $deleteIDArray as $deleteID )
            {

                $objectList = eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                                   null,
                                                                   array( 'id' => $deleteID ),
                                                                   null,
                                                                   null,
                                                                   true );
                foreach ( $objectList as $object )
                {
                    $object->purge();
                }
            }
        }
        else
        {
            return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
        }
    }
}
else if ( $http->hasPostVariable( 'EmptyButton' )  )
{
    $access = $user->hasAccessTo( 'content', 'cleantrash' );
    if ( $access['accessWord'] == 'yes' || $access['accessWord'] == 'limited' )
    {
        while ( true )
        {
            // Fetch 100 objects at a time, to limit transaction size
            $objectList = eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                               null,
                                                               array( 'status' => eZContentObject::STATUS_ARCHIVED ),
                                                               null,
                                                               100,
                                                               true );
            if ( count( $objectList ) < 1 )
                break;

            foreach ( $objectList as $object )
            {
                $object->purge();
            }
        }
    }
    else
    {
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }
}

$tpl = eZTemplate::factory();
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:content/trash.tpl' );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Trash' ),
                                'url' => false ) );


?>
