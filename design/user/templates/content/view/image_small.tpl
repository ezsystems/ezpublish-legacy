{let attribute=$node.object.data_map.image}
<img border="1" src="/var/storage/variations/{$attribute.content.mime_type_category}/{$attribute.content.small.additional_path}{$attribute.content.small.filename}" width={$attribute.content.small.width} height={$attribute.content.small.height} border="0" />
{/let}