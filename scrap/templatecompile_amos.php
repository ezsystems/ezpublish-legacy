<?php

include_once( 'lib/eztemplate/classes/eztemplate.php' );
include_once( 'lib/ezutils/classes/ezdebugsetting.php' );

$tpl =& eZTemplate::instance();
$tpl->setAutoloadPathList( array( 'lib/eztemplate/classes',
                                  'kernel/common',
                                  '.' ) );
include_once( 'kernel/common/eztemplatedesignresource.php' );
$tpl->registerResource( eZTemplateDesignResource::instance() );
$tpl->registerResource( eZTemplateDesignResource::standardInstance() );
$designResource =& eZTemplateDesignResource::instance();
//eZTemplateDesignResource::setDesignStartPath( "scrap/design" );

error_reporting ( E_ALL );
eZDebug::setHandleType( EZ_HANDLE_NONE );

$tpl->autoload();

class ContentObject
{
    function ContentObject( $id, $name, $attributes )
    {
        $this->ID = $id;
        $this->Name = $name;
        $this->Attributes = $attributes;
    }

    function hasAttribute( $name )
    {
        return in_array( $name, array( 'id', 'name', 'attributes' ) );
    }

    function attribute( $name )
    {
        if ( $name == 'name' )
            return $this->Name;
        else if ( $name == 'attributes' )
            return $this->Attributes;
        else if ( $name == 'id' )
            return $this->ID;
        return null;
    }
}

class ContentObjectAttribute
{
    function ContentObjectAttribute( $id, $dataText = false, $dataInt = false )
    {
        $this->ID = $id;
//         $this->DataTypeString = $dataTypeString;
//         $this->Name = $name;
        $this->DataText = $dataText;
        $this->DataInt = $dataInt;
        $this->ClassAttribute = false;
    }

    function hasAttribute( $name )
    {
        return in_array( $name, array( 'edit_template', 'view_template',
                                       'name', 'contentclass_attribute',
                                       'data_type_string',
                                       'id', 'data_text', 'data_int' ) );
    }

    function attribute( $name )
    {
        if ( $name == 'edit_template' )
            return $this->ClassAttribute->attribute( 'data_type_string' );
        else if ( $name == 'view_template' )
            return $this->ClassAttribute->attribute( 'data_type_string' );
        else if ( $name == 'name' )
            return $this->ClassAttribute->attribute( 'name' );
        else if ( $name == 'contentclass_attribute' )
            return $this->ClassAttribute;
        else if ( $name == 'id' )
            return $this->ID;
        else if ( $name == 'data_text' )
            return $this->DataText;
        else if ( $name == 'data_int' )
            return $this->DataInt;
        return null;
    }
}

class ContentClassAttribute
{
    function ContentClassAttribute( $id, $dataTypeString, $name, $isInformationCollector )
    {
        $this->ID = $id;
        $this->DataTypeString = $dataTypeString;
        $this->Name = $name;
        $this->IsInformationCollector = $isInformationCollector;
//         $this->DataText = $dataText;
//         $this->DataInt = $dataInt;
    }

    function hasAttribute( $name )
    {
        return in_array( $name, array( 'edit_template', 'view_template',
                                       'data_type_string',
                                       'id', 'name', 'is_information_collector' ) );
    }

    function attribute( $name )
    {
        if ( $name == 'edit_template' )
            return $this->DataTypeString;
        else if ( $name == 'view_template' )
            return $this->DataTypeString;
        else if ( $name == 'data_type_string' )
            return $this->DataTypeString;
        else if ( $name == 'name' )
            return $this->Name;
        else if ( $name == 'id' )
            return $this->ID;
        else if ( $name == 'is_information_collector' )
            return $this->IsInformationCollector;
//         else if ( $name == 'data_text' )
//             return $this->DataText;
//         else if ( $name == 'data_int' )
//             return $this->DataInt;
        return null;
    }
}

$cattribute1 = new ContentClassAttribute( 2, 'ezstring', 'Title', false );
$cattribute2 = new ContentClassAttribute( 3, 'eztext', 'Message', true );

$attribute1 = new ContentObjectAttribute( 5, 'New article' );
$attribute1->ClassAttribute = $cattribute1;
$attribute2 = new ContentObjectAttribute( 6, 'Cool site' );
$attribute2->ClassAttribute = $cattribute2;

$attributes1 = array( $attribute1, $attribute2 );
$object1 = new ContentObject( 2, 'New article', $attributes1 );

$tpl->setVariable( 'object', $object1 );

$designResource->setKeys( array( array( 'object' => $object1->attribute( 'id' ) ),
                                 array( 'section', 2 ),
                                 array( 'node' , 42 ) ) );

print( $tpl->fetch( 'scrap/templatecompile_amos.tpl' ) . "\n" );

// include_once( 'lib/ezutils/classes/ezphpcreator.php' );
// $php = new eZPHPCreator( '.', 'test.php' );
// $uri = "a.tpl";
// $resourceData = $tpl->resourceFor( $uri, $resource, $template );

// $parameters = array();

// $nodes = array();

// $tree = array( EZ_TEMPLATE_NODE_ROOT,
//                $nodes );

// eZTemplateCompiler::generatePHPCode( true, $php, $tpl, $tree, $resourceData );

// $php->store();

?>
