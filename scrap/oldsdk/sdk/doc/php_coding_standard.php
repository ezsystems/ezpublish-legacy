<?php
//
// Created on: <03-Jun-2002 12:52:26 bf>
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

$DocResult = array();
$DocResult["title"] = "PHP Coding Standard";
?>

<p>
This document defines how the PHP code in eZ publish&trade; is formatted. Naming conventions are also
defined in this document. The code in eZ publish must follow this standard to ensure a coherent looking code base.
</p>
<p>
To easier comply with this coding standard we recommend using emacs with our configuration files which handles proper
indenting and other nice features.
</p>

<h1>Files </h1>
<p>
Use lower-case in all file names. PHP files are named <i>.php</i>
</p>

<h1>Headers</h1>
<p>
All files must start with the following header:
</p>
<pre class="example">
<?php include( "doc/standards/header.txt" ); ?>
</pre>


<h1>Indenting</h1>
<p>
Indenting is 4 characters, real tab characters should not be used. Spaces are used for indenting.
</p>

<h1>Placement of brackets</h1>
<p>
Start and end brackets should be placed on the same column.
</p>
<p>
Examples:
</p>
<pre class="example">
multiply( $x, $y )
{
    // function body
}

if ( x == y )
{
  ..
}
else if ( x > y )
{
    ..
}
else
{
    ..
}

class foo
{
    function foo( )
    {
        ..
    }
}
</pre>

<h1>Parenthesis, brackets and comma</h1>
<p>
It should always be a space after a start parenthesis and one space before an end parenthesis.
One space is placed after every comma. Square brackets [] should not have space after the start
bracket and before the end bracket. It should be a space between each type.
</p>
<p>
Empty lines should be used to group variables and code lines which have a connection. Variable
names which is grouped should be indented to the same level.
</p>
<p>
Empty lines should not only appear empty, but they should also be empty, all tab and space
characters should be removed.
</p>
<p>
Use more parenthesis than necessary if you're not certain in which sequence the expression is executed.
</p>

<h1>Strings</h1>

$a = "aiosdjfjiioj $abc aopsdfopasdokf $adfafsd";
$a = 'aiosdjfjiioj ' . $abc . ' aopsdfopasdokf ' . $adfafsd;
$a = implode( 'aiosdjfjiioj ', $abc, ' aopsdfopasdokf ', $adfafsd );

<p>
PHP has many different ways of handling strings. Some are preferred because of speed, others
because of readability of code.
</p>

<p>
PHP has two different quotes " and '. These are used to define a text string. The difference
between the two is that the single quote does not process the string or does variable replacement.
I.e. if you write  "$myVariable\n" and '$myVariable\n' the first will print the contents of the variable
followed by a newline character. The latter will print: $myVariable\n, with no aditional processing.
For this reason you should use single quotes rather than double quotes because this will be quicker.
If you need to have a combination of text and variables you should use the implode() function. This
function should be used instead of the . operator since this does only allocate memory once and is
therefore more efficient.
</p>

<p>
Below is samples of how you should use single quotes in eZ publish.
</p>

<pre class="example">
$myHash['key'];
$str = 'This is a string';
$combinedString = implode( 'The color of the ball is ', $color, ' and it costs: ' . $price );

if ( $variable == 'string' )
{
    ..
}
</pre>


<h1>If and while statements</h1>
<p>
Nested if statements should always use {}. If you have a lot of if-else statements these should
probably be replaced by case statements, or redesign your code to use object oriented methods.
If and while statements should be constructed of logic statements, no assignment or changing of
data should occur.
</p>

<h1>Control structures</h1>
<p>
Below is examples the syntax of the different PHP control structures. Notice the space
after the control structure name, this is different from functions which does not have
a space after the function name. PHP has different syntax rules for using control structures,
the syntax below is preferred.
</p>

<pre class="example">

// if/else statement
if ( $a == $b )
{
    ...
}
else
{
    ...
}

// nested if/else statement
if ( $a == $b )
{
    ...
}
else if ( $a == $c )
{
    ...
}

// while loop
$i = 0;
while ( $i < 100 )
{
    ...
    $i++;
}

// do..while loop
$i = 0;
do
{
    ...
    $i++;
}
while ( $i < 100 );

// for loop
for ( $i = 0; $i < 100; $i ++ )
{
    ...
}

// foreach loop
foreach ( $array as $value )
{
    ...
}

// switch
switch ( $value )
{
    case 0:
    {
        ...
    }break;

    case 'text':
    {
        ...
    }break;

    default:
    {
        ...
    }break;
}



</pre>

<h1>Functions</h1>
<p>
Functions should be placed inside classes when possible. The syntax of functions are
showed in the snipped below.
</p>

<pre class="example">
/*!
  This is my function.
*/
function &myFunction( $paramA = 1, $paramB = 2 )
{
    return 42;
}
</pre>

<h1>Arrays</h1>
<p>
When indexing hash arrays you should use single quotes and no spaces.
</p>

