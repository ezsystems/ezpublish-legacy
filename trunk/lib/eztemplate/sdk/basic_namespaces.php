<?php

$Result = array( 'title' => 'Namespaces' );

print( "<h1>Namespaces</h1>
<p>Namespaces allow separation of variables with the same name. This is most useful for
functions that create variables on the fly (e.g. <i>section</i>).
</p>

<p>
Templates have two namespaces,
one is called the root namespace and defines the global namespace for one template, the other
is the current namespace. The current namespace will start out being the same as the root but
will change when a function is used which has namespace support. The current namespace is
used by functions to create new variables and avoid clashes, while the root namespace is
used to lookup the correct variable.
</p>

<p>
The following table displays how the root and current namespace behaves.
</p>

<table class=\"example\">
<tr><th>Code</th><th>Root&nbsp;&nbsp;</th><th>Current</th></tr>
<tr>
  <td><pre>{\$variable}</pre></td>
  <td>First</td>
  <td>First</td>
</tr>
<tr>
  <td><pre>{section name=Space}</pre></td>
  <td>First</td>
  <td>First:Space</td>
</tr>
<tr>
  <td><pre>  {\$variable}</pre></td>
  <td>First</td>
  <td>First:Space</td>
</tr>
<tr>
  <td><pre>  {\$First:Space:item}</pre></td>
  <td>First</td>
  <td>First:Space</td>
</tr>
<tr>
  <td><pre>  {section name=Room}</pre></td>
  <td>First</td>
  <td>First:Space:Room</td>
</tr>
<tr>
  <td><pre>    {\$First:Space:Room:item}</pre></td>
  <td>First</td>
  <td>First:Space:Room</td>
</tr>
<tr>
  <td><pre>  {/section}</pre></td>
  <td>First</td>
  <td>First:Space</td>
</tr>
<tr>
  <td><pre>{/section}</pre></td>
  <td>First</td>
  <td>First</td>
</tr>
</table>

<h2>Relative and absolute addressing</h2>
<p>
To address a variable in the current namespace, you can use a \$: (dollar colon). Use \$# (dollar hash)
to address a variable in the root namespace (absolute addressing).
</p>

<pre class=\"example\">
{let var='hi all!'}
  {section name=Space}
    {* Local namespace *}
    {\$Space:item.first}
    {* Also local namespace *}
    {\$:item.first}
    {* Root namespace *}
    {\$#var}
  {/section}
{/let}
</pre>
" );

?>
