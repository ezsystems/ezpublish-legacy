{* Windows media - Full view *}

<div class="content-view-full">
    <div class="class-windows_media">

    <h2>{$node.name|wash}</h2>

    <div class="attribute-short">
        {attribute_view_gui attribute=$node.data_map.description}
    </div>

    <div class="content-media">
    {let attribute=$node.data_map.file}
        <object ID="MediaPlayer"  CLASSID="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" STANDBY="Loading Windows Media Player components..." type="application/x-oleobject"
                {if $attribute.content.width|gt( 0 )}width="{$attribute.content.width}"{/if} {if $attribute.content.width|gt( 0 )}height="{$attribute.content.height}"{/if}>
        <param name="filename" value={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl} />
        <param name="autostart" value="{$attribute.content.is_autoplay}" />
        <param name="showcontrols" value="{$attribute.content.has_controller}" />
        <embed src={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl}
               type="application/x-mplayer2" pluginspage="{$attribute.content.pluginspage}"
               {if $attribute.content.width|gt( 0 )}width="{$attribute.content.width}"{/if} {if $attribute.content.height|gt( 0 )}height="{$attribute.content.height}"{/if} autostart="{$attribute.content.is_autoplay}"
               showcontrols="{$attribute.content.has_controller}" >
        </embed>
        </object>
    {/let}
    </div>

    </div>
</div>