<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$http = eZHTTPTool::instance();

$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

$user = eZUser::currentUser();
$userID = $user->id();

if ( $Module->isCurrentAction( 'Remove' )  )
{
    if ( $Module->hasActionParameter( 'DeleteIDArray' ) )
    {
        $deleteIDArray = $Module->actionParameter( 'DeleteIDArray' );

        foreach ( $deleteIDArray as $deleteID )
        {
            $bookmark = eZContentBrowseBookmark::fetch( $deleteID );
            if ( $bookmark === null )
                continue;
            if ( $bookmark->attribute( 'user_id' ) == $userID )
                $bookmark->remove();
        }
    }
    if ( $http->hasPostVariable( 'NeedRedirectBack' ) )
    {
        return $Module->redirectTo( $http->postVariable( 'RedirectURI', $http->sessionVariable( 'LastAccessesURI', '/' ) ) );
    }
}
else if ( $Module->isCurrentAction( 'Add' )  )
{
    return eZContentBrowse::browse( array( 'action_name' => 'AddBookmark',
                                           'description_template' => 'design:content/browse_bookmark.tpl',
                                           'from_page' => "/content/bookmark" ),
                                    $Module );
}
else if ( $Module->isCurrentAction( 'AddBookmark' )  )
{
    $nodeList = eZContentBrowse::result( 'AddBookmark' );
    if ( $nodeList )
    {
        $db = eZDB::instance();
        $db->begin();
        foreach ( $nodeList as $nodeID )
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
            if ( $node )
            {
                $nodeName = $node->attribute( 'name' );
                eZContentBrowseBookmark::createNew( $userID, $nodeID, $nodeName );
            }
        }
        $db->commit();
    }
}

$tpl = eZTemplate::factory();
$tpl->setVariable('view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:content/bookmark.tpl' );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'My bookmarks' ),
                                'url' => false ) );


?>
