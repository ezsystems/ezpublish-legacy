{set-block scope=root variable=subject}{"New %1 was published at %2"|i18n("design/standard/user/register",,array($object.class_name,ezini("SiteSettings","SiteURL")))}{/set-block}

{"New %1 was published. Click to the link to view"|i18n("design/standard/user/register",,array($object.class_name))}

http://{ezini("SiteSettings","SiteURL")}{concat("content/view/full/",$object.main_node_id)|ezurl(no)}

---------------

You can change your notification settings at

http://{ezini("SiteSettings","SiteURL")}{concat("notification/settings/")|ezurl(no)}
