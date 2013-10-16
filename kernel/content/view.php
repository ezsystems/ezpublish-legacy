<?php
/**
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$tpl = eZTemplate::factory();

$ViewMode = $Params['ViewMode'];
$NodeID = $Params['NodeID'];
$Module = $Params['Module'];
$LanguageCode = $Params['Language'];
$Offset = $Params['Offset'];
$Year = $Params['Year'];
$Month = $Params['Month'];
$Day = $Params['Day'];

// Check if we should switch access mode (http/https) for this node.
eZSSLZone::checkNodeID( 'content', 'view', $NodeID );

if ( isset( $Params['UserParameters'] ) )
{
    $UserParameters = $Params['UserParameters'];
}
else
{
    $UserParameters = array();
}

if ( $Offset )
    $Offset = (int) $Offset;
if ( $Year )
    $Year = (int) $Year;
if ( $Month )
    $Month = (int) $Month;
if ( $Day )
    $Day = (int) $Day;

$NodeID = (int) $NodeID;

if ( $NodeID < 2 )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$ini = eZINI::instance();

// Be able to filter node id for general use
$NodeID = ezpEvent::getInstance()->filter( 'content/view', $NodeID, $ini );

$testingHandler = new ezpMultivariateTest( ezpMultivariateTest::getHandler() );

if ( $testingHandler->isEnabled() )
    $NodeID = $testingHandler->execute( $NodeID );

$viewCacheEnabled = ( $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled' );

if ( isset( $Params['ViewCache'] ) )
{
    $viewCacheEnabled = $Params['ViewCache'];
}
elseif ( $viewCacheEnabled && !in_array( $ViewMode, $ini->variableArray( 'ContentSettings', 'CachedViewModes' ) ) )
{
    $viewCacheEnabled = false;
}

if ( $viewCacheEnabled && $ini->hasVariable( 'ContentSettings', 'ViewCacheTweaks' ) )
{
    $viewCacheTweaks = $ini->variable( 'ContentSettings', 'ViewCacheTweaks' );
    if ( isset( $viewCacheTweaks[$NodeID] ) && strpos( $viewCacheTweaks[$NodeID], 'disabled' ) !== false )
    {
        $viewCacheEnabled = false;
    }
}

$collectionAttributes = false;
if ( isset( $Params['CollectionAttributes'] ) )
    $collectionAttributes = $Params['CollectionAttributes'];

$validation = array( 'processed' => false,
                     'attributes' => array() );
if ( isset( $Params['AttributeValidation'] ) )
    $validation = $Params['AttributeValidation'];

$res = eZTemplateDesignResource::instance();
$keys = $res->keys();
if ( isset( $keys['layout'] ) )
    $layout = $keys['layout'];
else
    $layout = false;

$viewParameters = array(
    'offset' => $Offset,
    'year' => $Year,
    'month' => $Month,
    'day' => $Day,
    'namefilter' => false,
    '_custom' => $UserParameters
);
// Keep the following array_merge for BC
// All user parameters will be exposed as direct variables in template.
$viewParameters = array_merge( $viewParameters, $UserParameters );

$user = eZUser::currentUser();

eZDebugSetting::addTimingPoint( 'kernel-content-view', 'Operation start' );


$operationResult = array();

if ( eZOperationHandler::operationIsAvailable( 'content_read' ) )
{
    $operationResult = eZOperationHandler::execute( 'content', 'read', array( 'node_id' => $NodeID,
                                                                              'user_id' => $user->id(),
                                                                              'language_code' => $LanguageCode ), null, true );
}

if ( ( isset( $operationResult['status'] ) && $operationResult['status'] != eZModuleOperationInfo::STATUS_CONTINUE ) )
{
    switch( $operationResult['status'] )
    {
        case eZModuleOperationInfo::STATUS_HALTED:
        case eZModuleOperationInfo::STATUS_REPEAT:
        {
            if ( isset( $operationResult['redirect_url'] ) )
            {
                $Module->redirectTo( $operationResult['redirect_url'] );
                return;
            }
            else if ( isset( $operationResult['result'] ) )
            {
                $result = $operationResult['result'];
                $resultContent = false;
                if ( is_array( $result ) )
                {
                    if ( isset( $result['content'] ) )
                    {
                        $resultContent = $result['content'];
                    }
                    if ( isset( $result['path'] ) )
                    {
                        $Result['path'] = $result['path'];
                    }
                }
                else
                {
                    $resultContent = $result;
                }
                $Result['content'] = $resultContent;
            }
        } break;
        case eZModuleOperationInfo::STATUS_CANCELLED:
        {
            $Result = array();
            $Result['content'] = "Content view cancelled<br/>";
        } break;
    }
    return $Result;
}
else
{
    $args = compact(
        array(
            "NodeID", "Module", "tpl", "LanguageCode", "ViewMode", "Offset", "ini", "viewParameters", "collectionAttributes", "validation"
        )
    );
    if ( $viewCacheEnabled )
    {
        $cacheFileArray = eZNodeviewfunctions::generateViewCacheFile(
            eZUser::currentUser(),
            $NodeID,
            $Offset,
            $layout,
            $LanguageCode,
            $ViewMode,
            $viewParameters,
            false
        );

        $result = eZClusterFileHandler::instance( $cacheFileArray['cache_path'] )
            ->processCache(
                array( 'eZNodeviewfunctions', 'contentViewRetrieve' ),
                array( 'eZNodeviewfunctions', 'contentViewGenerate' ),
                null,
                null,
                $args
            );

        if ( isset( $result['responseHeaders'] ) )
        {
            foreach ( $result['responseHeaders'] as $header )
            {
                header( $header );
            }
        }

        return $result;
    }

    $data = eZNodeviewfunctions::contentViewGenerate( false, $args ); // the false parameter will disable generation of the 'binarydata' entry
    return $data['content']; // Return the $Result array
}

// Looking for some view-cache code?
// Try the eZNodeviewfunctions class for enlightenment.
?>
