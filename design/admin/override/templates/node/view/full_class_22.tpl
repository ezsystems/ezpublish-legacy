
{let children=fetch('content',list,hash(parent_node_id,$node.node_id,sort_by,array(array(class_name,false()),array(published))))} 

<h1>{$node.name}</h1> 

{* loop children and print name *} 
{section name=Child loop=$children} 
{$Child:item.name}<br> 
{/section} 

{/let} 
