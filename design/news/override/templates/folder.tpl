<div id="folder">

<form method="post" action={"content/action"|ezurl}>

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />

<h1>{$node.name|wash}</h1>

<div class="children">
{let page_limit=20
    children=fetch('content','list',hash( parent_node_id, $node.node_id,
                                          sort_by ,$node.sort_array,
                                          limit, $page_limit,
                                          offset, $view_parameters.offset,
                                          class_filter_type, 'exclude',
                                          class_filter_array, array( 'folder', 'info_page' ) ))
    list_count=fetch('content','list_count',hash(parent_node_id,$node.node_id,
                                                 class_filter_type, 'exclude',
						 class_filter_array, array( 'folder', 'info_page' )))}

{section name=Child loop=$children sequence=array(bglight,bgdark)}
<div class="child">
{node_view_gui view=line content_node=$Child:item}
</div>
{/section}

{include name=navigator
    uri='design:navigator/google.tpl'
    page_uri=concat('/content/view','/full/',$node.node_id)
    item_count=$list_count
    view_parameters=$view_parameters
    item_limit=$page_limit}

{/let}
</div>


</form>
</div>