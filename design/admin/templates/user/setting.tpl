<form name="Setting" method="post" action={concat( $module.functions.setting.uri, '/', $userID )|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'User settings for <%user_name>'|i18n( 'design/admin/section/list',, hash( '%user_name', $user.contentobject.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Maximum concurrent logins'|i18n( 'design/admin/user/setting' )}</label>
<input type="text" name="max_login" size="11" value="{$userSetting.max_login}" title="{'Use this field to specify the maximum allowed number of concurrent logins.'|i18n( 'design/admin/user/setting' )}" />
</div>

<div class="block">
<label class="check">{'Enable user account'|i18n( 'design/admin/user/setting' )}</label>
<input type="checkbox" name="is_enabled" {section show=$userSetting.is_enabled}checked{/section} title="{'Use this checkbox to enable or disable the user account.'|i18n( 'design/admin/user/setting' )}" />
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="UpdateSettingButton" value="{'OK'|i18n( 'design/admin/user/setting' )}" />
<input class="button" type="submit" name="CancelSettingButton" value="{'Cancel'|i18n( 'design/admin/user/setting' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
