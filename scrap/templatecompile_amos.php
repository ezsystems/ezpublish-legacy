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

$tpl->autoload();

class MyObject
{
    function MyObject()
    {
        $this->Sub = new MySubObject();
    }

    function hasAttribute( $name )
    {
        return in_array( $name, array( 'edit_template', 'view_template', 'name', 'sub' ) );
    }

    function attribute( $name )
    {
        if ( $name == 'edit_template' )
            return 'blah';
        else if ( $name == 'view_template' )
            return 'first';
        else if ( $name == 'name' )
            return 'MyObject';
        else if ( $name == 'sub' )
            return $this->Sub;
        return null;
    }
}

class MySubObject
{
    function hasAttribute( $name )
    {
        return in_array( array( 'edit_template', 'view_template', 'name' ), $name );
    }

    function attribute( $name )
    {
        if ( $name == 'edit_template' )
            return 'blah2';
        else if ( $name == 'view_template' )
            return 'blah';
        else if ( $name == 'name' )
            return 'MySubObject';
        return null;
    }
}

$myobj = new MyObject();

$tpl->setVariable( 'obj', $myobj );

$designResource =& eZTemplateDesignResource::instance();
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
