{let use_url_translation=ezini('URLTranslator','Translation')|eq('enabled')
     is_update=false()}
{section loop=$object.versions}{section show=and($:item.status|eq(3),$:item.version|ne($object.current_version))}{set is_update=true()}{/section}{/section}
{section show=$is_update}
{set-block scope=root variable=subject}[{ezini("SiteSettings","SiteURL")}] {$object.content_class.name} "{$object.name}" was updated{/set-block}
This email is to inform you that an updated item has been publish at {ezini("SiteSettings","SiteURL")}.
The item can viewed by using the URL below.
{section-else}
{set-block scope=root variable=subject}{'[%sitename] %classname "%itemname" was published'
                                        |i18n("design/standard/notification",,
                                              hash('%classname',$object.content_class.name,
                                                   '%itemname',$object.name,
                                                   '%sitename',ezini("SiteSettings","SiteURL")))}{/set-block}
{"This email is to inform you that a new item has been publish at %sitename.
The item can viewed by using the URL below."
 |i18n('design/standard/notification',,
       hash('%sitename',ezini("SiteSettings","SiteURL")))}
{/section}


{$object.name} - {$object.owner.name}
http://{ezini("SiteSettings","SiteURL")}{cond( $use_url_translation, $object.main_node.url_alias|ezurl(no),
                                               true(), concat( "/content/view/full/", $object.main_node_id )|ezurl(no) )}


{"If you do not wish to continue receiving these notifications,
change your settings at:"|i18n('design/standard/notification')}
http://{ezini("SiteSettings","SiteURL")}{concat("notification/settings/")|ezurl(no)}

-- 
{"%sitename notification system"
 |i18n('design/standard/notification',,
       hash('%sitename',ezini("SiteSettings","SiteURL")))}
{/let}
