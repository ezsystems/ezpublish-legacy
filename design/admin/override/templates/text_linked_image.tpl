{default object_name=$object.name}
<a href={concat('content/view/full/',$object.main_node_id)|ezurl}>
{attribute_view_gui attribute=$object.data_map.image image_class=small}<br/>
{$object_name|wash}
</a>
{/default}