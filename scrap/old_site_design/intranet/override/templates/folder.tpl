<div id="folder">

<form method="post" action={"content/action"|ezurl}>

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />

{*
{section show=$node.object.can_edit}
<div class="editbutton">
   <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
</div>
{/section}
*}
<h1>{$node.name|wash}</h1>


<div class="object_content">
{attribute_view_gui attribute=$node.object.data_map.description}
</div>

<div class="children">
{*
{let folder_list=fetch('content','list',hash( parent_node_id, $node.node_id,
                                          sort_by ,$node.sort_array,
                                          class_filter_type, 'include',
                                          class_filter_array, array( 'folder' ) ))}
{section show=$folder_list|is_set()}
    <div class="subfolders">
    {section name=folders loop=$folder_list sequence=array(bglight,bgdark)}
        <div class="{$folders:sequence}">
            <p><a href={$:item.url_alias|ezurl}>{$:item.name|wash}</a></p>
        </div>
    {/section}
    </div>
{/section}

{/let}

*}

{let page_limit=20
    children=fetch('content','list',hash( parent_node_id, $node.node_id,
                                          sort_by ,$node.sort_array,
                                          limit, $page_limit,
                                          offset, $view_parameters.offset,
                                          class_filter_type, 'exclude',
                                          class_filter_array, array( 'folder', 'info_page' ) ))
    list_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}

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


{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}

</form>
<div class="buttonblock">
{section show=$content_object.can_create}

{section show=$node.path_array[2]|eq(62)}
<form method="post" action={"content/action"|ezurl}>
         <input type="hidden" name="NodeID" value="{$node.node_id}" />
         <input type="hidden" name="ClassID" value="12" />
         <input class="button" type="submit" name="NewButton" value="{'New file'|i18n('design/standard/node/view')}" />
</form>
{/section}


{section show=$node.path_array[2]|eq(50)}
<form method="post" action={"content/action"|ezurl}>
         <input type="hidden" name="NodeID" value="{$node.node_id}" />
         <input type="hidden" name="ClassID" value="2" />
         <input class="button" type="submit" name="NewButton" value="{'New article'|i18n('design/standard/node/view')}" />
</form>
{/section}

{section show=$node.path_array[2]|eq(55)}
<form method="post" action={"content/action"|ezurl}>
         <input type="hidden" name="NodeID" value="{$node.node_id}" />
         <input type="hidden" name="ClassID" value="17" />
         <input class="button" type="submit" name="NewButton" value="{'New person'|i18n('design/standard/node/view')}" />
</form>

<form method="post" action={"content/action"|ezurl}>
         <input type="hidden" name="NodeID" value="{$node.node_id}" />
         <input type="hidden" name="ClassID" value="16" />
         <input class="button" type="submit" name="NewButton" value="{'New company'|i18n('design/standard/node/view')}" />
</form>

{/section}

{/section}

</div>

{/default}


</div>