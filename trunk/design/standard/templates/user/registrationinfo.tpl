{let site_url=ezini("SiteSettings","SiteURL")}
{set-block scope=root variable=subject}{"%1 registration info"|i18n("design/standard/user/register",,array($site_url))}{/set-block}
Thank you for registering at {$site_url}.

Your account information
Login: {$user.login}
Email: {$user.email}

{section show=$password}
Password: {$password}
{/section}

Link to user information:
http://{$site_url}{concat("content/view/full/",$object.main_node_id)|ezurl(no)}

{/let}
