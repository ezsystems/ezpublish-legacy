<form action={concat("/content/versions/",$object.id,"/")|ezurl} method="post">

<div class="maincontentheader">
<h1>Versions for: {$object.name} </h1>
</div>

{switch match=$edit_warning}
{case match=1}
<div class="warning">
<h2>Version not a draft</h2>
<ul>
    <li>Version {$edit_version} is not available for editing anymore, only drafts can be edited.</li>
    <li>To edit this version create a copy of it.</li>
</ul>
</div>
{/case}
{case match=2}
<div class="warning">
<h2>Version not yours</h2>
<ul>
    <li>Version {$edit_version} was not created by you, only your own drafts can be edited.</li>
    <li>To edit this version create a copy of it.</li>
</ul>
</div>
{/case}
{case}
{/case}
{/switch}

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	Version:
	</th>
	<th>
	Status:
	</th>
	<th>
	Translations:
	</th>
	<th>
	Creator:
	</th>
	<th colspan="2">
	Modified:
	</th>
</tr>
{section name=Version loop=$versions sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Version:sequence}">
	<a href={concat("/content/versionview/",$object.id,"/",$Version:item.version,"/",$edit_language|not|choose(array($edit_language,"/"),""))|ezurl}>{$Version:item.version}</a>
        {section show=eq($Version:item.version,$object.current_version)}*{/section}
	{section show=$Version:item.status|eq(3)}<div class="listicon"><img src={"history.png"|ezimage}  alt="History" /></div>{/section}

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

<div class="buttonblock">
<input class="button" type="submit" name="EditButton" value="Object Edit" />
<input class="button" type="submit" name="CopyVersionButton" value="Copy From" />
</div>

<input type="hidden" name="EditLanguage" value="{$edit_language}" />

</form>