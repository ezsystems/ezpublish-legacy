{default object_name=$node.name}
<a href={$node.url_alias|ezurl}>
{attribute_view_gui attribute=$node.data_map.image image_class=small}<br/>
{$object_name|wash}
</a>
{/default}