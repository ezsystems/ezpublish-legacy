<div class="view-embed">
    <div class="content-media">
    {let attribute=$object.data_map.file}
        <object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA"
                width="{$attribute.content.width}" height="{$attribute.content.height}">
        <param name="src" value={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl} />
        <param name="controls" value="{$attribute.content.controls}" />
        <param name="autostart" value="{section show=$attribute.content.is_autoplay}true{/section}" />
        <embed src={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl}
               pluginspage="{$attribute.content.pluginspage}"
               width="{$attribute.content.width}" height="{$attribute.content.height}" autostart="{section show=$attribute.content.is_autoplay}true{/section}"
               controls="{$attribute.content.controls}" >
        </embed>
        </object>
    {/let}
    </div>
</div>