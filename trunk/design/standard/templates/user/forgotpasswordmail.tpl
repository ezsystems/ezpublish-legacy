{let site_url=ezini("SiteSettings","SiteURL")}
{set-block scope=root variable=subject}{"%1 new password"|i18n("design/standard/user/forgotpassword",,array($site_url))}{/set-block}
{"Your account information"|i18n('design/standard/user/forgotpassword')}
{"Email:"|i18n('design/standard/user/forgotpassword')} {$user.email}

{section show=$link}
{"Click here to get new password:"|i18n('design/standard/user/forgotpassword')}
{concat("user/forgetpassword/", $hash_key, '/')|ezurl}
{section-else}


{section show=$password}
{"New password:"|i18n('design/standard/user/forgotpassword')} {$password}
{/section}

{section}

{/let}
