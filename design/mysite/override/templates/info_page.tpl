{* Info page template *}

{default content_version=$node.contentobject_version_object
         node_name=$node.name}

<h1>{$node_name|wash}</h1>
<div class="imageright">
    {attribute_view_gui attribute=$content_version.data_map.image image_class=medium}
    {section name=Object loop=$content_version.related_contentobject_array show=$content_version.related_contentobject_array}
        <div class="block">
            {content_view_gui view=text_linked content_version=$Object:item}
        </div>
    {/section}
</div>

{attribute_view_gui attribute=$content_version.data_map.body}

{/default}
