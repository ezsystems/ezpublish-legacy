{$node.name|texttoimage(gallery)}
<div class="imageright">
{attribute_view_gui attribute=$node.object.data_map.image image_class=medium}
{section name=Object loop=$node.object.related_contentobject_array show=$node.object.related_contentobject_array}
<div class="block">
{content_view_gui view=text_linked content_object=$Object:item}
</div>
{/section}

</div>
{attribute_view_gui attribute=$node.object.data_map.body}
