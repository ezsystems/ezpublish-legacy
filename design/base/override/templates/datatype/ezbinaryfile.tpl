{section show=$attribute.content}
{switch match=$icon}
    {case match='no'}
        <a href={concat("content/download/",$attribute.contentobject_id,"/",$attribute.id,"/file/",$attribute.content.original_filename)|ezurl}>{$attribute.content.original_filename|wash(xhtml)}</a> {$attribute.content.filesize|si(byte)}
    {/case}
    {case}
        <a href={concat("content/download/",$attribute.contentobject_id,"/",$attribute.id,"/file/",$attribute.content.original_filename)|ezurl}>{$attribute.content.mime_type|mimetype_icon()} {$attribute.content.original_filename|wash(xhtml)}</a> {$attribute.content.filesize|si(byte)}
    {/case}
{/section}