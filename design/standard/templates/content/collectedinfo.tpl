<h1>Collected info</h1>

<h2>{$collection.object.name}</h2>


{let class_attribute=$collection.object.data_map.new_attribute1
     total_count=fetch('content','collected_info_count', hash( 'object_id', $collection.object.id ) )}

<table width="300" cellspacing="0">
<tr>
{section name=Poll loop=$class_attribute.content.option_list}

{let item_count=fetch('content','collected_info_count', hash( 'object_id', $collection.object.id, 'value', $:item.id  ) ) }
<td>
{$:item.value}:
<table width="100%">
<tr>
<td bgcolor="ffff00" width="{div(mul($Poll:item_count,300),$total_count)}">
&nbsp;
</td>
<td bgcolor="eeeeee" width="{sub(300,div(mul($Poll:item_count,300),$total_count))}">

</td>
</tr>
</table>
</td>
{/let}
{delimiter}
</tr>
<tr>
{/delimiter}
{/section}
</tr>
</table>
Total: {$total_count}

{/let}

<br />
<b>You entered:</b>
{section name=Collection loop=$collection.attributes}
{* {$:item|attribute(show)}<br> *}
{$:item.data_text}<br>
{/section}

