<form method="post" action="/content/action/">

<h1>{$node.name}</h1>

<table bgcolor="#ffffff" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <th width="70%" valign="top">
    {attribute_view_gui attribute=$node.object.data_map.topic}
    </th>
    <th align="rigtht" width="30%" valign="top">
    {$node.object.published|l10n(datetime)}
    </th>
</tr>
<tr>
    <td colspan="2">
    {attribute_view_gui attribute=$node.object.data_map.message}
    </td>
</tr>
</table>


{section name=Child loop=fetch('content','list',hash(parent_node_id,$node.node_id,limit,$page_limit,offset,$view_parameters.offset)) sequence=array(efefef,ffffff)}
<table bgcolor="#{$Child:sequence}" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <th width="70%" valign="top">
    {attribute_view_gui attribute=$Child:item.object.data_map.topic}
    </th>
    <th align="rigtht" width="30%" valign="top">
    {$Child:item.object.published|l10n(datetime)}
    </th>
</tr>
<tr>
    <td colspan="2">
    {attribute_view_gui attribute=$Child:item.object.data_map.message}
    </td>
</tr>
</table>
{/section}


<input type="hidden" name="NodeID" value="{$node.node_id}" />
<input class="button" type="submit" name="NewButton" value="{"New reply"|i18n}" />
<input type="hidden" name="ClassID" value="8" />

<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />

</form>
