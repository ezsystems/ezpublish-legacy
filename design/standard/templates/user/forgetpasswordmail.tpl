{let site_url=ezini("SiteSettings","SiteURL")}
{set-block scope=root variable=subject}{"%1 new password"|i18n("design/standard/user/register",,array($site_url))}{/set-block}


Your account information
Email: {$user.email}

{section show=$password}
New password: {$password}
{/section}

Link to user information:
http://{$site_url}{concat("content/view/full/",$object.main_node_id)|ezurl(no)}

{/let}
