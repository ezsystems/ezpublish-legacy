<?php

// include_once( "lib/eztemplate/classes/eztemplate.php" );
// include_once( "lib/eztemplate/classes/eztemplatesectionfunction.php" );
// include_once( "lib/eztemplate/classes/eztemplatearrayoperator.php" );
// include_once( "lib/eztemplate/classes/eztemplatelogicoperator.php" );

print( "<h1>Section</h1>
<p>The <i>section</i> is the most versatile and most used function in the template engine.
It allows for looping over arrays and numeric ranges, and conditional control of blocks and sequences.
It is controlled by a series of input parameter and sub functions.</p>

<h2>Explanation of input parameters</h2>

<h3>name</h3>
<p>Defines the namespace for all generated template variables, see <i>loop</i> and <i>sequence</i> for
a list of generated variables.</p>

<h3>loop</h3>
</p>Defines the data that the section should loop over, each the time the section loops it sets a
template variable and appends the result of all its children to the output. The data can either
be an array, in which case each item in the array is traversed, or a number that determines the
number of iterations (a negative number makes the iteration go backwards).</p>
<p>It's possible to constrain the number of elements that is iterated as well as single elements,
see parameters <i>max</i> and <i>offset</i> and sub-children <i>section-exclude</i> and <i>section-include</i>.</p>
<p>Each time the section iterates it sets four template variables in the new namespace. The variables
are <i>index</i>, <i>number</i>, <i>key</i> and <i>item</i>.
<ul>
<li><i>index</i> - is a number which starts at 0 and increases for each iteration.</li>
<li><i>number</i> - same as <i>index</i> but starts at 1</li>
<li><i>key</i> - if an array is iterated the key of the current item is set, if a number it will be the same as <i>item</i></li>
<li><i>item</i> - if an array is iterated the current item is set, if a number the current iteration index is set (same as <i>index<i>)</li>
</ul>
</p>

<h3>show</h3>
<p>This parameter determines whether the <i>section</i> block should be shown or not. If the parameter
is not present or is true (either a boolean true, non-empty array or non-zero value) the <i>section</i> block
is shown, otherwise the <i>section-else</i> block is shown. This is quite useful for conditional inclusion
of template code depending on a variable. When the <i>section-else</i> block is used no looping is done.</p>

<h3>sequence</h3>
<p>Defines a sequence which is iterated as the normal <i>loop</i> parameter, the difference is that the
sequence will wrap and only supports arrays. The current item will be set in the <i>sequence</i> template
variable. This parameter is useful if you want to create alternating colors in lists, for instance.</p>

<h3>max</h3>
<p>Determines the maximum number of iterations, the value must be an integer or an array, if it's an array
the count of the array is used.</p>

<h3>offset</h3>
<p>Determines the start of the loop array for the iterations, the value must be an integer or an array,
if it's an array the count of the array is used.</p>

<h2>Explanation of sub children functions</h2>

<h3>section-else</h3>
<p>Determines the start of the alternative block which is shown when <i>show</i> is false. See above.</p>

<h3>delimiter</h3>
<p>Determines a block of template elements which should be placed in between two iterations.</p>

<h3>section-exclude and section-include</h3>
<p>Adds a new filter rule for excluding or including a loop item, the rules will be run
after one-another as they are found. The rule will read the <i>match</i> parameter and
change the current accept/reject state for the current item, the default is to accept
all items. The match parameter can match any template variable available including loop iterators,
keys and items, but not the loop sequence.</p>
<p>Sequences and iteration counts will not be advanced if the loop item is discarded.</p>

<h2>Examples</h2>

<pre class='example'>
&lt;h2&gt;Showing the template variables for different loop types&lt;/h2&gt;
&lt;p&gt;For each iteration you can see the template variables which are set by the section function,
they are &lt;b&gt;index&lt;/b&gt;:&lt;b&gt;number&lt;/b&gt;:&lt;b&gt;key&lt;/b&gt;&lt;/p&gt;

&lt;h3&gt;Looping an array of numbers&lt;/h3&gt;

{section name=Num loop=\$numbers offset=2 max=2}
{\$Num:index}:{\$Num:number}:{\$Num:key} Number: {\$Num:item}&lt;br/&gt;

{/section}

&lt;h3&gt;Looping an associative array&lt;/h3&gt;

{section name=Num loop=\$assoc}
{\$Num:index}:{\$Num:number}:{\$Num:key} Text: {\$Num:item}&lt;br/&gt;

{/section}

&lt;h3&gt;Iterating 5 times&lt;/h3&gt;

{section name=Num loop=5 sequence=array(red,blue)}
{section-exclude match=\$Num:item|gt(3)}
{section-exclude match=\$Num:item|lt(3)}
{section-include match=\$Num:item|lt(2)}
{\$Num:sequence}-{\$Num:index}:{\$Num:number}:{\$Num:key} Number: {\$Num:item}&lt;br/&gt;

{/section}

&lt;h3&gt;Iterating 5 times, backwards&lt;/h3&gt;

{section name=Num loop=-5}
{\$Num:index}:{\$Num:number}:{\$Num:key} Number: {\$Num:item}&lt;br/&gt;

{/section}

&lt;br/&gt;

&lt;h3&gt;Looping over a multi-dim array&lt;/h3&gt;
{* Looping over a multi-dim array and with a sequence *}
&lt;table&gt;
&lt;th&gt;URI&lt;/th&gt;&lt;th&gt;Name&lt;/th&gt;
{section name=Loop loop=\$menu:items sequence=array(odd,even)}
&lt;tr&gt;&lt;td&gt;{\$Loop:sequence} - {\$Loop:item.uri}&lt;/td&gt;&lt;td class={\$Loop:sequence}&gt;{\$Loop:item.name}&lt;/td&gt;&lt;/tr&gt;
{/section}
&lt;/table&gt;

{* This section is controlled by the show parameter, if true the section is used (in this case false) *}
&lt;p&gt;Show list={\$show_list|choose('off','on')}&lt;/p&gt;
&lt;p&gt;{section name=Loop loop=\$menu:items show=\$show_list}
{\$Loop:item.uri} : {\$Loop:item.name}&lt;br /&gt;
{/section}&lt;/p&gt;

{* This section will only show the {section-else} part since the show item is false *}
{section name=Loop show=0}
&lt;p&gt;abc {\$Loop:item} def&lt;/p&gt;
{section-else}
&lt;p&gt;Shown for zero or empty vars&lt;/p&gt;
{/section}

{* Numeric looping, also shows the use of the {delimiter} function *}
&lt;h2&gt;Loop 5 times&lt;/h2&gt;
{section name=Loop loop=5}
{\$Loop:item}
{delimiter}.{/delimiter}
{/section}

&lt;h2&gt;Loop 5 times negative&lt;/h2&gt;
{section name=Loop loop=-5}
{\$Loop:key}
{delimiter}::{/delimiter}
{/section}
</pre>

<h2>Results</h2>

<h2>Showing the template variables for different loop types</h2>
<p>For each iteration you can see the template variables which are set by the section function,
they are <b>index</b>:<b>number</b>:<b>key</b></p>

<h3>Looping an array of numbers</h3>
0:1:2 Number: 1003<br/>

<h3>Looping an associative array</h3>
0:1:red Text: Red<br/>
1:2:green Text: Green<br/>
2:3:blue Text: Blue<br/>

<h3>Iterating 5 times</h3>
red-0:1:0 Number: 1<br/>
blue-1:2:2 Number: 3<br/>

<h3>Iterating 5 times, backwards</h3>
0:1:0 Number: -1<br/>
1:2:-1 Number: -2<br/>
2:3:-2 Number: -3<br/>
3:4:-3 Number: -4<br/>
4:5:-4 Number: -5<br/>

<br/>

<h3>Looping over a multi-dim array</h3>

<table>
<th>URI</th><th>Name</th><tr><td>odd - http://ez.no</td><td class=odd>eZ home</td></tr><tr><td>even - http://zez.org</td><td class=even>ZeZ</td></tr><tr><td>odd - http://.ez.no/developer</td><td class=odd>eZ developer</td></tr></table>


<p>Show list=off</p>
<p></p>

<p>Shown for zero or empty vars</p>

<h2>Loop 5 times</h2>1.2.3.4.5
<h2>Loop 5 times negative</h2>0::-1::-2::-3::-4
" );

// // Init template
// $tpl =& eZTemplate::instance();
// $tpl->setShowDetails( true );

// $tpl->registerFunctions( new eZTemplateSectionFunction() );
// $tpl->registerOperators( new eZTemplateArrayOperator() );
// $tpl->registerOperators( new eZTemplateLogicOperator() );

// $tpl->setVariable( "numbers", array( 1001, 1002, 1003 ) );
// $tpl->setVariable( "assoc", array( "red" => "Red", "green" => "Green", "blue" => "Blue" ) );
// $tpl->setVariable( "items", array( array( "uri" => "http://ez.no",
//                                           "name" => "eZ home" ),
//                                    array( "uri" => "http://zez.org",
//                                           "name" => "ZeZ" ),
//                                    array( "uri" => "http://ez.no/developer",
//                                           "name" => "eZ developer" ) ),
//                    "menu" );
// $tpl->setVariable( "show_list", false );

// $tpl->display( "lib/eztemplate/sdk/templates/section.tpl" );

// eZDebug::addTimingPoint( "Template end" );

?>
