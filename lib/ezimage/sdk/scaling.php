<h1>Scaling</h1>

<p>
The code for scaling the image is similar to the conversion code but adds a rule that
states the width and height of the destination image.
</p>

<pre class="example">
$source = "lib/ezimage/sdk/images/bike.jpg";
$dest_img = $img->convert( $source, "var/cache/image1-scale",
                           array( "width" => 200, "height" => 200 ),
                           array( array( "rule-type" => "colorspace/gray" ) ) );

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

// $file2 = "images/bike.jpg";
// $file3 = "lib/ezimage/sdk/$file2";
// $file4 = "images/nightelfvsundead-1600x.jpg";
// $scale_type = $img->mimeTypeFor( $file3 );
// $scale_rules =& $img->convertRules( $scale_type, true );
// $img->addScaleRule( $scale_rules, array( "width" => 100, "height" => 100 ) );
// $img->addFilterRule( $scale_rules, true );

// $img2 = $img->convert( $file3, "var/cache/image1-scale",
//                        array( "width" => 200, "height" => 200 ),
//                        array( array( "rule-type" => "colorspace/gray" ) ) );

// print( '<h2>The running example</h2>
// <h3>Conversion from ' . "$scale_type ($file3)" . '</h3>
// <table style="border: 1px black;" cellspacing="4">
//   <tr><th>From</th>
//       <th>To</th>
//       <th>Conversion</th>
//       <th>Can scale</th>
//       <th>Do scale</th>
//       <th>Can filter</th>
//       <th>Do filter</th>
//       <th>Parameters</th>
//   </tr>' );
// foreach( $scale_rules as $rule )
// {
//     print( "<tr>
// <td>" . $rule["from"] . "</td>
// <td>" . $rule["to"] . "</td>
// <td>" . $rule["type"] . "</td>
// <td>" . ( $rule["canscale"] ? "yes" : "no" ) . "</td>
// <td>" . $rule["scale"]["width"] . "x" . $rule["scale"]["height"] . "</td>
// <td>" . ( $rule["canfilter"] ? "yes" : "no" ) . "</td>
// <td>" . ( $rule["filter"] ? "yes" : "no" ) . "</td>
// <td>" . $rule["params"] . "</td>\n" );
// }

// print( '<tr><td>Output file="' . eZSys::wwwDir() . "/$img2" . '"</td></tr>
// <tr><td><img src="' . eZSys::wwwDir() . "/$img2" . '" /></td></tr>
// ' );

// print( "</table>" );

?>
