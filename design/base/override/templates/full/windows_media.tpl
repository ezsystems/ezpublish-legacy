{* Windows media - Full view *}

<div class="view-full">
    <div class="class-windows_media">

    <h2>{$node.name}</h2>

    <div class="content-short">
        {attribute_view_gui attribute=$node.object.data_map.description}
    </div>

    <div class="content-media">
    {let attribute=$node.object.data_map.file}
        <object ID="MediaPlayer"  CLASSID="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" STANDBY="Loading Windows Media Player components..." type="application/x-oleobject"
                width="{$attribute.content.width}" height="{$attribute.content.height}">
        <param name="filename" value={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl} />
        <param name="autostart" value="{$attribute.content.is_autoplay}" />
        <param name="showcontrols" value="{$attribute.content.has_controller}" />
        <embed src={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl}
               type="application/x-mplayer2" pluginspage="{$attribute.content.pluginspage}"
               width="{$attribute.content.width}" height="{$attribute.content.height}" autostart="{$attribute.content.is_autoplay}"
               showcontrols="{$attribute.content.has_controller}" >
        </embed>
        </object>
    {/let}
    </div>

    </div>
</div>