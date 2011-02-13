<div class="view-embed">
    <div class="content-media">

    {let attribute=$object.data_map.file}
        <object codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"
                width="{$attribute.content.width}" height="{$attribute.content.height}" id="objectid{$object.id}">

        <param name="movie" value={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl} />
        <param name="quality" value="{$attribute.content.quality}" />
        <param name="play" value="{if $attribute.content.is_autoplay}true{/if}" />
        <param name="loop" value="{if $attribute.content.is_loop}true{/if}" />
        <embed src={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl}
               type="application/x-shockwave-flash"
               quality="{$attribute.content.quality}" pluginspage="{$attribute.content.pluginspage}"
               width="{$attribute.content.width}" height="{$attribute.content.height}" play="{if $attribute.content.is_autoplay}true{/if}"
               loop="{if $attribute.content.is_loop}true{/if}" name="objectid{$object.id}">
        </embed>
        </object>
    {/let}
    </div>
</div>
