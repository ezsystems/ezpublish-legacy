<?php

include_once( "lib/eztemplate/classes/eztemplate.php" );
include_once( "lib/eztemplate/classes/eztemplatesectionfunction.php" );
include_once( "lib/eztemplate/classes/eztemplatearrayoperator.php" );
include_once( "lib/eztemplate/classes/eztemplatelogicoperator.php" );

print( "<p>The <i>section</i> is the most versatile and most used function in the template engine.
It allows for looping over arrays and numeric ranges, conditional control of blocks and sequences.
It is controlled by a series of input parameter and sub children functions.</p>
<h2>Explanation of input parameters</h2>
<h3>name</h3>
<p>Defines the namespace for all generated template variables, see <i>loop</i> and <i>sequence</i> for
a list of generated variables.</p>
<h3>loop</h3>
</p>Defines the data which the section should loop over, each the time the section loops it sets an
template variables and appends the result of all it's children to the output. The data can either
be an array in which case each item in the array is traversed, or a number which determines the
number of iterations (negative numbers makes the iteration go backwards).</p>
<p>It's possible to constrain the number of elements which is iterated as well as single elements,
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
of template code depending on a variable. when the <i>section-else</i> block is used no looping is done.</p>
<h3>sequence</h3>
<p>Defines a sequence which is iterated as the normal <i>loop</i> parameter, the difference is that the
sequence will wrap and only supports arrays. The current item will be set in the <i>sequence</i> template
variable. This parameter is useful if you want to create alternating colors in lists for instance.</p>
<h3>max</h3>
<p>Determines the maximum number of iterations, the value must be an integer or an array, if it's an array
the count of the array is used.</p>
<h3>offset</h3>
<p>Determines the start of the loop array for the iterations, the value must be an integer or an array, if it's an array
the count of the array is used.</p>
<h2>Explanation of sub children functions</h2>
<h3>section-else</h3>
<p>Determines the start of the alternative block which is shown when <i>show</i> is false. See above.</p>
<h3>delimiter</h3>
<p>Determines a block of template elements which should be placed inbetween two iterations.</p>
<h3>section-exclude and section-include</h3>
<p>Adds a new filter rule for excluding or including a loop item, the rules will be run
after one-another as they are found. The rule will read the <i>match</i> parameter and
change the current accept/reject state for the current item, the default is to accept
all items. The match parameter can match any template variable available including loop iterators,
keys and items, but not the loop sequence.</p>
<p>Sequences and iteration counts will not be advanced if the loop item is discarded.</p>
" );

// Init template
$tpl =& eZTemplate::instance();
$tpl->setShowDetails( true );

$tpl->registerFunctions( new eZTemplateSectionFunction() );
$tpl->registerOperators( new eZTemplateArrayOperator() );
$tpl->registerOperators( new eZTemplateLogicOperator() );

$tpl->setVariable( "numbers", array( 1001, 1002, 1003 ) );
$tpl->setVariable( "assoc", array( "red" => "Red", "green" => "Green", "blue" => "Blue" ) );
$tpl->setVariable( "items", array( array( "uri" => "http://ez.no",
                                          "name" => "eZ home" ),
                                   array( "uri" => "http://zez.org",
                                          "name" => "ZeZ" ),
                                   array( "uri" => "http://developer.ez.no",
                                          "name" => "eZ developer" ) ),
                   "menu" );
$tpl->setVariable( "show_list", false );

$tpl->display( "lib/eztemplate/sdk/templates/section.tpl" );

eZDebug::addTimingPoint( "Template end" );

?>
