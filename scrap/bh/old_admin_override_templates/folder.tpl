{* Folder admin view template *}
{default with_children=true()
         is_editable=true()
         is_standalone=true()}
{let page_limit=15
     list_count=and( $with_children, fetch( content, list_count, hash( parent_node_id, $node.node_id ) ) )}
{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}

<div class="objectheader">
    <h2>{$node_name|wash}</h2>
</div>

<div class="object">
    <p>{attribute_view_gui attribute=$node.object.data_map.short_description}</p>

    <p>{attribute_view_gui attribute=$node.object.data_map.description}</p>
</div>

{section show=$is_standalone}
    {section name=ContentAction loop=$content_object.content_action_list show=$content_object.content_action_list}
        <div class="block">
            <input type="submit" name="{$ContentAction:item.action}" value="{$ContentAction:item.name|wash}" />
        </div>
    {/section}
{/section}


{/default}
{/let}
{/default}
