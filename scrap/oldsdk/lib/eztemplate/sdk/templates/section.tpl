<h2>Showing the template variables for different loop types</h2>
<p>For each iteration you can see the template variables which are set by the section function,
they are <b>index</b>:<b>number</b>:<b>key</b></p>
<h3>Looping an array of numbers</h3>

{section name=Num loop=$numbers offset=2 max=2}
{$Num:index}:{$Num:number}:{$Num:key} Number: {$Num:item}<br/>

{/section}

<h3>Looping an associative array</h3>

{section name=Num loop=$assoc}
{$Num:index}:{$Num:number}:{$Num:key} Text: {$Num:item}<br/>

{/section}

<h3>Iterating 5 times</h3>

{section name=Num loop=5 sequence=array(red,blue)}
{section-exclude match=$Num:item|gt(3)}
{section-exclude match=$Num:item|lt(3)}
{section-include match=$Num:item|lt(2)}
{$Num:sequence}-{$Num:index}:{$Num:number}:{$Num:key} Number: {$Num:item}<br/>

{/section}

<h3>Iterating 5 times, backwards</h3>

{section name=Num loop=-5}
{$Num:index}:{$Num:number}:{$Num:key} Number: {$Num:item}<br/>

{/section}

<br/>

<h3>Looping over a multi-dim array</h3>
{* Looping over a multi-dim array and with a sequence *}
<table>
<th>URI</th><th>Name</th>
{section name=Loop loop=$menu:items sequence=array(odd,even)}
<tr><td>{$Loop:sequence} - {$Loop:item.uri}</td><td class={$Loop:sequence}>{$Loop:item.name}</td></tr>
{/section}
</table>

{* This section is controlled by the show parameter, if true the section is used (in this case false) *}
<p>Show list={$show_list|choose("off","on")}</p>
<p>{section name=Loop loop=$menu:items show=$show_list}
{$Loop:item.uri} : {$Loop:item.name}<br />
{/section}</p>

{* This section will only show the {section-else} part since the show item is false *}
{section name=Loop show=0}
<p>abc {$Loop:item} def</p>
{section-else}
<p>Shown for zero or empty vars</p>
{/section}

{* Numeric looping, also shows the use of the {delimiter} function *}
<h2>Loop 5 times</h2>
{section name=Loop loop=5}
{$Loop:item}
{delimiter}.{/delimiter}
{/section}

<h2>Loop 5 times negative</h2>
{section name=Loop loop=-5}
{$Loop:key}
{delimiter}::{/delimiter}
{/section}
