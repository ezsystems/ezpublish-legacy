<?php

include_once( "lib/eztemplate/classes/eztemplate.php" );
include_once( "lib/eztemplate/classes/eztemplatephpoperator.php" );
include_once( "lib/eztemplate/classes/eztemplatelocaleoperator.php" );
include_once( "lib/eztemplate/classes/eztemplateimageoperator.php" );
include_once( "lib/eztemplate/classes/eztemplatelogicoperator.php" );

include_once( "lib/ezlocale/classes/ezlocale.php" );

$locale =& eZLocale::instance();

// Init template
$tpl =& eZTemplate::instance();
$tpl->setShowDetails( true );

$tpl_ini =& $tpl->ini();

include_once( "lib/ezutils/classes/ezsys.php" );

$imgop = new eZTemplateImageOperator();
$imgop->setFontDir( realpath( "." ) . "/" . $tpl_ini->variable( "TextToImageSettings", "FontDir" ) );
$imgop->setCacheDir( realpath( "." ) . "/" . $tpl_ini->variable( "TextToImageSettings", "CacheDir" ) );
$imgop->setHtmlDir( eZSys::wwwDir() . $tpl_ini->variable( "TextToImageSettings", "HtmlDir" ) );
$imgop->setFamily( $tpl_ini->variable( "TextToImageSettings", "Family" ) );
$imgop->setPointSize( $tpl_ini->variable( "TextToImageSettings", "PointSize" ) );
$imgop->setAngle( $tpl_ini->variable( "TextToImageSettings", "Angle" ) );
$imgop->setXAdjustment( $tpl_ini->variable( "TextToImageSettings", "XAdjustment" ) );
$imgop->setYAdjustment( $tpl_ini->variable( "TextToImageSettings", "YAdjustment" ) );
$imgop->setColor( "bgcolor", $tpl_ini->variable( "TextToImageSettings", "BackgroundColor" ) );
$imgop->setColor( "textcolor", $tpl_ini->variable( "TextToImageSettings", "TextColor" ) );

$tpl->registerOperators( new eZTemplatePHPOperator( array( "upcase" => "strtoupper",
                                                           "reverse" => "strrev",
                                                           "nl2br" => "nl2br" ) ) );
$tpl->registerOperators( new eZTemplateLocaleOperator() );
$tpl->registerOperators( new eZTemplateLogicOperator() );
$tpl->registerOperators( $imgop );

$tpl->setVariable( "curdate", mktime() );
$tpl->setVariable( "myvar", "Typewriter font output" );

print( "<p>With the <i>texttoimage</i> operator it's possible to create images out of text at
runtime. This is useful when you want to display text with other fonts than the standard web fonts
or want some special charset support. Normally you would create the images manually in a image
manipulation program and upload to the site, however this is very time consuming.</p>

<p>The operator has several parameters that can be modified, some, as directory information, can only
be modified in PHP code while others as size, family etc. has default values which can be overridden
in the template.</p>

<p>The parameters below are listed sequentially as they are entered in the template, if you want
the default value simply use a ',' (comma)</p>
<table>
<tr><th>Name</th><th>Default</th><th>Description</th></tr>
<tr><td>family</td><td>\"" . $imgop->family() . "\"</td><td>The name of the font family (e.g. arial) which must be in TrueType format</td></tr>
<tr><td>pointsize</td><td>" . $imgop->pointSize() . "</td><td>The fonts pointsize</td></tr>
<tr><td>angle</td><td>" . $imgop->angle() . "</td><td>The angle of the font rendering in degrees</td></tr>
<tr><td>bgcolor</td><td>(" . implode( ",", $imgop->color( "bgcolor" ) ) . ")</td><td>The background color</td></tr>
<tr><td>textcolor</td><td>(" . implode( ",", $imgop->color( "textcolor" ) ) . ")</td><td>The color used for rendering the font</td></tr>
<tr><td>x-adjustment</td><td>" . $imgop->xAdjustment() . "</td><td>Number of pixels to adjust the x position</td></tr>
<tr><td>y-adjustment</td><td>" . $imgop->yAdjustment() . "</td><td>Number of pixels to adjust the y position</td></tr>
</table>
<p class=\"footnote\">This operator is most likely to change in the future, this is needed to allow more advanced image features such as layers and more.</p>
" );

$tpl->display( "lib/eztemplate/sdk/templates/image.tpl" );

eZDebug::addTimingPoint( "Template end" );

?>
