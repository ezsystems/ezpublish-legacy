{let domain=ezini("SiteSettings","SiteURL")}
{set-block variable=subject}
{"Update on %1"|i18n('design/standard/notification',,array($domain))}
{/set-block}
{section show=$message_list|gt(1)}
{"The following pages have been updated"|i18n('design/standard/notification')}:


{section name=Message loop=$message_list}

{let object=fetch("content","object",hash("object_id",$:item.object_id))}
{$:object.name}
{concat("http://",$domain,"/content/view/full/",$:object.main_node_id)}

{/let}
{/section}
{section-else}
{let object=fetch("content","object",hash("object_id",$message_list[0].object_id))}
{"The following page has been updated"|i18n('design/standard/notification')}:

{$object.name}
{concat("http://",$domain,"/content/view/full/",$object.main_node_id)}

{/let}
{/section}

-- 
Site admin at {$domain}
{/let}
