{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}

<b>product</b>

{$node_name|wash}

{attribute_view_gui attribute=$content_version.data_map.name}
<p>
{attribute_view_gui attribute=$content_version.data_map.description}
</p>
<b>{attribute_view_gui attribute=$content_version.data_map.product_nr}</b>

{attribute_view_gui attribute=$content_version.data_map.name}
{attribute_view_gui attribute=$content_version.data_map.name}



{$content_object.published|l10n(date)}

{$content_object.published|l10n(time)}
{$content_object.published|l10n(datetime)}

<table border="2">
<tr>
{section name="FirstName" loop=20 sequence=array(bglight,bgdark,234,23,42,34,234) }
<td class="{$FirstName:sequence}">
DEtte er innhold
</td>

{delimiter modulo=2}
</tr>
<tr>
{/delimiter}
{/section}
</tr>
</table>
banner
<table border="2">
<tr>
{section name="TH" loop=20}
<td>
DEtte er innhold
</td>

{delimiter modulo=6}
</tr>
<tr>
{/delimiter}
{/section}
</tr>
</table>

{$children_count}

{/default}