{default object_name=$object.name}
<a href={$object.main_node.url_alias|ezurl}>
{attribute_view_gui attribute=$object.data_map.image image_class=small}<br/>
{$object_name|wash}
</a>
{/default}