<div class="imagecenter">
<img src={concat("/var/storage/variations/",$object.data_map.image.content.mime_type_category,"/",$object.data_map.image.content.large.additional_path,$object.data_map.image.content.large.filename)|ezroot} width="{$object.data_map.image.content.large.width}" height="{$object.data_map.image.content.large.height}" border="{$border_size}" /><br />

{attribute_view_gui attribute=$object.data_map.caption}
</div>
