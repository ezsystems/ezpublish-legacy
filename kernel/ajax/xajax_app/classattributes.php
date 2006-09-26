<?php

function addClassAttribute( $classID, $datatypeString )
{
    $objResponse = new xajaxResponse();

    include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

    $user =& eZUser::currentUser();
    $accessResult = $user->hasAccessTo( 'class', 'edit' );

    if ( $accessResult['accessWord'] == 'no' )
    {
        $objResponse->addAlert( 'You are not allowed to edit content classes' );
        return $objResponse->getXML();
    }

    include_once( 'kernel/classes/ezcontentclass.php' );
    $class = eZContentClass::fetch( $classID, true, EZ_CLASS_VERSION_STATUS_TEMPORARY );

    if ( !is_object( $class ) or $class->attribute( 'id' ) == null )
    {
        $objResponse->addAlert( 'Unable to find the temporary version of the class.' );
        return $objResponse->getXML();
    }
    else
    {
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );

        include_once( 'lib/ezutils/classes/ezini.php' );
        $contentIni =& eZIni::instance( 'content.ini' );
        $timeOut = $contentIni->variable( 'ClassSettings', 'DraftTimeout' );

        if ( $class->attribute( 'modifier_id' ) != $user->attribute( 'contentobject_id' ) &&
             $class->attribute( 'modified' ) + $timeOut > time() )
        {
            $message = 'This class is already being edited by someone else.';
            $message = $message . ' The class is temporarly locked and thus it can not be edited by you.';

            $objResponse->addAlert( $message );
            return $objResponse->getXML();
        }
    }

    $existingAttributes =& eZContentClass::fetchAttributes( $classID, false, EZ_CLASS_VERSION_STATUS_TEMPORARY );

    $number = count( $existingAttributes ) + 1;

    include_once( 'kernel/classes/ezdatatype.php' );
    eZDataType::loadAndRegisterAllTypes();

    $new_attribute = eZContentClassAttribute::create( $classID, $datatypeString );
    $new_attribute->setAttribute( 'name', ezi18n( 'kernel/class/edit', 'new attribute' ) . $number );
    $dataType = $new_attribute->dataType();
    $dataType->initializeClassAttribute( $new_attribute );
    $new_attribute->store();

    include_once( 'kernel/common/template.php' );
    $tpl =& templateInit();

    $tpl->setVariable( 'attribute', $new_attribute );
    $tpl->setVariable( 'number', $number );

    $header1 =& $tpl->fetch( 'design:class/edit_xajax_attribute_header_1.tpl' );
    $header2 =& $tpl->fetch( 'design:class/edit_xajax_attribute_header_2.tpl' );
    $header3 =& $tpl->fetch( 'design:class/edit_xajax_attribute_header_3.tpl' );

    $cell2 =& $tpl->fetch( 'design:class/edit_xajax_attribute_cell_2.tpl' );

    $objResponse->addScriptCall( 'addNewAttributeRows', $new_attribute->attribute( 'id' ) );

    $objResponse->addAssign( 'newHeader' . $new_attribute->attribute( 'id' ) . '_1', 'innerHTML', $header1 );
    $objResponse->addAssign( 'newHeader' . $new_attribute->attribute( 'id' ) . '_2', 'innerHTML', $header2 );
    $objResponse->addAssign( 'newHeader' . $new_attribute->attribute( 'id' ) . '_3', 'innerHTML', $header3 );

    $objResponse->addAssign( 'newCell' . $new_attribute->attribute( 'id' ) . '_2', 'innerHTML', $cell2 );

    return $objResponse->getXML();
}

function moveClassAttribute( $attributeID, $direction )
{
    $objResponse = new xajaxResponse();

    include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

    $user =& eZUser::currentUser();
    $accessResult = $user->hasAccessTo( 'class', 'edit' );

    if ( $accessResult['accessWord'] == 'no' )
    {
        $objResponse->addAlert( 'You are not allowed to edit content classes' );
        return $objResponse->getXML();
    }

    $attribute =& eZContentClassAttribute::fetch( $attributeID, true, EZ_CLASS_VERSION_STATUS_TEMPORARY,
                                                  array( 'contentclass_id', 'version', 'placement' ) );

    if ( !$attribute )
    {
        $objResponse->addAlert( 'Unable to fetch the class attribute.' );
        return $objResponse->getXML();
    }

    $classID = $attribute->attribute( 'contentclass_id' );

    include_once( 'kernel/classes/ezcontentclass.php' );
    $class = eZContentClass::fetch( $classID, true, EZ_CLASS_VERSION_STATUS_TEMPORARY );

    if ( !is_object( $class ) or $class->attribute( 'id' ) == null )
    {
        $objResponse->addAlert( 'Unable to find the temporary version of the class.' );
        return $objResponse->getXML();
    }
    else
    {
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );

        include_once( 'lib/ezutils/classes/ezini.php' );
        $contentIni =& eZIni::instance( 'content.ini' );
        $timeOut = $contentIni->variable( 'ClassSettings', 'DraftTimeout' );

        if ( $class->attribute( 'modifier_id' ) != $user->attribute( 'contentobject_id' ) &&
             $class->attribute( 'modified' ) + $timeOut > time() )
        {
            $message = 'This class is already being edited by someone else.';
            $message = $message . ' The class is temporarly locked and thus it can not be edited by you.';

            $objResponse->addAlert( $message );
            return $objResponse->getXML();
        }
    }

    $attribute->move( $direction );
    $objResponse->addScriptCall( 'moveAttributeRows', $attributeID, $direction );

    return $objResponse->getXML();

}

?>