<pre class="example">
$value = $myHashArray['IndexValue'];
</pre>

<h1>Naming</h1>
<p>
All names should be English and grammatically correct. Names like "foo" and "tmp" should be avoided
since they do not describe anything. Names should not contain numbers, numbers should be described
with letters unless there is a good reason for it.
</p>
<p>
Example:
</p>
<pre class="example">
$ValueOne
</pre>
<p>
Function names can be constructed of several words, all words should have lower-case except the first
letter which should be an upper-case letter. The exception for this is the first letter which should always
be in lower-case. If the first letter is part of an abbreviation the whole abbreviation should be in
lower-case. Functions which return booleans should be named as a question (<i>has</i>, <i>is</i>,
<i>can</i> ...).
</p>
<p>
Example:
</p>
<pre class="example">
setText(..);
id();
setID();
getTheValuesForThis();
hasValue();
isValid();
canProcess();
</pre>
<p>
Constants should be constructed by upper-case only letters, _ should be used to separate words.
</p>
<p>
Example:
</p>
<pre class="example">
define( 'MY_CONSTANT', 42 );
</pre>
<p>
Member variables can be constructed of several words, every word is spelled in lower-case with a capital
first letter. It should not be too many words, 1-3 is normal, they can be abbreviated as long as it's
understandable.
</p>
<p>
Example:
</p>
<pre class="example">
$Len;
$X, $Y;
$FirstName;
</pre>
<p>
Local variables can also consist of several words in lower-case, the first character of each word is a
capital letter except the first word. The number of words should be as few as possible and the words can be abbreviated.
</p>
<pre class="example">
$index, $x, $y, i;
$xAdd;
$firstName, $lastName;
</pre>
<p>
Parameters are names like local variables.
</p>
<p>
Class names can consist of several words, every word should be in lower-case with the first letter in capital.
Classes should have a unique string which starts the name, for instance eZ, which is to uniquely identify the
class.
</p>
<p>
Example:
</p>
<pre class="example">
eZUser
eZContentObject
eZImageVariation
</pre>
<p>
Global variables are to be avoided, place them in a class and make them static.
</p>
<h2>HTTP Post variables</h2>
<p>
POST variables should have the following syntax.
</p>
<pre class="example">
Class_FirstName
Class_ValueOne
Content_CategoryID
Content_LongVariable
SearchValue
</pre>

<p>The <i>Class</i> and <i>Content</i> names are called the base of the variable and should be used for variables
which are specific for a certain module, function or page view. Variables such as <i>SearchValue</i> should
be used for global values, all though you can also use <i>Global</i> as the base.
</p>

<h2>Global variables</h2>
<p>Global variables should be named uniquely by using the class name as the base of the name. For instance
instance variables and global lists for classes.</p>
<pre class="example">
$instance =& $GLOBALS['eZUserInstance'];
$handlers =& $GLOBALS['eZCodecHandlers'];
</pre>

<h1>Functions</h1>
<p>
All functions should be placed in classes unless there is a good reason not to.
</p>
<p>
Functions should be short and do one thing. The length of a function depends on the complexity of the function,
the more complex the function is the shorter should the function be. If it gets too complex then divide the
function up into helper functions.
</p>
<p>
The number of local variables in a function should not be larger than 7. 7 is a known limit to the number of
things people can control at one time. Split up the function if it gets too large.
</p>
<p>
Default parameters should be used with care.
</p>
<p>
You should let the parameter line go over several lines with grouping of the similar parameters, if it gets long.
</p>
<h1>Classes</h1>
<p>
Classes should have one responsibility. All member variables are private.
Helper functions should also be private.
Functions that do not access member variables should be static.
</p>
<h1>Commenting</h1>
<p>
The comments used should be written to comply with the syntax of the Doxygen tool. This tool, which is used
to generate class reference documentation, is available at <a href="http://ez.no/sdk">http://ez.no/sdk</a>.
</p>

<pre class="example">

/*! \defgroup eZGroup Grouped classes
    Use groups to place common classes together.
*/

/*!
  \class eZMyClass ezmyclass.php
  \brief eZMyClass handles ...
  \ingroup eZGroup

  This is a more complete description of the class.

  Here is an code example:
  \code
  $myObj = new MyClass();
  \endcode

  See also theese classes:

  \sa eZUser, eZContentClass
  \todo Add this function..
  \bug Does not handle...
*/

class MyClass
{
    /*!
      This is the contructor of MyClass. It handles....
    */
    function MyClass()
    {
        ...
    }

    /*!
      \private
      This is a private helper function.
    */
    function doSomething()
    {
        ...
    }

    /*!
     \return the value of ...
    */
    function value()
    {
    }

    /*!
     \static
     This can be called like MyClass::doIt()
    */
    function doIt()
    {
        ...
    }

    //// \privatesection
    /// This is a variable description.
    var $MyVar;
}
</pre>
