<div class="folder">

<form method="post" action={"content/action"|ezurl}>

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />

<h1>{$node.name|wash}</h1>

{let folder_list=fetch('content','list',hash( parent_node_id, $node.node_id,
                                          sort_by ,$node.sort_array,
                                          class_filter_type, 'include',
                                          class_filter_array, array( 'folder' ) ))}
{section show=$folder_list|is_set()}
    <div class="subfolderlist">
    <ul>
    {section name=folders loop=$folder_list sequence=array(bglight,bgdark)}
        <li><a href={$:item.url_alias|ezurl}>{$:item.name|wash}</a></li>
    {/section}
    </ul>
    </div>
{/section}

{/let}

{section show=$node.object.data_map.description|ne()}
<div class="folderdescription">
{attribute_view_gui attribute=$node.object.data_map.description}
</div>
{/section}

<div class="children">
{let page_limit=20
    children=fetch('content','list',hash( parent_node_id, $node.node_id,
                                          sort_by ,$node.sort_array,
                                          limit, $page_limit,
                                          offset, $view_parameters.offset,
                                          class_filter_type, 'exclude',
                                          class_filter_array, array( 'folder', 'info_page' ) ))
    list_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}

{section name=Child loop=$children sequence=array(bglight,bgdark)}
       {node_view_gui view=line content_node=$Child:item}
{/section}

{include name=navigator
    uri='design:navigator/google.tpl'
    page_uri=concat('/content/view','/full/',$node.node_id)
    item_count=$list_count
    view_parameters=$view_parameters
    item_limit=$page_limit}

{/let}
</div>


{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}

<div class="buttonblock">

{section show=$content_object.can_create}
         <input type="hidden" name="NodeID" value="{$node.node_id}" />
         <select name="ClassID">
              {section name=Classes loop=$content_object.can_create_class_list}
              <option value="{$:item.id}">{$:item.name|wash}</option>
              {/section}
         </select>
         <input class="button" type="submit" name="NewButton" value="{'Create here'|i18n('design/standard/node/view')}" />
{/section}
</div>

{/default}

</form>
</div>