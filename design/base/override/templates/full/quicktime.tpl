{* Quicktime - Full view *}

<div class="content-view-full">
    <div class="class-quicktime">

    <h2>{$node.name}</h2>

    <div class="attribute-short">
        {attribute_view_gui attribute=$node.object.data_map.description}
    </div>

    <div class="content-media">
    {let attribute=$node.object.data_map.file}
        <object width="{$attribute.content.width}" height="{$attribute.content.height}">
        <param name="movie" value={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl} />
        <param name="controller" value="{section show=$attribute.content.has_controller}true{/section}" />
        <param name="play" value="{section show=$attribute.content.is_autoplay}true{/section}" />
        <param name="loop" value="{section show=$attribute.content.is_loop}true{/section}" />
        <embed src={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl}
               pluginspage="{$attribute.content.pluginspage}"
               width="{$attribute.content.width}" height="{$attribute.content.height}" play="{section show=$attribute.content.is_autoplay}true{/section}"
               loop="{section show=$attribute.content.is_loop}true{/section}" controller="{section show=$attribute.content.has_controller}true{/section}" >
        </embed>
        </object>
    {/let}
    </div>

    </div>
</div>