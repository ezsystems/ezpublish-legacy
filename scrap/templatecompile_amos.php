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
eZTemplateDesignResource::setDesignStartPath( "scrap/design" );

error_reporting ( E_ALL );

$tpl->autoload();

class MyObject
{
    function MyObject()
    {
        $this->Sub = new MySubObject();
    }

    function hasAttribute( $name )
    {
        return in_array( $name, array( 'edit_template', 'view_template', 'name', 'sub', 'is_information_collector' ) );
    }

    function attribute( $name )
    {
        if ( $name == 'edit_template' )
            return 'edit';
        else if ( $name == 'view_template' )
            return 'view';
        else if ( $name == 'name' )
            return 'MyObject';
        else if ( $name == 'sub' )
            return $this->Sub;
        else if ( $name == 'is_information_collector' )
            return false;
        return null;
    }
}

class MySubObject
{
    function hasAttribute( $name )
    {
        return in_array( $name, array( 'edit_template', 'view_template', 'name', 'is_information_collector' ) );
    }

    function attribute( $name )
    {
        if ( $name == 'edit_template' )
            return 'edit_sub';
        else if ( $name == 'view_template' )
            return 'view_sub';
        else if ( $name == 'name' )
            return 'MySubObject';
        else if ( $name == 'is_information_collector' )
            return true;
        return null;
    }
}

$myobj = new MyObject();
$myobj2 = new MySubObject();

$tpl->setVariable( 'obj', $myobj );
$tpl->setVariable( 'obj2', $myobj2 );

$designResource->setKeys( array( array( 'section', 2 ),
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
