{set-block scope=root variable=subject}{"New article was published at %1"|i18n("design/standard/user/register",,array(ezini("SiteSettings","SiteURL")))}{/set-block}


New object was published

http://{ezini("SiteSettings","SiteURL")}{concat("content/view/full/",$object.main_node_id)|ezurl(no)}
