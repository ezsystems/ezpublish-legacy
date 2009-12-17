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

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Log in to the Administration Interface of eZ Publish'|i18n( 'design/admin/user/login' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
    <p>{'Please enter a valid username/password combination then click "Log in".'|i18n( 'design/admin/user/login' )}</p>
    <p>{'Use the "Register" button to create a new account.'|i18n( 'design/admin/user/login' )}</p>
    {if and( is_set( $User:max_num_of_failed_login ), ne( $User:max_num_of_failed_login, false() ) )}
        {'The user will not be allowed to login after <b>%max_number_failed</b> failed login attempts.'|i18n( 'design/admin/user/login',, hash( '%max_number_failed', $User:max_num_of_failed_login ) )}
    {/if}
</div>

<div class="block">
    <label for="id1">{'Username'|i18n( 'design/admin/user/login' )}:</label>
    <input class="halfbox" type="text" size="10" name="Login" id="id1" value="{$User:login|wash}" tabindex="1" title="{'Enter a valid username in this field.'|i18n( 'design/admin/user/login' )}" />
</div>

<div class="block">
    <label for="id2">{'Password'|i18n( 'design/admin/user/login' )}:</label>
    <input class="halfbox" type="password" size="10" name="Password" id="id2" value="" tabindex="1" title="{'Enter a valid password in this field.'|i18n( 'design/admin/user/login' )}" />
</div>

{if and( ezini_hasvariable( 'Session', 'RememberMeTimeout' ), ezini( 'Session', 'RememberMeTimeout' ) )}
    <div class="block">
        <input type="checkbox" tabindex="1" name="Cookie" id="id3" /><label for="id3" style="display:inline;">{"Remember me"|i18n("design/admin/user/login")}</label>
    </div>
{/if}

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <input class="defaultbutton" type="submit" name="LoginButton" value="{'Log in'|i18n( 'design/admin/user/login', 'Login button' )}" tabindex="1" title="{'Click here to log in using the username/password combination entered in the fields above.'|i18n( 'design/admin/user/login' )}" />
    <input class="button" type="submit" name="RegisterButton" value="{'Register'|i18n( 'design/admin/user/login', 'Register button' )}" tabindex="1" title="{'Click here to create a new account.'|i18n( 'design/admin/user/login' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

<input type="hidden" name="RedirectURI" value="{$User:redirect_uri|wash}" />

</form>

</div>




{literal}
<script language="JavaScript" type="text/javascript">
<!--
    window.onload=function()
    {
        document.getElementById('id1').focus();
    }
-->
</script>
{/literal}
