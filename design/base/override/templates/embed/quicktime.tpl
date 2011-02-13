<div class="view-embed">
    <div class="content-media">
    {let attribute=$object.data_map.file}
        <object {if $attribute.content.width|gt( 0 )}width="{$attribute.content.width}"{/if} {if $attribute.content.height|gt( 0 )}height="{$attribute.content.height}"{/if}>
        <param name="movie" value={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl} />
        <param name="controller" value="{if $attribute.content.has_controller}true{/if}" />
        <param name="play" value="{if $attribute.content.is_autoplay}true{/if}" />
        <param name="loop" value="{if $attribute.content.is_loop}true{/if}" />
        <embed src={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl}
               type="video/quicktime"
               pluginspage="{$attribute.content.pluginspage}"
               {if $attribute.content.width|gt( 0 )}width="{$attribute.content.width}"{/if} {if $attribute.content.height|gt( 0 )}height="{$attribute.content.height}"{/if} play="{if $attribute.content.is_autoplay}true{/if}"
               loop="{if $attribute.content.is_loop}true{/if}" controller="{if $attribute.content.has_controller}true{/if}" >
        </embed>
        </object>
    {/let}
    </div>
</div>