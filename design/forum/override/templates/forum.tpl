{let child_list=fetch('content','list',hash(parent_node_id,$node.node_id,limit,20,offset,$view_parameters.offset,sort_by,array(array(published,false()))))
     child_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}

<div id="forum">
<h1>{$node.name}</h1>

{attribute_view_gui attribute=$node.object.data_map.description}



<table width="100%" cellspacing="0" cellpadding="0" border="1">
<tr>
    <td class="forumhead">
    Topics
    </td>
    <td class="forumhead">
    Replies
    </td>
    <td class="forumhead">
    Author
    </td>
</tr>

{section name=Child loop=$child_list sequence=array(bglightforum,bgdarkforum)}
<tr>
    <td class="{$Child:sequence}" valign="top">
    <p>
    <a href={$Child:item.url_alias|ezurl}>{$Child:item.object.name|wash}</a>
    {let last_reply=fetch('content','list',hash(parent_node_id,$Child:item.node_id,sort_by,array(array('published',false())),limit,1))}
    {section name=Reply loop=$Child:last_reply show=$Child:last_reply}
    <br /><br />
    Last reply:     <a href={concat($Child:Reply:item.parent.url_alias,'#msg',$Child:Reply:item.node_id)|ezurl}>{$Child:Reply:item.name|wash}</a><br />
<span class="forumdate">({$Child:Reply:item.object.published|l10n(shortdatetime)}) by {$Child:Reply:item.object.owner.name|wash}</span>

    {/section}
    {/let}
    </p>
    </td>
    <td class="{$Child:sequence}"  valign="top" align="center">
    <p>
    {fetch('content','tree_count',hash(parent_node_id,$Child:item.node_id))}
    </p>
    </td>
    <td class="{$Child:sequence}" valign="top">
    <p>
    {$Child:item.object.owner.name|wash}<br />
    <span class="forumdate">({$Child:item.object.published|l10n(shortdatetime)})</span>
    </p>
    </td>

</tr>
{/section}
</table>

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/view','/full/',$node.node_id)
         item_count=$child_count
         view_parameters=$view_parameters
         item_limit=20}

</div>

{/let}