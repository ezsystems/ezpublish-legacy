{section show=$attribute.content}
<a href={concat("content/download/",$attribute.contentobject_id,"/",$attribute.id,"/file/",$attribute.content.original_filename)|ezurl}>{$attribute.content.original_filename|wash(xhtml)}</a>&nbsp;{$attribute.content.filesize|si(byte)}
{/section}