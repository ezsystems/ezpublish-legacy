<?php
//
// Created on: <24-Jan-2003 15:34:16 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

$Result = array( 'title' => 'Operator list' );

?>

<p>
This is a complete list of all the operators you can use in eZ publish
templates. Most operators are native to eZ template (general), some are specific
for eZ publish.
</p>

<h2>Execute</h2>
<dl>
<dt>fetch</dt>
<dd>Executes a given fetch operation, and returns the result. The parameters are the module name, the
operation name, and a hash of operation parameters corresponding to the given function_name in
kernel/[module_name]/function_definition.php. A few examples:<br/>
Fetch the number of items in a list:
E.g. {fetch('content','list_count',hash(parent_node_id,$node.node_id,sort_by,array(published,true()),class_filter_type,exclude,class_filter_array,array(1,24)))}<br/>
Fetch a class: Returns the content class defined by class_id. E.g. {fetch('content','class',hash(class_id,4))}<br/>
Fetch a class attribute: Returns one specific class attribute defined by attribute_id. The parameter
version_id is optional (default 0) and defines the version of the attribute.
E.g. {fetch('content','class_attribute',hash(attribute_id,140,version_id,0))}<br/>
Fetch a class attribute list: Returns an array of all attributes for one class defined by class_id.
The parameter version_id is optional (default 0) and defines the version of the attributes.
E.g. {fetch('content','class_attribute_list',hash(class_id,4,version_id,0))}
</dd>
</dl>

<h2>Locale</h2>
<dl>
<dt>l10n</dt>
<dd>Localizes values, e.g. {42.50|l10n(currency)}. Allowed types are: time, shorttime, date, shortdate, currency or number.</dd>
</dl>

<h2>Dates</h2>
<dl>
<dt>datetime</dt>
<dd>Formats dates and times according to formats defined in datetime.ini. Custom formats in the template itself are also possible. E.g. {$date|datetime(mydate)}, {$date|datetime(custom,"%m %y")}</dd>
<dt>currentdate</dt>
<dd>Returns the timestamp of the current datetime. E.g. {currentdate()}</dd>
</dl>

<h2>Debug</h2>
<dl>
<dt>attribute</dt>
<dd>Helper attribute to print available methods in objects and arrays, by default it only shows the array keys and object attribute names but by passing show as parameter it will fetch the values. The second parameter can be used to controlled the number of children to expand, default is no limit which may give problems with values that loop. The returned result is an HTML table unless false() is passed as the third parameter. E.g. {$node|attribute(show)}, {$node|attribute(show,2,false)}
</dd>
</dl>

<h2>Output washing</h2>
<dl>
<dt>wash</dt>
<dd>General character/string washing operator. The washing type is the first parameter, for now it only supports XHTML characters.</dd>
</dl>

<h2>Text operations</h2>
<dl>
<dt>concat</dt>
<dd>Concatenates values to one string. If you give it an array, it will concatenate the elements of the array. E.g. {concat('/var/',$node.node_id,'/')}</dd>
<dt>autolink</dt>
<dd>Converts all known links in a text to links that can be clicked. E.g. {"Some links: ftp://ftp.example.com me@example.com http://www.example.com"|autolink}
</dd>
</dl>

<h2>Unit operators</h2>
<dl>
<dt>si</dt>
<dd>Handles unit display of values, most often used for showing sizes of files but can also be used for displaying units on other items such as meters, grams etc. The operator reads two parameters. The first tells the kind of unit type we're dealing with, for instance: byte, length. The second determines the behaviour of prefixes and is optional.</dd>
<dd>E.g.<br/>
{1025|si(byte)}<br/>
{1025|si(byte,binary)}<br/>
{1025|si(byte,decimal)}<br/>
{1025|si(byte,none)}<br/>
{1025|si(byte,auto)}<br/>
{1025|si(byte,mebi)}</dd>
</dl>

<h2>Type creators</h2>
<dl>
<dt>true</dt>
<dd>Creates a true boolean. Remember to use brackets, e.g. {true()}.</dd>
<dt>false</dt>
<dd>Creates a false boolean. Remember to use brackets, e.g. {false()}.</dd>
<dt>array</dt>
<dd>Creates an array. E.g. array(6,8,42)</dd>
<dt>hash</dt>
<dd>Creates an associative array. E.g. hash(name,'Ola Norman',age,26)</dd>
</dl>

