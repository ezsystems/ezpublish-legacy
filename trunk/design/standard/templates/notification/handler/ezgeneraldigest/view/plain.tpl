{set-block scope=root variable=subject}{"Digest from site %1"|i18n("design/standard/user/register",,array(ezini("SiteSettings","SiteURL")))}{/set-block}


{section name=Handlers loop=fetch(notification,digest_handlers,hash(date,$date.timestamp,address,$address))}

{include handler=$Handlers:item date=$date address=$address uri=concat( "design:notification/handler/",$Handlers:item.id_string,"/view/digest_plain.tpl")}

{/section}

-----------

You can change your notification settings at

http://{ezini("SiteSettings","SiteURL")}{concat("notification/settings/")|ezurl(no)}
