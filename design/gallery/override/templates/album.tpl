<div id="album">

<form method="post" action={"content/action"|ezurl}>

<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />

{section show=$node.object.can_edit}
<div class="editbutton">
   <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
</div>
{/section}

<div class="object_title">
    <h1>{$node.name}</h1>
</div>

{attribute_view_gui attribute=$node.object.data_map.description}

</form>

<div id="image_thumb">
{let image_count=fetch( content, list_count, hash( parent_node_id, $node.node_id,
                                                   class_filter_type, include,
						   class_filter_array, array( 5 ) ) )
     image_list=fetch( content, list, hash( parent_node_id, $node.node_id,
                                            class_filter_type, include,
                                            class_filter_array, array( 5 ),
                                            sort_by, array( 'published', false() ) ) )}

  {section var=image loop=$image_list}
    {node_view_gui view=thumbnail content_node=$image.item}
  {/section}
</div>

{/let}

</div>
