{set-block scope=root variable=subject}{"New user registered at %1"|i18n("design/standard/user/register",,array(ezini("SiteSettings","SiteURL")))}{/set-block}
A new user has registered.

Account information.
Login: {$user.login}
Email: {$user.email}

Link to user information:
http://{ezini("SiteSettings","SiteURL")}{concat("content/view/full/",$object.main_node_id)|ezurl(no)}
