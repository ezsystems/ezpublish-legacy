{* Quicktime - Admin preview *}

<div class="content-view-full">
    <div class="class-quicktime">

    <h2>{$node.name|wash}</h2>

    <div class="attribute-short">
        {attribute_view_gui attribute=$node.data_map.description}
    </div>

    <div class="content-media">
    {let attribute=$node.data_map.file}
    {if $attribute.has_content}
        <object {if $attribute.content.width|gt( 0 )}width="{$attribute.content.width}"{/if} {if $attribute.content.height|gt( 0 )}height="{$attribute.content.height}"{/if} codebase="http://www.apple.com/qtactivex/qtplugin.cab">
        <param name="movie" value={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl} />
        <param name="controller" value="{if $attribute.content.has_controller}true{/if}" />
        <param name="autoplay" value="{if $attribute.content.is_autoplay}true{/if}" />
        <param name="loop" value="{if $attribute.content.is_loop}true{/if}" />
        <embed src={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl}
               pluginspage="{$attribute.content.pluginspage}"
               {if $attribute.content.width|gt( 0 )}width="{$attribute.content.width}"{/if} {if $attribute.content.height|gt( 0 )}height="{$attribute.content.height}"{/if} play="{if $attribute.content.is_autoplay}true{/if}"
               loop="{if $attribute.content.is_loop}true{/if}" controller="{if $attribute.content.has_controller}true{/if}" >
        </embed>
        </object>
    {else}
        {'No media file is available.'|i18n( 'design/admin/content/datatype' )}
    {/if}
    {/let}
    </div>

    </div>
</div>
