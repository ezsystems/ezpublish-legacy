<?php

if ( !class_exists( 'NestedTestContentClass' ) )
{
    class NestedTestContentClass
    {
        function NestedTestContentClass( $id, $identifier, $name, $attributes )
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

if ( !class_exists( 'NestedTestContentObject' ) )
{
    class NestedTestContentObject
    {
        function NestedTestContentObject( $id, $name, $section, $attributes, &$class, $list = array() )
        {
            $this->ID = $id;
            $this->Name = $name;
            $this->Section = $section;
            $this->Class =& $class;
            $this->Attributes = $attributes;
            $this->List = $list;
        }

        function attributes()
        {
            return array( 'id', 'name', 'attributes',
                          'list',
                          'section_id', 'contentclass_id', 'class_identifier'
                          );
        }

        function hasAttribute( $name )
        {
            return in_array( $name, array( 'id', 'name', 'attributes',
                                           'list',
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
            else if ( $name == 'list' )
                return $this->List;
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

if ( !class_exists( 'NestedTestContentObjectTreeNode' ) )
{
    class NestedTestContentObjectTreeNode
    {
        function NestedTestContentObjectTreeNode( $id, $name, &$object )
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

if ( !class_exists( 'NestedTestContentObjectAttribute' ) )
{
    class NestedTestContentObjectAttribute
    {
        function NestedTestContentObjectAttribute( $id, $dataText = false, $dataInt = false )
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

if ( !class_exists( 'NestedTestContentClassAttribute' ) )
{
    class NestedTestContentClassAttribute
    {
        function NestedTestContentClassAttribute( $id, $dataTypeString, $name, $isInformationCollector )
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

$cattribute1 = new NestedTestContentClassAttribute( 2, 'ezstring', 'Title', false );
$cattribute2 = new NestedTestContentClassAttribute( 3, 'eztext', 'Message', true );
$cattribute3 = new NestedTestContentClassAttribute( 4, 'ezstring', 'Author', true );
$cattribute4 = new NestedTestContentClassAttribute( 5, 'ezstring', 'Author2', false );
$cattribute5 = new NestedTestContentClassAttribute( 6, 'ezfile', 'File', false );

$cattributes1 = array( $cattribute1, $cattribute2, $cattribute3, $cattribute4, $cattribute5 );
$class1 = new NestedTestContentClass( 1, 'article', 'Article', $cattributes1 );

$cattribute2_1 = new NestedTestContentClassAttribute( 12, 'ezstring', 'Title', false );
$cattribute2_2 = new NestedTestContentClassAttribute( 13, 'eztext', 'Body', false );

$cattributes2 = array( $cattribute2_1, $cattribute2_2 );
$class2 = new NestedTestContentClass( 2, 'folder', 'Folder', $cattributes2 );

$attribute1 = new NestedTestContentObjectAttribute( 5, 'New article' );
$attribute1->ClassAttribute = $cattribute1;
$attribute2 = new NestedTestContentObjectAttribute( 6, 'Cool site' );
$attribute2->ClassAttribute = $cattribute2;
$attribute3 = new NestedTestContentObjectAttribute( 7, 'John Doe' );
$attribute3->ClassAttribute = $cattribute3;
$attribute4 = new NestedTestContentObjectAttribute( 8, 'Arne' );
$attribute4->ClassAttribute = $cattribute4;
$attribute5 = new NestedTestContentObjectAttribute( 9, 'info.pdf' );
$attribute5->ClassAttribute = $cattribute5;

$attribute2_1 = new NestedTestContentObjectAttribute( 15, 'New article' );
$attribute2_1->ClassAttribute = $cattribute1;
$attribute2_2 = new NestedTestContentObjectAttribute( 16, 'Cool site' );
$attribute2_2->ClassAttribute = $cattribute2;
$attribute2_3 = new NestedTestContentObjectAttribute( 17, 'John Doe' );
$attribute2_3->ClassAttribute = $cattribute3;
$attribute2_4 = new NestedTestContentObjectAttribute( 18, 'Arne' );
$attribute2_4->ClassAttribute = $cattribute4;
$attribute2_5 = new NestedTestContentObjectAttribute( 19, 'test.pdf' );
$attribute2_5->ClassAttribute = $cattribute5;

$attribute3_1 = new NestedTestContentObjectAttribute( 25, 'New article' );
$attribute3_1->ClassAttribute = $cattribute1;
$attribute3_2 = new NestedTestContentObjectAttribute( 26, 'Cool site' );
$attribute3_2->ClassAttribute = $cattribute2;
$attribute3_3 = new NestedTestContentObjectAttribute( 27, 'John Doe' );
$attribute3_3->ClassAttribute = $cattribute3;
$attribute3_4 = new NestedTestContentObjectAttribute( 28, 'Arne' );
$attribute3_4->ClassAttribute = $cattribute4;
$attribute3_5 = new NestedTestContentObjectAttribute( 29, 'document.pdf' );
$attribute3_5->ClassAttribute = $cattribute5;

$attributes1 = array( $attribute1, $attribute2, $attribute3, $attribute4, $attribute5 );
$object1 = new NestedTestContentObject( 2, 'New article', 1, $attributes1, $class1 );
$attributes2 = array( $attribute2_1, $attribute2_2, $attribute2_3, $attribute2_4, $attribute2_5 );
$object2 = new NestedTestContentObject( 3, 'Sub article', 1, $attributes2, $class1, array( 1, 5, 10 ) );
$attributes3 = array( $attribute3_1, $attribute3_2, $attribute3_3, $attribute3_4, $attribute3_5 );
$object3 = new NestedTestContentObject( 4, 'Sub folder', 1, $attributes3, $class2, array( 2, 4, 12 ) );
$object4 = null;

$node1 = new NestedTestContentObjectTreeNode( 2, 'New article', $object1 );
$node2 = new NestedTestContentObjectTreeNode( 3, 'Sub article', $object2 );
$node1->addChild( $node2 );
$node3 = new NestedTestContentObjectTreeNode( 4, 'Sub folder', $object3 );
$node1->addChild( $node3 );
$top = new NestedTestContentObjectTreeNode( 5, 'Top folder', $object4 );
$top->addChild( $node1 );

$tpl->setVariable( 'object', $object1 );
$tpl->setVariable( 'top', $top );

include_once( 'kernel/common/eztemplatedesignresource.php' );
$designResource =& eZTemplateDesignResource::instance();
$designResource->setKeys( array( array( 'object' => $object1->attribute( 'id' ) ),
                                 array( 'section', $object1->attribute( 'section_id' ) ),
                                 array( 'node' , $node1->attribute( 'node_id' ) ) ) );


?>