<h2>Control operators</h2>
<dl>
<dt>cond</dt>
<dd>Evaluates clauses and returns the value of the first clause whose condition is true. Pairs of parameters are treated as clauses where the first is the condition and the second is the body. The last clause may have one parameter only in which case it is used as condition and body. E.g. {cond($b|ne(0),div($a,$b),0)} returns the result of $a/$b if $b is not 0, or 0 if it is.
</dd>
<dt>first_set</dt>
<dd>Returns the first value which exists, this is useful if you want to make sure that you always have a value to work with, since you can put a constant as the last parameter and constants always exist. E.g. {first_set($a,$b,$c,"&nbsp;")} returns "&nbsp;" if none of the variables are set.</dd>
</dl>

<h2>Logical operators</h2>
<dl>
<dt>lt</dt>
<dd>Returns true if the input value is less than the first parameter. E.g. {1|lt(2)} returns true.</dd>
<dt>gt</dt>
<dd>Returns true if the input value is greater than the first parameter. E.g. {2|gt(1)} returns true.</dd>
<dt>le</dt>
<dd>Returns true if the input value is less than or equal to the first parameter. E.g. {1|le(1)} and {1|le(2)} returns true.</dd>
<dt>ge</dt>
<dd>Returns true if the input value is greater than or equal to the first parameter. E.g. {1|le(1)} and {2|le(1)} returns true.</dd>
<dt>eq</dt>
<dd>Returns true if the input value is equal to the first parameter, or if no input value is available it returns true if all parameters are equal. E.g. {1|eq(1)} returns true. {eq(1,true(),false()|not,0|inc)} returns true.</dd>
<dt>null</dt>
<dd>Returns true if the input value is null, which is not the same as 0. E.g. {0|null()} returns false.</dd>
<dt>not</dt>
<dd>Returns true if the input value is false. E.g. {false()|not()} returns true.</dd>
<dt>true</dt>
<dd>Creates a true boolean. Remember to use brackets, e.g. {true()}.</dd>
<dt>false</dt>
<dd>Creates a false boolean. Remember to use brackets, e.g. {false()}.</dd>
<dt>or</dt>
<dd>Evaluates all parameter values until one is found to be true, then returns that value. The remaining parameters are not evaluated at all. If there are no parameters or all elements were false it returns false. E.g. {or(false(),false(),true(),false())} returns true.</dd>
<dt>and</dt>
<dd>Evaluates all parameter values until one is found to be false, then returns that value. The remaining parameters are not evaluated at all. If there are no parameters it returns false, if no elements were false it returns the last parameter value. E.g. {and(false(),false(),true(),false())} returns false.</dd>
<dt>choose</dt>
<dd>Uses the input count to pick one of the parameter elements. The input count equals the parameter index. E.g. {0|choose("a","b","c")} returns "a".</dd>
<dt>contains</dt>
<dd>Returns true if the first parameter value is found in the input value, which must be an array.
Currently it works the same way as the PHP function in_array() but it may later be extended to support more
advanced matching.<br/>
E.g. $array|contains($myvalue)</dd>
</dl>

