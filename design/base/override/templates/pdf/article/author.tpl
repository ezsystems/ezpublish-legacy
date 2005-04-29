{section name=Author loop=$attribute.content.author_list sequence=array(bglight,bgdark) }
{"Author: "|i18n("design/base")|wash(pdf)}{$Author:item.name|wash(pdf)} &lt;{$Author:item.email|wash(pdf)}&gt;{delimiter},{/delimiter}
{/section}
