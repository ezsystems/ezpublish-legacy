{include uri="design:content/path.tpl" items=$parents base_uri=$module.functions.view.uri} {$object.name}
<h1>{attribute_view_gui attribute=$object.data_map.name}</h1>

<table align="center" bgcolor="black" cellpadding="15" cellspacing="0">
<tr>
	<td>
	{attribute_view_gui attribute=$object.data_map.image}
	</td>
</tr>
<tr>
	<td>
	<font color="white">
	{attribute_view_gui attribute=$object.data_map.caption}
	</font>
	</td>
</tr>
</table>