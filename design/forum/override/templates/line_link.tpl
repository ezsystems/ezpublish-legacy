{* Link template *}

{default content_version=$node.contentobject_version_object
         node_name=$node.name}

<table class="linklist" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th class="linkheader">
    {$node_name|wash}
    </th>
</tr>
<tr>
    <td class="linkcontent">
    {attribute_view_gui attribute=$content_version.data_map.description}
    <label>URL:</label><div class="labelbreak"></div>
    {attribute_view_gui attribute=$content_version.data_map.link}
    </td>
</tr>

</table>
<br />

{/default}