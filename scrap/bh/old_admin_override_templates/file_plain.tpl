{* File admin plain view template *}

{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}

<div class="objectheader">
    <h2>{'File'|i18n('design/admin/node/view')}</h2>
</div>

<div class="object">
    <h1>{$node_name|wash}</h1>

    {attribute_view_gui attribute=$node.object.data_map.description}
    <p>{attribute_view_gui attribute=$node.object.data_map.file}</p>

    <h3>{'Placed in'|i18n('design/admin/node/view')}</h3>
    {section name=Parent loop=$content_object.parent_nodes}
        {let parent_node=fetch(content,node,hash(node_id,$:item))}
        {section name=Path loop=$:parent_node.path}
            {node_view_gui view=text_linked content_node=$:item} /
        {/section}
        {node_view_gui view=text_linked content_node=$:parent_node}<br/>
        {/let}
    {/section}
</div>

{let name=Object related_objects=$content_version.related_contentobject_array}
{section name=ContentObject  loop=$Object:related_objects show=$Object:related_objects  sequence=array(bglight,bgdark)}
    <div class="block">
        {content_view_gui view=text_linked content_object=$Object:ContentObject:item}
    </div>
{/section}
{/let}

{/default}