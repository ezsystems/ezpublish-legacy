{default icon_size='normal' icon_title=$attribute.content.mime_type}
{section show=$attribute.content}
{switch match=$icon}
    {case match='no'}
        <a href={concat("content/download/",$attribute.contentobject_id,"/",$attribute.id,"/file/",$attribute.content.original_filename)|ezurl}>{$attribute.content.original_filename|wash(xhtml)}</a> {$attribute.content.filesize|si(byte)}
    {/case}
    {case}
        <a href={concat("content/download/",$attribute.contentobject_id,"/",$attribute.id,"/file/",$attribute.content.original_filename)|ezurl}>{$attribute.content.mime_type|mimetype_icon( $icon_size, $icon_title )} {$attribute.content.original_filename|wash(xhtml)}</a> {$attribute.content.filesize|si(byte)}
    {/case}
{/switch}
{section-else}
	<div class="message-error"><h2>{"The file could not be found."|i18n("design/standard/content/datatype")}</h2></div>
{/section}
{/default}
