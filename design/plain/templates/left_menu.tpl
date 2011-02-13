{let children=fetch('content',list,hash(parent_node_id,2))}

{* loop children and print name with link *}
{section name=Child loop=$children}
<a href={$Child:item.url_alias|ezurl}>{$Child:item.name|wash}</a><br>
{/section}

{/let}
