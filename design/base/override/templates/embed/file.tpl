{* Flder - List embed view *}
<div class="content-view-embed">
    <div class="class-folder">
    <h2>{$object.name|wash}</h2>

    <div class="content-body">
{let attribute=$object.data_map.file}
{if $attribute.content}
<a href={concat("content/download/",$attribute.contentobject_id,"/",$attribute.id,"/file/",$attribute.content.original_filename)|ezurl}>{$attribute.content.original_filename|wash(xhtml)}</a> {$attribute.content.filesize|si(byte)}
{/if}
{/let}
     </div>
   </div>
</div>