<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$ClassID = null;
if ( isset( $Params["ClassID"] ) )
    $ClassID = $Params["ClassID"];
$class = eZContentClass::fetch( $ClassID, true, 0 );
if ( !$class )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE );

$classCopy = clone $class;
$classCopy->initializeCopy( $class );
$classCopy->setAttribute( 'version', eZContentClass::VERSION_STATUS_MODIFIED );
$classCopy->store();

$mainGroupID = false;
$classGroups = eZContentClassClassGroup::fetchGroupList( $class->attribute( 'id' ),
                                                          $class->attribute( 'version' ) );
for ( $i = 0; $i < count( $classGroups ); ++$i )
{
    $classGroup =& $classGroups[$i];
    $classGroup->setAttribute( 'contentclass_id', $classCopy->attribute( 'id' ) );
    $classGroup->setAttribute( 'contentclass_version', $classCopy->attribute( 'version' ) );
    $classGroup->store();
    if ( $mainGroupID === false )
        $mainGroupID = $classGroup->attribute( 'group_id' );
}

$classAttributeCopies = array();
$classAttributes = $class->fetchAttributes();
foreach ( array_keys( $classAttributes ) as $classAttributeKey )
{
    $classAttribute =& $classAttributes[$classAttributeKey];
    $classAttributeCopy = clone $classAttribute;

    if ( $datatype = $classAttributeCopy->dataType() ) //avoiding fatal error if datatype not exist (was removed).
    {
        $datatype->cloneClassAttribute( $classAttribute, $classAttributeCopy );
    }
    else
    {
        continue;
    }

    $classAttributeCopy->setAttribute( 'contentclass_id', $classCopy->attribute( 'id' ) );
    $classAttributeCopy->setAttribute( 'version', eZContentClass::VERSION_STATUS_MODIFIED );
    $classAttributeCopy->store();
    $classAttributeCopies[] =& $classAttributeCopy;
    unset( $classAttributeCopy );
}

$ini = eZINI::instance( 'content.ini' );
$classRedirect = strtolower( trim( $ini->variable( 'CopySettings', 'ClassRedirect' ) ) );

switch ( $classRedirect )
{
    case 'grouplist':
    {
        $classCopy->storeDefined( $classAttributeCopies );
        return $Module->redirectToView( 'grouplist', array() );
    } break;

    case 'classlist':
    {
        $classCopy->storeDefined( $classAttributeCopies );
        return $Module->redirectToView( 'classlist', array( $mainGroupID ) );
    } break;

    case 'classview':
    {
        $classCopy->storeDefined( $classAttributeCopies );
        return $Module->redirectToView( 'view', array( $classCopy->attribute( 'id' ) ) );
    } break;

    default:
    {
        eZDebug::writeWarning( "Invalid ClassRedirect value '$classRedirect', use one of: grouplist, classlist, classedit or classview" );
    }

    case 'classedit':
    {
        return $Module->redirectToView( 'edit', array( $classCopy->attribute( 'id' ) ) );
    } break;
}

?>
