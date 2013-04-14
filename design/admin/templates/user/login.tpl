{* Warnings *}
{if $User:warning.bad_login}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The system could not log you in.'|i18n( 'design/admin/user/login' )}</h2>
<ul>
    {if and( is_set( $User:user_is_not_allowed_to_login ), eq( $User:user_is_not_allowed_to_login, true() ) )}
         <li>{'"%user_login" is not allowed to log in because failed login attempts by this user exceeded allowable number of failed login attempts!'|i18n( 'design/admin/user/login',, hash( '%user_login', $User:login ) )}</li>
         <li>{'Please contact the site administrator.'|i18n( 'design/admin/user/login' )}</li>
    {else}
         <li>{'Make sure that the username and password is correct.'|i18n( 'design/admin/user/login' )}</li>
         <li>{'All letters must be entered in the correct case.'|i18n( 'design/admin/user/login' )}</li>
         <li>{'Please try again or contact the site administrator.'|i18n( 'design/admin/user/login' )}</li>
    {/if}
</ul>
</div>
{else}
{if $site_access.allowed|not}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Access denied!'|i18n( 'design/admin/user/login' )}</h2>
<ul>
    <li>{'You do not have permission to access <%siteaccess_name>.'|i18n( 'design/admin/user/login',, hash( '%siteaccess_name', $site_access.name ) )|wash}</li>
    <li>{'Please contact the site administrator.'|i18n( 'design/admin/user/login' )}</li>
</ul>
</div>
{/if}
{/if}

{* Login window *}
<div class="context-block">

<form name="loginform" method="post" action={'/user/login/'|ezurl}>

<div class="login-inputs context-attributes">
    
    {if and( is_set( $User:max_num_of_failed_login ), ne( $User:max_num_of_failed_login, false() ) )}
        <div class="block login-text-wrapper">
        {'The user will not be allowed to login after <b>%max_number_failed</b> failed login attempts.'|i18n( 'design/admin/user/login',, hash( '%max_number_failed', $User:max_num_of_failed_login ) )}
        </div>
    {/if}    

    <div class="block">
        <div class="login-input-wrapper">
            <div id="icon-login"></div>
            <input class="halfbox" type="text" autofocus="autofocus" size="10" name="Login" id="logintext" placeholder="{'Username'|i18n( 'design/admin/user/login' )}" tabindex="1" title="{'Enter a valid username in this field.'|i18n( 'design/admin/user/login' )}" />
        </div>
    </div>

    <div class="block">
        <div class="login-input-wrapper">
            <div id="icon-password"></div>
            <input class="halfbox" type="password" size="10" name="Password" id="passwordtext" placeholder="{'Password'|i18n( 'design/admin/user/login' )}" tabindex="2" title="{'Enter a valid password in this field.'|i18n( 'design/admin/user/login' )}" />
        </div>
    </div>

    {if and( ezini_hasvariable( 'Session', 'RememberMeTimeout' ), ezini( 'Session', 'RememberMeTimeout' ) )}
        <div class="block login-text-wrapper">
            <input type="checkbox" name="Cookie" id="id3" /><label for="id3" style="display:inline;">{"Remember me"|i18n("design/admin/user/login")}</label>
        </div>
    {/if}

</div>


<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
    <div class="login-input-wrapper">
        <input class="defaultbutton" type="submit" id="loginbutton" name="LoginButton" value="{'Log in'|i18n( 'design/admin/user/login', 'Login button' )}" tabindex="3" title="{'Click here to log in using the username/password combination entered in the fields above.'|i18n( 'design/admin/user/login' )}" />
    </div>
    <div class="login-text-wrapper">
        {'or'|i18n( 'design/admin/user/login')}
        <br/>
        <a href={'/user/register'|ezurl()}>{'Register new account'|i18n( 'design/admin/user/login')}</a>
    </div>
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

<input type="hidden" name="RedirectURI" value="{$User:redirect_uri|wash}" />

</form>

</div>
