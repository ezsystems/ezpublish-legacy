<ul class="forumlist">
    {section name=Child loop=fetch('content','tree',hash(parent_node_id,$object.main_node_id,limit,5,sort_by,array(array('modified',false()))))}
    <li>
    {switch match=$Child:item.object.contentclass_id}
    {case match=19}
    <a href={concat($Child:item.parent.url_alias,'#msg',$Child:item.node_id)|ezurl}>{$Child:item.name|wash}</a>
    {/case}

    {case}
    {section show=eq("Forum message",$Child:item.object.class_name)}
    {section show=eq($Child:item.depth,7)}
    <a href={concat($Child:item.parent.url_alias,'#msg',$Child:item.node_id)|ezurl}>{$Child:item.name|wash}</a>
    {section-else}
    <a href={$Child:item.url_alias|ezurl}>{$Child:item.name|wash}</a>
    {/section}

    {section-else}
    <a href={$Child:item.url_alias|ezurl}>{$Child:item.name|wash}</a>
    {/section}
    {/case}
    {/switch}
    by {$Child:item.object.owner.name} 
    </li>
    {/section}
</ul>

