<div id="folder">

<form method="post" action={"content/action"|ezurl}>

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />

{section show=$node.object.can_edit}
<div class="editbutton">
   <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
</div>
{/section}

<h1>{$node.name|wash}</h1>


<div class="object_content">
{attribute_view_gui attribute=$node.object.data_map.description}
</div>

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