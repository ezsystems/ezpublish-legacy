{section name=Author loop=$attribute.content.author_list sequence=array(bglight,bgdark) }
 {$Author:item.name} - ( <a href="mailto:{$Author:item.email}">{$Author:item.email}</a> )

{delimiter}
,
{/delimiter}
{/section}
