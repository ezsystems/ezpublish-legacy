{default content_object=$node.object
         node_name=$node.name}

{$node_name|texttoimage(gallery)}
<div class="imageright">
{attribute_view_gui attribute=$content_object.data_map.image image_class=medium}
{section name=Object loop=$content_object.related_contentobject_array show=$content_object.related_contentobject_array}
<div class="block">
{content_view_gui view=text_linked content_object=$Object:item}
</div>
{/section}

</div>
{attribute_view_gui attribute=$content_object.data_map.body}

{/default}
