<h1>Image rules</h1>

<p>
The basis of the image system is to setup the rules that control how to convert from one format to
another. This consists of initializing the handlers, mimetypes, conversion rules and output types.
</p>

<h2>Initializing</h2>
<p>
The manager is initialized by registering the various image handlers, each handler is
given an identifier which is used by image rules. This means that it's possible to
register the same image class multiple times with different names, this is useful
if you want different options.
</p>

<pre class="example">
// First get the image manager instance.
$img =&amp; eZImageManager::instance();

// Register a shell converter using "convert", we name it "convert"
$img->registerType( "convert", new eZImageShell( '', "convert", array(), array(), array() ) );
// Register a converter which uses the PHP extension ImageGD
$img->registerType( "gd", new eZImageGD() );
</pre>

<h2>Creating mimetypes</h2>
<p>
For the image manager to know the image type of a file you need to setup some mimetypes. The mimetype is a unique
identifier for one given filetype and consists of a group and name part delimited by a slash (/).
A standardised set of mimetypes is defined, but you can create your own if you wish.
</p>

<p>
The mimetypes are based on filenames (for now). The mimetype rule consists of the identifier (image/jpeg),
the regular expression matching the filename ("\.jpe?g$") and the filename suffix. The suffix is for
creating proper filenames for converted images.
</p>

<pre class="example">
// Create the JPEG, PNG and GIF type
$jpeg_type = $img->createMIMEType( "image/jpeg", "\.jpe?g$", "jpg" );
$png_type  = $img->createMIMEType( "image/png", "\.png$", "png" );
$gif_type  = $img->createMIMEType( "image/gif", "\.gif$", "gif" );

// and register them
$img->setMIMETypes( array( $jpeg_type, $png_type, $gif_type ) );
</pre>

<h2>Creating rules</h2>
<p>
Next we create conversion rules by using the eZImageManager::createRule function.
The function returns a structure based upon the parameters. For instance to convert
from GIF to PNG we would do this:
</p>

<pre class="example">
// Create rule
$gif2png_rule = $img->createRule( "image/gif", "image/png", "convert", false, false );

// Register the rule and setup the default rule
$img->setRules( array( $gif2png_rule ),
                $img->createRule( "*", "image/png", "convert", false, false ) );
</pre>

<p>
The rule consists of the from and to mimetypes, the image handler identifier and two parameters
that say whether the rule can scale images and if it can run image operations.
The default rule is also registered, this rule will be activated if no other rules
match the file.
</p>

<h2>Finishing the setup</h2>
<p>
The last thing to do before the manager can be used is to setup the allowed output types.
The output types supported by most browsers are GIF, PNG and JPEG but this might increase
in the future, we might also want to limit our output types due to licensing issues.
</p>

<pre class="example">
// We only want JPEG images
$img->setOutputTypes( array( "mime/jpeg" ) );
</pre>

<?php

// include_once( "lib/ezimage/classes/ezimagemanager.php" );
// include_once( "lib/ezimage/classes/ezimageshell.php" );
// include_once( "lib/ezimage/classes/ezimagegd.php" );

// $img =& eZImageManager::instance();

// $img->registerType( "convert", new eZImageShell( '', "convert", array(), array(),
//                                                  array( eZImageShell::createRule( "-geometry %wx%h>",
//                                                                                   "modify/scale" ),
//                                                         eZImageShell::createRule( "-colorspace GRAY",
//                                                                                   "colorspace/gray" ) ) ) );
// $img->registerType( "svg2gif", new eZImageShell( '', "svg2gif", array( "-antialias" ), array(),
//                                                  array( eZImageShell::createRule( "",
//                                                                                   "modify/scale" ) ) ) );
// $img->registerType( "gd", new eZImageGD() );

// // We only want jpeg output
// $types = array( "image/jpeg" );

// // Setup conversion rules
// $rules = array( $img->createRule( "image/jpeg", "image/jpeg", "convert", true, false ),
//                 $img->createRule( "image/png", "image/jpeg", "gd", true, false ),
//                 $img->createRule( "image/gif", "image/png", "convert", true, false ),
//                 $img->createRule( "image/ps", "image/pdf", "ps2pdf", true, false ),
//                 $img->createRule( "image/pdf", "image/png", "convert", true, false ),
//                 $img->createRule( "image/wml", "image/svg", "wml2svg", false, false ),
//                 $img->createRule( "image/svg", "image/gif", "svg2gif", false, false ),
//                 $img->createRule( "image/ppm", "image/gif", "convert", false, false ),
//                 $img->createRule( "image/xpm", "image/tiff", "convert", false, false ),
//                 $img->createRule( "image/tiff", "image/tga", "convert", false, false ),
//                 $img->createRule( "image/tga", "image/gif", "convert", false, true ),
//                 $img->createRule( "image/psd", "image/jpeg", "convert", true, false ) );

// // And mime rules
// $mime_rules = array( $img->createMIMEType( "image/jpeg", "\.jpe?g$", "jpg" ),
//                      $img->createMIMEType( "image/png", "\.png$", "png" ),
//                      $img->createMIMEType( "image/gif", "\.gif$", "gif" ),
//                      $img->createMIMEType( "image/xpm", "\.xpm$", "xpm" ),
//                      $img->createMIMEType( "image/tiff", "\.tiff$", "tiff" ),
//                      $img->createMIMEType( "image/ppm", "\.ppm$", "ppm" ),
//                      $img->createMIMEType( "image/tga", "\.tga$", "tga" ),
//                      $img->createMIMEType( "image/svg", "\.svg$", "svg" ),
//                      $img->createMIMEType( "image/wml", "\.wml$", "wml" ),
//                      $img->createMIMEType( "image/bmp", "\.bmp$", "bmp" ) );

// $img->setOutputTypes( $types );
// // Set rules including default rule
// $img->setRules( $rules, $img->createRule( "*", "image/wml", "convert", true, false ) );
// $img->setMIMETypes( $mime_rules );

// // Use the image system

// $file1 = "images/bike.bmp";
// $conv_type = $img->mimeTypeFor( $file1 );
// $convert_rules =& $img->convertRules( $conv_type );
// $img->addScaleRule( $scale_rules, array( "width" => 100, "height" => 100 ) );
// $img->addFilterRule( $scale_rules, true );

// print( '<h2>The running example</h2>
// <p>Note: The example is not converting the image, only displaying the rule tree
// for a given image with a set of conversion rules.
// </p>
// <h3>Conversion from ' . "$conv_type ($file1)" . '</h3>
// <table style="border: 1px black;" cellspacing="4"><tr><th>From</th><th>To</th><th>Conversion</th><th>Can scale</th></tr>' );
// foreach( $convert_rules as $rule )
// {
//     print( "<tr>
// <td>" . $rule["from"] . "</td>
// <td>" . $rule["to"] . "</td>
// <td>" . $rule["type"] . "</td>
// <td>" . ( $rule["canscale"] ? "yes" : "no" ) . "</td>
// <td>" . $rule["params"] . "</td>\n" );
// }

// print( "</table>
// <p/>
// Mime type for my.png is " . $img->mimeTypeFor( "my.png" ) );


?>