<h2>Type operators</h2>
<p>These operators generally correspond to the PHP functions of the same name, where they exist.</p>
<dl>
<dt>is_array</dt>
<dd>Returns true if the input or the first parameter is an array. If both input and parameter are supplied, the parameter will be used.</dd>
<dt>is_boolean</dt>
<dd>Returns true if the input or the first parameter is a boolean (true or false). If both input and parameter are supplied, the parameter will be used.</dd>
<dt>is_integer</dt>
<dd>Returns true if the input or the first parameter is an integer. If both input and parameter are supplied, the parameter will be used.</dd>
<dt>is_float</dt>
<dd>Returns true if the input or the first parameter is a floating point number. If both input and parameter are supplied, the parameter will be used.</dd>
<dt>is_numeric</dt>
<dd>Returns true if the input or the first parameter is a number or a numberic string (a string consisting of numbers). If both input and parameter are supplied, the parameter will be used.</dd>
<dt>is_string</dt>
<dd>Returns true if the input or the first parameter is a string. If both input and parameter are supplied, the parameter will be used.</dd>
<dt>is_object</dt>
<dd>Returns true if the input or the first parameter is an object (as opposed to a simple type like integer or float). If both input and parameter are supplied, the parameter will be used.</dd>
<dt>is_class</dt>
<dd>Returns true if the input or the first parameter is a class. If both input and parameter are supplied, the parameter will be used.</dd>
<dt>is_null</dt>
<dd>Returns true if the input or the first parameter is null. Note: The integer 0 is not the same as 'null', if you want to test for 0 use {$var|eq(0)} or {eq($var,0)} instead. If both input and parameter are supplied, the parameter will be used.</dd>
<dt>is_set</dt>
<dd>Returns true if the first parameter is not false. is_set does not take an input.</dd>
<dt>is_unset</dt>
<dd>Returns true if the first parameter is false. is_unset does not take an input.</dd>
<dt>get_type</dt>
<dd>Returns the type of the input or the first parameter as a string. If both input and parameter are supplied, the parameter will be used. If the data is an object, then the string 'object' and the classname will be returned. If the data is an array, then the string 'array' and the array count will be returned. If the data is a string, then the string 'string' and the string length will be returned.</dd>
<dt>get_class</dt>
<dd>Returns the class of the input or the first parameter as a string. If both input and parameter are supplied, the parameter will be used. If the data is not an object, false will be returned.</dd>
</dl>

<h2>Arithmentic operators</h2>
<dl>
<dt>sum</dt>
<dd>Returns the sum of all parameters.</dd>
<dt>sub</dt>
<dd>Subtracts all extra parameters from the first parameter, e.g. sub($a,$b,$c) equal $a - $b - $c.</dd>
<dt>inc</dt>
<dd>Increases either the input value or the first parameter with one.</dd>
<dt>dec</dt>
<dd>Decreases either the input value or the first parameter with one.</dd>
<dt>div</dt>
<dd>Divides all extra parameters with the first parameter, e.g. div($a,$b,$c) equal $a / $b / $c.</dd>
<dt>mod</dt>
<dd>Returns the modulo of the first input parameter divided by the second. E.g. mod(5,3) returns 2.</dd>
<dt>mul</dt>
<dd>Multiplies all parameters and returns the result.</dd>
<dt>max</dt>
<dd>Returns the largest value of all parameters.</dd>
<dt>min</dt>
<dd>Returns the smallest value of all parameters.</dd>
<dt>abs</dt>
<dd>Returns a positive value of either the input value or the first parameter. E.g. {abs(-1)} returns 1 and {abs(200)} returns 200.</dd>
<dt>ceil</dt>
<dd>Returns the next highest integer value by rounding up input value if necessary.</dd>
<dt>floor</dt>
<dd>Returns the next lowest integer value by rounding down input value if necessary.</dd>
<dt>round</dt>
<dd>Returns the rounded value of input value.</dd>
<dt>count</dt>
<dd>Returns the count of the input value.</dd>
<dd>How counts are interpreted:<br/>
- If the data is an array the array count is used<br/>
- If the data is an object the object attribute count is used<br/>
- If the data is a string the string length is used<br/>
- If the data is a numeric the value is used<br/>
- If the data is a boolean false is 0 and true is 1<br/>
- For all other data 0 is used</dd>
</dl>

