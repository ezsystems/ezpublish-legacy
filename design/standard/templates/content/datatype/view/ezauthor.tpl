{section name=Author loop=$attribute.content.author_list sequence=array(bglight,bgdark) }
 {$Author:item.name|wash(xhtml)} - ( {$Author:item.email|wash(email)} ){delimiter},{/delimiter}
{/section}