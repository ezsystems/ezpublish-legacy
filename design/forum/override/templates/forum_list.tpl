<div class="children">
{let page_limit=20
    children=fetch('content','list',hash( parent_node_id, $node.node_id,
                                          sort_by ,$node.sort_array,
                                          limit, $page_limit,
                                          offset, $view_parameters.offset,
                                          class_filter_type, 'exclude',
                                          class_filter_array, array( 'info_page' ) ))
    list_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}

{section name=Child loop=$children sequence=array(bglight,bgdark)}
<div class="forum_level1">
<table class="forum" cellspacing="0">
<tr>
    <th colspan="2">
    Topics
    </th>
    <th>
    Posts
    </th>
    <th>
    Replies
    </th>
</tr>
{node_view_gui view=forumline content_node=$Child:item}
</table>
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
