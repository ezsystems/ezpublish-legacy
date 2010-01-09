<form name="Setting" method="post" action={concat( $module.functions.setting.uri, '/', $userID )|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'User settings for <%user_name>'|i18n( 'design/admin/user/setting',, hash( '%user_name', $user.contentobject.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Maximum concurrent logins'|i18n( 'design/admin/user/setting' )}:</label>
<input type="text" name="max_login" size="11" value="{$userSetting.max_login}" title="{'This functionality is not currently not available. [Use this field to specify the maximum allowed number of concurrent logins.]'|i18n( 'design/admin/user/setting' )}" disabled="disabled" />
</div>

{if and( ne( $max_failed_login_attempts, 0), gt( $failed_login_attempts, $max_failed_login_attempts ) )}
<div class="block">
<label>{'Account has been locked because the maximum number of failed login attempts was exceeded.'|i18n( 'design/admin/user/setting' )|wash}</label>
</div>
{/if}

{if or( $max_failed_login_attempts, $failed_login_attempts )}
<div class="block">
<label>{'Maximum number of failed login attempts'|i18n( 'design/admin/user/setting' )}: {$max_failed_login_attempts}</label>
<label>{'Number of failed login attempts for this user'|i18n( 'design/admin/user/setting' )}: {$failed_login_attempts}
<input class="button" type="submit" name="ResetFailedLoginButton" {if $failed_login_attempts|not()}disabled="disabled"{/if} value="{'Reset'|i18n( 'design/admin/user/setting' )}" />
</label>
</div>
{/if}

<div class="block">
<label class="check">{'Enable user account'|i18n( 'design/admin/user/setting' )}:</label>
<input type="checkbox" name="is_enabled" {if $userSetting.is_enabled}checked="checked"{/if} title="{'Use this checkbox to enable or disable the user account.'|i18n( 'design/admin/user/setting' )}" />
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input class="button" type="submit" name="UpdateSettingButton" value="{'OK'|i18n( 'design/admin/user/setting' )}" />
<input class="button" type="submit" name="CancelSettingButton" value="{'Cancel'|i18n( 'design/admin/user/setting' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>
