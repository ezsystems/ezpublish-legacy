{* Real video - Admin preview *}

<div class="content-view-full">
    <div class="class-real_video">

    <h2>{$node.name|wash}</h2>

    <div class="attribute-short">
        {attribute_view_gui attribute=$node.data_map.description}
    </div>

    <div class="content-media">
    {let attribute=$node.data_map.file}
    {if $attribute.has_content}
        <object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA"
                {if $attribute.content.width|gt( 0 )}width="{$attribute.content.width}"{/if} {if $attribute.content.height|gt( 0 )}height="{$attribute.content.height}"{/if}>
        <param name="src" value={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl} />
        <param name="controls" value="{$attribute.content.controls}" />
        <param name="autostart" value="{if $attribute.content.is_autoplay}true{/if}" />
        <embed src={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl}
               {*pluginspage="{$attribute.content.pluginspage}"*}
               pluginspage="http://real.com"
               type="audio/x-pn-realaudio-plugin"
               {if $attribute.content.width|gt( 0 )}width="{$attribute.content.width}"{/if} {if $attribute.content.height|gt( 0 )}height="{$attribute.content.height}"{/if} autostart="{if $attribute.content.is_autoplay}true{/if}"
               controls="{$attribute.content.controls}" >
        </embed>
        </object>
    {else}
        {'No media file is available.'|i18n( 'design/admin/content/datatype' )}
    {/if}
    {/let}
    </div>

    </div>
</div>
