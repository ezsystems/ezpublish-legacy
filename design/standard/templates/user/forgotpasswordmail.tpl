{let site_url=ezini("SiteSettings","SiteURL")}
{set-block scope=root variable=subject}{"%1 new password"|i18n("design/standard/user/register",,array($site_url))}{/set-block}
Your account information
Email: {$user.email}

{section show=$link}
{"Click here to get new password:"|i18n('design/standard/user/register')}
{concat("user/forgetpassword/", $hash_key, '/')|ezurl}
{section-else}


{section show=$password}
New password: {$password}
{/section}

{section}


{/let}
