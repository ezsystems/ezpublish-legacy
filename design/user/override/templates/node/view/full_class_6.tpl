{default with_children=true()
         is_editable=true()
	 is_standalone=true()
         content_object=$node.object}

{section show=$is_standalone}
<form method="post" action={"/content/action/"|ezurl}>
{/section}

{section show=$with_children}
<table width="100%" cellspacing="1" cellpadding="4" bgcolor="#000000">
<tr>
    <th bgcolor="#FF9900">
    <span class="small">{"Topic"|i18n}</span>
    </th>
    <th bgcolor="#FF9900">
    <span class="small">{"Posted"|i18n}</span>
    </th>
</tr>
{section name=Message loop=fetch('content','list',hash(parent_node_id,$node.node_id,limit,$page_limit,offset,$view_parameters.offset)) sequence=array(FDF4D9,FDF1CE)}
<tr>
    <td bgcolor="#{$Message:sequence}" width="60%">
    <span class="small">
    <a href={concat('content/view/full/',$Message:item.node_id,'/')|ezurl}>{$Message:item.name}</a>
    {let list_count=fetch('content','tree_count',hash(parent_node_id,$Message:item.node_id))}
     ( {$Message:list_count} )
    {/let}
    
    </span>
    </td>
    <td bgcolor="#{$Message:sequence}" width="40%">
    <span class="small">{$Message:item.object.published|l10n(shortdatetime)} <br />
    by {$Message:item.object.owner.name}
    </span>
    </td>
</tr>
{/section}

</table>
{/section}


{section show=$is_editable}
<br />
<input type="hidden" name="NodeID" value="{$node.node_id}" />
<input class="button" type="submit" name="NewButton" value="{"New topic"|i18n}" />
<input type="hidden" name="ClassID" value="8" />

<input type="hidden" name="ContentObjectID" value="{$content_object.id}" />
{/section}

{section show=$is_standalone}
</form>
{/section}

{/default}