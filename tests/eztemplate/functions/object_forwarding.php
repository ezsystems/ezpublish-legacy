<?php


if ( !class_exists( 'TestContentObject' ) )
{
    class TestContentObject
    {
        function TestContentObject( $id, $name, $attributes )
        {
            $this->ID = $id;
            $this->Name = $name;
            $this->Attributes = $attributes;
        }

        function attributes()
        {
            return array( 'id', 'name', 'attributes' );
        }

        function hasAttribute( $name )
        {
            return in_array( $name, array( 'id', 'name', 'attributes' ) );
        }

        function &attribute( $name )
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
}

if ( !class_exists( 'TestContentObjectTreeNode' ) )
{
    class TestContentObjectTreeNode
    {
        function TestContentObjectTreeNode( $id, $name, &$object )
        {
            $this->ID = $id;
            $this->Name = $name;
            $this->Object =& $object;
            $this->Children = array();
            $object->Node =& $this;
        }

        function attributes()
        {
            return array( 'id', 'name', 'object', 'children' );
        }

        function hasAttribute( $name )
        {
            return in_array( $name, array( 'id', 'name', 'object', 'children' ) );
        }

        function &attribute( $name )
        {
            if ( $name == 'name' )
                return $this->Name;
            else if ( $name == 'object' )
                return $this->Object;
            else if ( $name == 'children' )
                return $this->Children;
            else if ( $name == 'id' )
                return $this->ID;
            return null;
        }

        function addChild( &$node )
        {
            $this->Children[] =& $node;
        }
    }
}

if ( !class_exists( 'TestContentObjectAttribute' ) )
{
    class TestContentObjectAttribute
    {
        function TestContentObjectAttribute( $id, $dataText = false, $dataInt = false )
        {
            $this->ID = $id;
//         $this->DataTypeString = $dataTypeString;
//         $this->Name = $name;
            $this->DataText = $dataText;
            $this->DataInt = $dataInt;
            $this->ClassAttribute = false;
        }

        function attributes()
        {
            return array( 'edit_template', 'view_template',
                          'name', 'contentclass_attribute',
                          'data_type_string',
                          'id', 'data_text', 'data_int' );
        }

        function hasAttribute( $name )
        {
            return in_array( $name, array( 'edit_template', 'view_template',
                                           'name', 'contentclass_attribute',
                                           'data_type_string',
                                           'id', 'data_text', 'data_int' ) );
        }

        function &attribute( $name )
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
}

if ( !class_exists( 'TestContentClassAttribute' ) )
{
    class TestContentClassAttribute
    {
        function TestContentClassAttribute( $id, $dataTypeString, $name, $isInformationCollector )
        {
            $this->ID = $id;
            $this->DataTypeString = $dataTypeString;
            $this->Name = $name;
            $this->IsInformationCollector = $isInformationCollector;
//         $this->DataText = $dataText;
//         $this->DataInt = $dataInt;
        }

        function attributes()
        {
            return array( 'edit_template', 'view_template',
                          'data_type_string',
                          'id', 'name', 'is_information_collector' );
        }

        function hasAttribute( $name )
        {
            return in_array( $name, array( 'edit_template', 'view_template',
                                           'data_type_string',
                                           'id', 'name', 'is_information_collector' ) );
        }

        function &attribute( $name )
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
}

$cattribute1 = new TestContentClassAttribute( 2, 'ezstring', 'Title', false );
$cattribute2 = new TestContentClassAttribute( 3, 'eztext', 'Message', true );
$cattribute3 = new TestContentClassAttribute( 4, 'ezstring', 'Author', true );
$cattribute4 = new TestContentClassAttribute( 5, 'ezstring', 'Author2', false );

$attribute1 = new TestContentObjectAttribute( 5, 'New article' );
$attribute1->ClassAttribute = $cattribute1;
$attribute2 = new TestContentObjectAttribute( 6, 'Cool site' );
$attribute2->ClassAttribute = $cattribute2;
$attribute3 = new TestContentObjectAttribute( 7, 'John Doe' );
$attribute3->ClassAttribute = $cattribute3;
$attribute4 = new TestContentObjectAttribute( 8, 'Arne' );
$attribute4->ClassAttribute = $cattribute4;

$attributes1 = array( $attribute1, $attribute2, $attribute3, $attribute4 );
$object1 = new TestContentObject( 2, 'New article', $attributes1 );
$attributes2 = array();
$object2 = new TestContentObject( 3, 'Sub article', $attributes2 );

$node1 = new TestContentObjectTreeNode( 2, 'New article', $object1 );
$node2 = new TestContentObjectTreeNode( 3, 'Sub article', $object2 );
$node1->addChild( $node2 );

$tpl->setVariable( 'object', $object1 );
$tpl->setVariable( 'node', $node1 );

include_once( 'kernel/common/eztemplatedesignresource.php' );
$designResource =& eZTemplateDesignResource::instance();
$designResource->setKeys( array( array( 'object' => $object1->attribute( 'id' ) ),
                                 array( 'section', 2 ),
                                 array( 'node' , 42 ) ) );


?>
