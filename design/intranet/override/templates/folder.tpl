<h1>{$node.name}</h1>

<h2>Name</h2>
{attribute_view_gui attribute=$node.object.data_map.name}

<h2>Description</h2>
{attribute_view_gui attribute=$node.object.data_map.description}

{let page_limit=20
    children=fetch('content','list',hash(parent_node_id,$node.node_id,sort_by,$node.sort_array,limit,$page_limit,offset,$view_parameters.offset))    list_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}

{section name=Child loop=$children sequence=array(bglight,bgdark)}
{node_view_gui view=line content_node=$Child:item}
{/section}
{include name=navigator
    uri='design:navigator/google.tpl'
    page_uri=concat('/content/view','/full/',$node.node_id)
    item_count=$list_count
    view_parameters=$view_parameters
    item_limit=$page_limit}
