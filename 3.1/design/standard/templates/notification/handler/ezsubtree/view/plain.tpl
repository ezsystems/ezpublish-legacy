{set-block scope=root variable=subject}{'[%sitename] %classname "%itemname" was published'
                                        |i18n("design/standard/notification",,
                                              hash('%classname',$object.content_class.name,
                                                   '%itemname',$object.name,
                                                   '%sitename',ezini("SiteSettings","SiteURL")))}{/set-block}
{"This email is to inform you that a new item has been publish at %sitename.
The item can viewed by using the URL below."
 |i18n('design/standard/notification',,
       hash('%sitename',ezini("SiteSettings","SiteURL")))}

{$object.name} - {$object.owner.name}
http://{ezini("SiteSettings","SiteURL")}{concat("content/view/full/",$object.main_node_id)|ezurl(no)}


{"If you do not wish to continue receiving these notifications,
change your settings at:"|i18n('design/standard/notification')}
http://{ezini("SiteSettings","SiteURL")}{concat("notification/settings/")|ezurl(no)}

-- 
{"%sitename notification system"
 |i18n('design/standard/notification',,
       hash('%sitename',ezini("SiteSettings","SiteURL")))}
