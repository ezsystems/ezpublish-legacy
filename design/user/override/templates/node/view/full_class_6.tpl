<form method="post" action="/content/action/">

<h1>{$node.name}</h1>

<table width="100%">
<tr>
    <th bgcolor="#ffffff">
    {"Topic"|i18n}
    </th>
    <th bgcolor="#ffffff">
    {"Posted"|i18n}
    </th>
</tr>
{section name=Message loop=fetch('content','list',hash(parent_node_id,$node.node_id,limit,$page_limit,offset,$view_parameters.offset)) sequence=array(bglight,bgdark)}
<tr>
    <td bgcolor="#efefef" width="60%">
    <a href={concat('content/view/full/',$Message:item.node_id,'/')|ezurl}>{$Message:item.name}</a>
    </td>
    <td bgcolor="#efefef" width="40%">
    {$Message:item.object.published|l10n(datetime)}
    </td>
</tr>
{/section}

</table>


<input type="hidden" name="NodeID" value="{$node.node_id}" />
<input class="button" type="submit" name="NewButton" value="{"New topic"|i18n}" />
<input type="hidden" name="ClassID" value="8" />

<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />

</form>