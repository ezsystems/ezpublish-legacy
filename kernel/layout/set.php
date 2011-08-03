<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$LayoutStyle = $Params['LayoutStyle'];
$Module = $Params['Module'];

$userParamString = '';
foreach ( $Params['UserParameters'] as $key => $param )
{
    $userParamString .= "/($key)/$param";
}

$Result = array();
$Result['content'] = '';
$Result['rerun_uri'] = '/' . implode( '/', array_splice( $Params['Parameters'], 1 ) ) . $userParamString;

$layoutINI = eZINI::instance( 'layout.ini' );
$i18nINI = eZINI::instance( 'i18n.ini' );
if ( $layoutINI->hasGroup( $LayoutStyle ) )
{
    if ( $layoutINI->hasVariable( $LayoutStyle, 'PageLayout' ) )
        $Result['pagelayout'] = $layoutINI->variable( $LayoutStyle, 'PageLayout' );

    if ( $layoutINI->hasVariable( $LayoutStyle, 'ContentType' ) )
        header( 'Content-Type: ' . $layoutINI->variable( $LayoutStyle, 'ContentType' ) . '; charset=' . $i18nINI->variable( 'CharacterSettings', 'Charset' ) );

    $res = eZTemplateDesignResource::instance();
    $res->setKeys( array( array( 'layout', $LayoutStyle ) ) );

    if ( $layoutINI->hasVariable( $LayoutStyle, 'UseAccessPass' ) && $layoutINI->variable( $LayoutStyle, 'UseAccessPass' ) == 'false' )
    {
    }
    else
    {
        eZSys::addAccessPath( array( 'layout', 'set', $LayoutStyle ), 'layout', false );
    }



    $useFullUrl = false;
    $http = eZHTTPTool::instance();
    $http->UseFullUrl = false;
    if ( $layoutINI->hasVariable( $LayoutStyle, 'UseFullUrl' ) )
    {
        if ( $layoutINI->variable( $LayoutStyle, 'UseFullUrl' ) == 'true' )
        {
            $http->UseFullUrl = true;
        }
    }

    $Module->setExitStatus( eZModule::STATUS_RERUN );
}
else
{
    eZDebug::writeError( 'No such layout style: ' . $LayoutStyle, 'layout/set' );
}

?>
