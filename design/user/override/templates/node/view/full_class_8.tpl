{default with_children=true()
         is_editable=true()
	 is_standalone=true()
         content_object=$node.object
         content_version=$node.contentobject_version_object}

{section show=$is_standalone}
<form method="post" action={"content/action/"|ezurl}>
{/section}

{section show=$with_children}
<table width="100%" cellspacing="1" cellpadding="4" bgcolor="#000000">
<tr>
    <th bgcolor="#FF9900" width="70%" valign="top">
    <span class="small">
    {attribute_view_gui attribute=$content_version.data_map.topic}
    </span>
    </th>
    <td bgcolor="#FF9900" align="rigtht" width="30%" valign="top">
    <span class="small">{$content_object.published|l10n(shortdatetime)}</span>
    </td>
</tr>
<tr>
    <td bgcolor="#FFFFFF" colspan="2">
    <span class="small">
    {attribute_view_gui attribute=$content_version.data_map.message}
    </span>
    </td>
</tr>
</table>
<br />

{section name=Child loop=fetch('content','list',hash(parent_node_id,$node.node_id,limit,$page_limit,offset,$view_parameters.offset))  sequence=array(FDF4D9,FDF1CE)}
<table width="100%" cellspacing="1" cellpadding="4" bgcolor="#000000">
<tr>
    <th bgcolor="#{$Child:sequence}"  width="70%" valign="top">
    <span class="small">
    {attribute_view_gui attribute=$Child:item.object.data_map.topic}
    </span>
    </th>
    <td bgcolor="#{$Child:sequence}" align="rigtht" width="30%" valign="top">
    <span class="small">{$Child:item.object.published|l10n(shortdatetime)} by
    by {$Child:item.object.owner.name}</span>
    </td>
</tr>
<tr>
    <td bgcolor="#{$Child:sequence}" colspan="2">
    <span class="small">
    {attribute_view_gui attribute=$Child:item.object.data_map.message}
    </span>
    </td>
</tr>
</table>
<br />
{/section}

{/section}

{section show=$is_editable}
<br />
<input type="hidden" name="NodeID" value="{$node.node_id}" />
<input class="button" type="submit" name="NewButton" value="{'New reply'|i18n('forummessage')}" />
<input type="hidden" name="ClassID" value="8" />

<input type="hidden" name="ContentObjectID" value="{$content_object.id}" />
{/section}

{section show=$is_standalone}
</form>
{/section}

{/default}
