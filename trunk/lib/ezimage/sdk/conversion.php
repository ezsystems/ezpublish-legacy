<h1>Image conversion</h1>

<p>
Converting the image is done by passing the file path to the source image and the
output directory to the <i>convert</i> function. Outputting the image in HTML is done
by printing the result string as the src of an <i>img</i> tag.
</p>

<pre class="example">
$source = "lib/ezimage/sdk/images/connect.xpm";
$dest_img = $img->convert( $source, "var/cache/" );

print( "&lt;img src="/$dest_img"&gt;" );
</pre>

<?php

// include_once( "lib/ezutils/classes/ezsys.php" );

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

// $file2 = "images/connect.xpm";
// $file3 = "lib/ezimage/sdk/$file2";
// $conv_type2 = $img->mimeTypeFor( $file2 );
// $scale_type = $img->mimeTypeFor( $file3 );
// $convert_rules2 =& $img->convertRules( $conv_type2 );

// $img1 = $img->convert( $file3, "var/cache/" );

// print( '<h2>The running example</h2>
// <h3>Conversion from ' . "$conv_type2 ($file2)" . '</h3>
// <table style="border: 1px black;" cellspacing="4"><tr><th>From</th><th>To</th><th>Conversion</th><th>Can scale</th></tr>' );
// foreach( $convert_rules2 as $rule )
// {
//     print( "<tr>
// <td>" . $rule["from"] . "</td>
// <td>" . $rule["to"] . "</td>
// <td>" . $rule["type"] . "</td>
// <td>" . ( $rule["canscale"] ? "yes" : "no" ) . "</td>
// <td>" . $rule["params"] . "</td>\n" );
// }

// print( '<tr><td>Output file="' . eZSys::wwwDir() . "/$img1" . '"</td></tr>
// <tr><td><img src="' . eZSys::wwwDir() . "/$img1" . '" /></td></tr>
// ' );

// print( "</table>" );

?>
