{set-block scope=root variable=subject}{"Confirm user registration at %1"|i18n("design/standard/user/register",,array(ezini("SiteSettings","SiteURL")))}{/set-block}
Your user account at {ezini("SiteSettings","SiteURL")} has been created

Account information:
Login: {$user.login}
Email: {$user.email}

Click the following URL to confirm your account
http://{ezini("SiteSettings","SiteURL")}{concat("user/activate/",$hash)|ezurl(no)}

