<?php


if ( !class_exists( 'TestContentClass' ) )
{
    class TestContentClass
    {
        function TestContentClass( $id, $identifier, $name, $attributes )
        {
            $this->ID = $id;
            $this->Identifier = $identifier;
            $this->Name = $name;
            $this->Attributes = $attributes;
        }

        function attributes()
        {
            return array( 'id', 'name', 'attributes', 'identifier'
                          );
        }

        function hasAttribute( $name )
        {
            return in_array( $name, array( 'id', 'name', 'attributes', 'identifier'
                                           ) );
        }

        function &attribute( $name )
        {
            if ( $name == 'name' )
                return $this->Name;
            else if ( $name == 'attributes' )
                return $this->Attributes;
            else if ( $name == 'identifier' )
                return $this->Identifier;
            else if ( $name == 'id' )
                return $this->ID;
            return null;
        }
    }
}

if ( !class_exists( 'TestContentObject' ) )
{
    class TestContentObject
    {
        function TestContentObject( $id, $name, $section, $attributes, &$class )
        {
            $this->ID = $id;
            $this->Name = $name;
            $this->Section = $section;
            $this->Class =& $class;
            $this->Attributes = $attributes;
        }

        function attributes()
        {
            return array( 'id', 'name', 'attributes',
                          'section_id', 'contentclass_id', 'class_identifier'
                          );
        }

        function hasAttribute( $name )
        {
            return in_array( $name, array( 'id', 'name', 'attributes',
                                           'section_id', 'contentclass_id', 'class_identifier'
                                           ) );
        }

        function &attribute( $name )
        {
            if ( $name == 'name' )
                return $this->Name;
            else if ( $name == 'attributes' )
                return $this->Attributes;
            else if ( $name == 'section_id' )
                return $this->Section;
            else if ( $name == 'contentclass_id' )
                return $this->Class->ID;
            else if ( $name == 'class_identifier' )
                return $this->Class->Identifier;
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
            return array( 'node_id', 'name', 'object', 'children', 'contentobject_id' );
        }

        function hasAttribute( $name )
        {
            return in_array( $name, array( 'node_id', 'name', 'object', 'children', 'contentobject_id' ) );
        }

        function &attribute( $name )
        {
            if ( $name == 'name' )
                return $this->Name;
            else if ( $name == 'object' )
                return $this->Object;
            else if ( $name == 'children' )
                return $this->Children;
            else if ( $name == 'contentobject_id' )
            {
                return $this->Object->ID;
            }
            else if ( $name == 'node_id' )
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
                                           'section_id', 'contentclass_id', 'class_identifier',
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

$cattributes1 = array( $cattribute1, $cattribute2, $cattribute3, $cattribute4 );
$class1 = new TestContentClass( 1, 'article', 'Article', $cattributes1 );

$cattribute2_1 = new TestContentClassAttribute( 12, 'ezstring', 'Title', false );
$cattribute2_2 = new TestContentClassAttribute( 13, 'eztext', 'Body', false );

$cattributes2 = array( $cattribute2_1, $cattribute2_2 );
$class2 = new TestContentClass( 2, 'folder', 'Folder', $cattributes2 );

$attribute1 = new TestContentObjectAttribute( 5, 'New article' );
$attribute1->ClassAttribute = $cattribute1;
$attribute2 = new TestContentObjectAttribute( 6, 'Cool site' );
$attribute2->ClassAttribute = $cattribute2;
$attribute3 = new TestContentObjectAttribute( 7, 'John Doe' );
$attribute3->ClassAttribute = $cattribute3;
$attribute4 = new TestContentObjectAttribute( 8, 'Arne' );
$attribute4->ClassAttribute = $cattribute4;

$attribute2_1 = new TestContentObjectAttribute( 15, 'New article' );
$attribute2_1->ClassAttribute = $cattribute1;
$attribute2_2 = new TestContentObjectAttribute( 16, 'Cool site' );
$attribute2_2->ClassAttribute = $cattribute2;
$attribute2_3 = new TestContentObjectAttribute( 17, 'John Doe' );
$attribute2_3->ClassAttribute = $cattribute3;
$attribute2_4 = new TestContentObjectAttribute( 18, 'Arne' );
$attribute2_4->ClassAttribute = $cattribute4;

$attribute3_1 = new TestContentObjectAttribute( 25, 'New article' );
$attribute3_1->ClassAttribute = $cattribute1;
$attribute3_2 = new TestContentObjectAttribute( 26, 'Cool site' );
$attribute3_2->ClassAttribute = $cattribute2;
$attribute3_3 = new TestContentObjectAttribute( 27, 'John Doe' );
$attribute3_3->ClassAttribute = $cattribute3;
$attribute3_4 = new TestContentObjectAttribute( 28, 'Arne' );
$attribute3_4->ClassAttribute = $cattribute4;

$attributes1 = array( $attribute1, $attribute2, $attribute3, $attribute4 );
$object1 = new TestContentObject( 2, 'New article', 1, $attributes1, $class1 );
$attributes2 = array( $attribute2_1, $attribute2_2, $attribute2_3, $attribute2_4 );
$object2 = new TestContentObject( 3, 'Sub article', 1, $attributes2, $class1 );
$attributes3 = array( $attribute3_1, $attribute3_2, $attribute3_3, $attribute3_4 );
$object3 = new TestContentObject( 4, 'Sub folder', 1, $attributes3, $class2 );

$node1 = new TestContentObjectTreeNode( 2, 'New article', $object1 );
$node2 = new TestContentObjectTreeNode( 3, 'Sub article', $object2 );
$node1->addChild( $node2 );
$node3 = new TestContentObjectTreeNode( 4, 'Sub folder', $object3 );
$node1->addChild( $node3 );

$tpl->setVariable( 'object', $object1 );
$tpl->setVariable( 'node', $node1 );

include_once( 'kernel/common/eztemplatedesignresource.php' );
$designResource =& eZTemplateDesignResource::instance();
$designResource->setKeys( array( array( 'object' => $object1->attribute( 'id' ) ),
                                 array( 'section', $object1->attribute( 'section_id' ) ),
                                 array( 'node' , $node1->attribute( 'node_id' ) ) ) );
if ( !isset( $GLOBALS['TestTemplateOverride'] ) )
{
    $GLOBALS['TestTemplateOverride'] = true;
    eZTemplateDesignResource::addGlobalOverride( 'folder', 'node/view/line.tpl', 'folder.tpl', 'templates', array( 'class' => 2 ) );
//    eZTemplateDesignResource::addGlobalOverride( 'folder3', 'node/view/listitem.tpl', 'folder2.tpl', 'templates', array( 'class' => 5 ) );
    eZTemplateDesignResource::addGlobalOverride( 'folder2', 'node/view/listitem.tpl', 'folder2.tpl', 'templates', array() );
}


?>
