<?php
// URI:       design:content/datatype/view/ezimage.tpl
// Filename:  design/standard/templates/content/datatype/view/ezimage.tpl
// Timestamp: 1069148474 (Tue Nov 18 10:41:14 CET 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/shop/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$defaultResult1 = eZTemplateSetFunction::createDefaultVariables( $tpl,
                                                                 array( "image_class" => array( array( 3,
                                                                                                       "large",
                                                                                                       false ) ),
                                                                        "alignment" => array( array( 2,
                                                                                                     false,
                                                                                                     false ) ),
                                                                        "link_to_image" => array( array( 2,
                                                                                                         false,
                                                                                                         false ) ),
                                                                        "href" => array( array( 2,
                                                                                                false,
                                                                                                false ) ),
                                                                        "hspace" => array( array( 2,
                                                                                                  false,
                                                                                                  false ) ),
                                                                        "border_size" => array( array( 2,
                                                                                                       0,
                                                                                                       false ) ) ),
                                                                 array( array( 1,
                                                                               0,
                                                                               1 ),
                                                                        array( 6,
                                                                               22,
                                                                               153 ),
                                                                        "design/standard/templates/content/datatype/view/ezimage.tpl" ),
                                                                 $currentNamespace,
                                                                 $rootNamespace, $currentNamespace );

$text .= "  ";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult1 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "image_content" => array( array( 4,
                                                                                              array( "",
                                                                                                     2,
                                                                                                     "attribute" ),
                                                                                              false ),
                                                                                       array( 5,
                                                                                              array( array( 3,
                                                                                                            "content",
                                                                                                            false ) ),
                                                                                              false ) ),
                                                             "image" => array( array( 4,
                                                                                      array( "",
                                                                                             2,
                                                                                             "image_content" ),
                                                                                      false ),
                                                                               array( 5,
                                                                                      array( array( 4,
                                                                                                    array( "",
                                                                                                           2,
                                                                                                           "image_class" ),
                                                                                                    false ) ),
                                                                                      false ) ) ),
                                                      array( array( 7,
                                                                    2,
                                                                    158 ),
                                                             array( 8,
                                                                    41,
                                                                    236 ),
                                                             "design/standard/templates/content/datatype/view/ezimage.tpl" ),
                                                      $currentNamespace,
                                                      $rootNamespace, $currentNamespace );

$text .= "  ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "link_to_image" );

if ( $show )
{

unset( $show );

$text .= "  ";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
$letResult2 = eZTemplateSetFunction::defineVariables( $tpl,
                                                      array( "image_original" => array( array( 4,
                                                                                               array( "",
                                                                                                      2,
                                                                                                      "image_content" ),
                                                                                               false ),
                                                                                        array( 5,
                                                                                               array( array( 1,
                                                                                                             "original",
                                                                                                             false ) ),
                                                                                               false ) ) ),
                                                      array( array( 10,
                                                                    2,
                                                                    273 ),
                                                             array( 10,
                                                                    47,
                                                                    318 ),
                                                             "design/standard/templates/content/datatype/view/ezimage.tpl" ),
                                                      $currentNamespace,
                                                      $rootNamespace, $currentNamespace );

$text .= "      ";

$textElements = array();
$tpl->processFunction( "set", $textElements,
                       false,
                       array( "href" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "image_original" ),
                                                     false ),
                                              array( 5,
                                                     array( array( 3,
                                                                   "url",
                                                                   false ) ),
                                                     false ),
                                              array( 6,
                                                     array( "ezroot" ),
                                                     false ) ) ),
                       array( array( 11,
                                    6,
                                    327 ),
                             array( 11,
                                    41,
                                    362 ),
                             "design/standard/templates/content/datatype/view/ezimage.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "  ";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult2 );

$text .= "  ";

}

$text .= "     ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "href" );

if ( $show )
{

unset( $show );

$text .= "<a href=";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "href" );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= ">";

}

$text .= "<img src=";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "image" );
$var1 = "url";
$var = compiledFetchAttribute( $var, $var1 );
$tpl->processOperator( "ezroot",
                       array(),
                       $rootNamespace, $currentNamespace, $var, false, false );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= " width=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "image" );
$var1 = "width";
$var = compiledFetchAttribute( $var, $var1 );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\" height=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "image" );
$var1 = "height";
$var = compiledFetchAttribute( $var, $var1 );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\" ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "hspace" );

if ( $show )
{

unset( $show );

$text .= "hspace=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "hspace" );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\"";

}

$text .= " ";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "alignment" );

if ( $show )
{

unset( $show );

$text .= "align=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "alignment" );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\"";

}

$text .= " border=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "border_size" );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\" alt=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "image" );
$var1 = "text";
$var = compiledFetchAttribute( $var, $var1 );
$tpl->processOperator( "wash",
                       array( array( array( 3,
                                            "xhtml",
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $var, false, false );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\" title=\"";

$namespace = $rootNamespace;
$var = compiledFetchVariable( $vars, $namespace, "image" );
$var1 = "text";
$var = compiledFetchAttribute( $var, $var1 );
$tpl->processOperator( "wash",
                       array( array( array( 3,
                                            "xhtml",
                                            false ) ) ),
                       $rootNamespace, $currentNamespace, $var, false, false );
while ( is_object( $var ) and method_exists( 'templateValue' ) )
{
    $var =& $var->templateValue();
}
if ( is_object( $var ) )
    $text .= compiledFetchText( $tpl, $rootNamespace, $currentNamespace, $namespace, $var );
else
    $text .= $var;
unset( $var );

$text .= "\" />";

$namespace = $rootNamespace;
$show = compiledFetchVariable( $vars, $namespace, "href" );

if ( $show )
{

unset( $show );

$text .= "</a>";

}

$text .= "  ";

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $letResult1 );

include_once( "lib/eztemplate/classes/eztemplatesetfunction.php" );
eZTemplateSetFunction::cleanupVariables( $tpl,
                                         $rootNamespace, $currentNamespace,
                                         $defaultResult1 );


?>