<h2>Image handling operators</h2>
<p>These operators require the ImageMagick and/or ImageGD extension to work.</p>
<dl>
<dt>image</dt>
<dd>Creates and returns an image by flattening the image layers given as parameters. (This requires the
ImageMagick or the ImageGD extension.) If a parameter is text it will be used as the alternative text.
If the parameter is an array it will assume that the first element (0) is the image layer, and the second
is a hash table with parameters for that layer.<br/>
The parameters that can be set are:<br/>
- transparency: float value from 0 to 1.0 (ie 0-100%)<br/>
- halign: horizontal alignment, use left, right or center<br/>
- valign: vertical alignment, use top, bottom or center<br/>
- x: absolute placement (works with left and right align)<br/>
- y: absolute placement (works with top and bottom align)<br/>
- xrel: relative placement, float value from 0 to 1.0. (works with left and right align)<br/>
- xrel: relative placement, float value from 0 to 1.0. (works with top and bottom align)</dd>
<dd>The x and xrel parameters cannot be used at the same time (same with y and yrel).</dd>
<dd>When right or bottom alignment is used, the coordinate system will shift to accommodate the alignment.
This is useful for doing alignment and placement since the placement is relative to the current coordinate
system. Right alignment will start the axis at the right (0) and go on to the left (width). Bottom
alignment will start the axis at the bottom (0) and go on to the top (height).</dd>
<dd>
Examples:<br/>
Merge two images: {image(imagefile('image1.png'|ezimage),imagefile('image2.png'|ezimage))}<br/>
Texttoimage: {'I is cool'|texttoimage('arial')}<br/>
Similar to above but now wrapped in an image object: {image('I is cool'|texttoimage('arial'))}<br/>
Loads image from file to display as layer: {imagefile('var/cache/texttoimage/church.jpg')}<br/>
Creates image object with one file image layer: {image(imagefile('var/cache/texttoimage/church.jpg'))}<br/>
Creates image object with 80% transparent text aligned in the top right corner:<br/>
{image("church",imagefile('var/cache/texttoimage/church.jpg'),array('I is cool '|texttoimage('arial'),hash('transparency',0.8,halign,right,valign,top)))}
</dd>
<dt>imagefile</dt>
<dd>Creates and returns an image layer for the image file given as the first parameter.
(This requires the ImageMagick or the ImageGD extension.) See the 'image' example.</dd>
<dt>texttoimage</dt>
<dd>Converts the input value, which should be a string, into an image. (This requires the ImageGD
extension.) It can also be used with the 'image' operator to allow you to merge the output with another
image. Use only the first parameter if you don't want to override the settings in the font class.
The font classes are specified in settings/texttoimage.ini.</dd>
<dd>Accepts the following parameters:<br/>
- class: The font class, which is specified in settings/texttoimage.ini. Use for instance 'archtura'.<br/>
- family: The font family.<br/>
- pointsize: The point size of the font.<br/>
- angle: The angle, in degrees counterclockwise from horizontal, at which the text should be shown.<br/>
- bgcolor: The background color, specified as an array of three numbers from 0 to 255.<br/>
- textcolor: The text color, specified as an array of three numbers from 0 to 255.<br/>
- x: The horizontal text offset in pixels from the left side of the image.<br/>
- y: The vertical text offset in pixels from the top of the image.<br/>
- w: A number of pixels that specify how much wider than the default the image should be.<br/>
- h: A number of pixels that specify how much taller than the default the image should be.<br/>
- usecache: A boolean that decides whether to use cache, must be true() or false().<br/>
Examples:<br/>
{"This is not a text"|texttoimage('archtura')}<br/>
{"This is not a text"|texttoimage('archtura',,50,0,array(200,255,255),array(255,0,0),10,10,28,26,true())}
</dd>
</dl>

<h2>eZ publish kernel operators</h2>
<p>For more information see the <a href="/sdk/kernel/view/template_operators/">Kernel template operators</a> tutorial.</p>
<dl>
<dt>ezurl</dt>
<dd>Makes sure that the url works for both virtual hosts and non-virtual host setups.</dd>
<dt>ezroot</dt>
<dd>Similar to ezurl but does not include the index.php.</dd>
<dt>ezsys</dt>
<dd>Returns values from ezsys. The available values are wwwdir, sitedir, indexfile, indexdir and imagesize.</dd>
<dt>ezdesign</dt>
<dd>Similar to ezroot but prefixes with the design directory.</dd>
<dt>ezimage</dt>
<dd>Similar to ezroot but prefixes image directory in the design.</dd>
<dt>exturl</dt>
<dd>Not finished. Will be used for storing a URL in the database with a unique id, this means that similar URLs across templates can be updated centrally without changing templates. This is useful if URLs are wrong or become dead.</dd>
<dt>i18n</dt>
<dd>Marks a string for translation. E.g. {"I like %1!"|i18n("design/standard",,array($food))} See the tutorial <a href="/sdk/sdk/tutorials/view/translation">Translation and i18n</a> for more information.</dd>
<dt>x18n</dt>
<dd>Marks a string in an extension template for translation. E.g. {"I like %1!"|i18n("myextension","design/standard",,array($food))} See the tutorial <a href="/sdk/sdk/tutorials/view/translation">Translation and i18n</a> for more information.</dd>
</dl>
