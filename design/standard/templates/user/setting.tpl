<form action={concat($module.functions.setting.uri,"/",$userID)|ezurl} method="post" name="Setting">

<div class="maincontentheader">
<h1>User setting</h1>
</div>

<div class="block">
<label>{"Maximum login"|i18n}:</label><div class="labelbreak"></div>
<input type="text" name="max_login" size="11" value="{$userSetting.max_login}" />
</div>

<div class="block">
<input type="checkbox" name="is_enabled" {section show=$userSetting.is_enabled}checked{/section} >&nbsp;<label class="check">{"Is enabled"|i18n}</label><div class="labelbreak"></div>
</div>

<div class="buttonblock">
<input class="button" type="submit" name="UpdateSettingButton" value="Update" />
<input class="button" type="submit" name="CancelSettingButton" value="Cancel" />
</div>

</form>