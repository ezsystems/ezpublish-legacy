<?php

// include_once( "lib/eztemplate/classes/eztemplate.php" );
// include_once( "lib/eztemplate/classes/eztemplatesequencefunction.php" );
// include_once( "lib/eztemplate/classes/eztemplatesectionfunction.php" );
// include_once( "lib/eztemplate/classes/eztemplatearrayoperator.php" );
// include_once( "lib/eztemplate/classes/eztemplatelogicoperator.php" );

print( "<h1>Sequence</h1>
<p>The <i>sequence</i> function allows for creating a sequence which is iterated over
and wrapped around when the end is reached. This is particularly useful when you want to
alternate colors in a list. It's possible to create multiple sequences and advance them
individually.</p>

<pre class='example'>
{sequence name=Seq loop=array(a,b,c)}

&lt;table&gt;
&lt;tr&gt;&lt;th&gt;Sequence value&lt;/th&gt;&lt;th&gt;Section item&lt;/th&gt;&lt;/tr&gt;
{section name=Loop1 loop=array(1,2,3,4,5,6)}

&lt;tr&gt;&lt;td&gt;{\$Seq:item}&lt;/td&gt;&lt;td&gt;{\$Loop1:item}&lt;/td&gt;&lt;/tr&gt;

{* Next sequence *}
{sequence name=Seq}
{/section}
&lt;/table&gt;
</pre>

<p>The above example will return this result (the sequence a, b, c is repeated):</p>

<pre class='example'>
Sequence value   Section item
a                1
b                2
c                3
a                4
b                5
c                6
</pre>
" );

// // Init template
// $tpl =& eZTemplate::instance();
// $tpl->setShowDetails( true );

// $tpl->registerFunctions( new eZTemplateSectionFunction() );
// $tpl->registerFunctions( new eZTemplateSequenceFunction() );
// $tpl->registerOperators( new eZTemplateArrayOperator() );
// $tpl->registerOperators( new eZTemplateLogicOperator() );

// $tpl->setVariable( "numbers", array( 1001, 1002, 1003 ) );

// $tpl->display( "lib/eztemplate/sdk/templates/sequence.tpl" );

// eZDebug::addTimingPoint( "Template end" );

?>
