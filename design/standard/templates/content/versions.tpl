<form action={concat("/content/versions/",$object.id,"/")|ezurl} method="post">

<div class="maincontentheader">
<h1>{"Versions for:"|i18n("design/standard/content/version")} {$object.name} </h1>
</div>

{switch match=$edit_warning}
{case match=1}
<div class="warning">
<h2>{"Version not a draft"|i18n("design/standard/content/version")}</h2>
<ul>
    <li>{"Version"|i18n("design/standard/content/version")} {$edit_version} {"is not available for editing anymore, only drafts can be edited."|i18n("design/standard/content/version")}</li>
    <li>{"To edit this version create a copy of it."|i18n("design/standard/content/version")}</li>
</ul>
</div>
{/case}
{case match=2}
<div class="warning">
<h2>{"Version not yours"|i18n("design/standard/content/version")}</h2>
<ul>
    <li>{"Version"|i18n("design/standard/content/version")} {$edit_version} {"was not created by you, only your own drafts can be edited."|i18n("design/standard/content/version")}</li>
    <li>{"To edit this version create a copy of it."|i18n("design/standard/content/version")}</li>
</ul>
</div>
{/case}
{case}
{/case}
{/switch}

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{"Version:"|i18n("design/standard/content/version")}
	</th>
	<th>
	{"Status:"|i18n("design/standard/content/version")}
	</th>
	<th>
	{"Translations:"|i18n("design/standard/content/version")}
	</th>
	<th>
	{"Creator:"|i18n("design/standard/content/version")}
	</th>
	<th colspan="2">
	{"Modified:"|i18n("design/standard/content/version")}
	</th>
</tr>
{section name=Version loop=$versions sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Version:sequence}">
	<a href={concat("/content/versionview/",$object.id,"/",$Version:item.version,"/",$edit_language|not|choose(array($edit_language,"/"),""))|ezurl}>{$Version:item.version}</a>
        {section show=eq($Version:item.version,$object.current_version)}*{/section}

	</td>
	<td class="{$Version:sequence}">
	{$Version:item.status|choose("Draft","Published","Pending","Archived","Rejected")}
	</td>
	<td class="{$Version:sequence}">
	{section name=Language loop=$Version:item.language_list}
        {delimiter},{/delimiter}
	<a href={concat("/content/versionview/",$object.id,"/",$Version:item.version,"/",$Version:Language:item.language_code,"/")|ezurl}>{$Version:Language:item.locale.intl_language_name}</a>{/section}
	</td>
	<td class="{$Version:sequence}">
	<a href={concat("/content/view/full/",$Version:item.creator.main_node_id,"/")|ezurl}>{$Version:item.creator.name}</a>
	</td>
	<td class="{$Version:sequence}">
	<span class="small">{$Version:item.modified|l10n(shortdatetime)}</span>
	</td>
	<td class="{$Version:sequence}">
	<input type="radio" name="RevertToVersionID" value="{$Version:item.version}" {section show=eq($Version:item.version,$edit_version)}checked="checked"{/section} />
	</td>
</tr>
{/section}
<tr>
</table>

<div class="buttonblock" align="right">
<input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/content/version')}" />
<input class="button" type="submit" name="CopyVersionButton" value="{'Copy and edit'|i18n('design/standard/content/version')}" />
</div>

<input type="hidden" name="EditLanguage" value="{$edit_language}" />

</form>
