<h1>Test of different variable operators</h1>

<h2>Display of variable content</h2>
{$var1|attribute(,,show)}

<h2>Creating new types</h2>
<p>Creating an array</p>
{array(1,5,"test")|attribute(,,show)}
<p>Creating booleans with nested operators and displaying them with the <i>choose</i> operator</p>
{section loop=array(true(),false())}
{$item|choose("false","true")}<br>
{/section}

<h2>Multiple serialized operators</h2>
{array(1,2)|gt(1)|choose("Tiny array","Greater than one")}

