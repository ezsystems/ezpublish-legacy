<?php

$Result = array( 'title' => 'Namespaces' );

print( "<p>Namespaces allows separation of variables with the same name. This is most useful for
functions which creates variables on the fly (e.g. <i>section</i>).
</p>
<p>
Templates has two namespaces,
one is called the root namespace and defines the global namespace for one template, the other
is the current namespace. The current namespace will start out being the same as the root but
will change when a function is used which has namespace support. The current namespace is
used by functions to create new variables and avoid clashes, while the root namespace is
used to lookup the correct variable ( That's why variable use must always include the full namespace* ).
</p>

<p>
The following table displays how the root and current namespace behaves.
</p>

<table class=\"example\">
<tr><th>Code</th><th>Root</th><th>Current</th></tr>
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

<h2>The using function</h2>
<p>
The <i>using</i> function allows for changing the root namespace, the current root namespace will be stored
and restored when the function is done. This is useful when you have a lot of namespaces and need to access
variables with their full namespace
</p>

<pre class=\"example\">
{section name=Space}
  {using name=Space}
    {\$item.first}
    {\$item.second}
    {\$item.third}
  {/using}
  {\$Space:item}
{/section}
</pre>

<p class=\"footnote\">* A special syntax might be added to support local variable lookup</p>
" );

?>
