<h1>Attributes</h1>

<p>Attributes are a way of accessing data in objects and arrays.
The attribute is an identifier that will lookup either the index number or the key in an array
or the defined attribute in an object. The access method for arrays and objects are similar
which means that the template code can be fed an array or an object and the code wouldn't know the
difference.
</p>

<h2>Arrays</h2>
<p>
Array elements have two types of attributes, numerical indices or identifiers.
</p>
<h3>Index lookup</h3>
<pre class="example">{$array.1}
{$array.24}
</pre>
<h3>Identifier lookup</h3>
<pre class="example">{$array.first}
{$array.a.b.c.d.e.f}
</pre>

<h2>Objects</h2>
<p>
For objects to be able to handle attribute access the class must implement these following functions.
<ul>
<li>bool <b>hasAttribute</b>( string <i>name</i> )</li>
<li>mixed &<b>attribute</b>( string <i>name</i> )</li>
</ul>
It also recommended, but not necessary, to implement these functions.
<ul>
<li>array <b>attributes</b>()</li>
<li><b>setAttribute</b>( string <i>name</i>, mixed <i>value</i> )</li>
</ul>
</p>

<h2>Example</h2>
<p>
Here's an example of how to implement attribute support.
</p>
<pre class="example">class eZItem
{
    function attributes()
    {
        return array( "first", "second" );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, eZItem::attributes() );
    }

    function &attribute( $name )
    {
        if ( $name == "first" )
            return $this->First;
        else if ( $name == "second" )
            return $this->Second;
        return null;
    }

    function setAttribute( $name, $value )
    {
        if ( $name == "first" )
            $this->First = $value;
        else if ( $name == "second" )
            $this->Second = $value;
    }

    var $First;
    var $Second;
}
</pre>

<h2>Access methods</h3>
<p>
It's possible to access attributes using two different methods, this is shown below. The first uses the
<b>.</b> operator and an identifier name or numerical index. The second uses the <b>[</b> and <b>]</b>
brackets to enclose a new statement for which the result is used to do the lookup.
</p>
<h3>. operator</h3>
<pre class="example">{$array.1}
{$array.first.last}
</pre>
<h3>[] operator</h3>
<pre class="example">{$array[1]}
{$array[first][last]}
</pre>
<p>
It's also possible to use another variable as lookup, doing this is best suited with the <b>[]</b> brackets.
</p>
<pre class="example">
{$array.$index}
{$array[$iterator.key]}
</pre>

<?php

$Result = array( 'title' => 'Attributes' );

?>
