<h1>Object versions: {$object.name} </h1>

<form action="/content/versions/{$object.id}/" method="post">
<table width="100%" cellspacing="0" cellpadding="2">
<tr>
	<th>
	Version nr.
	</th>
	<th>
	Creator.
	</th>
	<th>
	Created.
	</th>
	<th>
	Modified.
	</th>
</tr>
{section name=Versions loop=$versions sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Versions:sequence}" valign="top">
	<img src={"history.png"|ezimage}  alt="" />
	<a href="/content/versionview/{$object.id}/{$Versions:item.version}/">{$Versions:item.version}</a>

{switch name=sw match=$Versions:item.version}
{case match=$object.current_version}
<i>*Current*</i>
{/case}
{case}

{/case}
{/switch}

	</td>
	<td class="{$Versions:sequence}" valign="top">
	{$Versions:item.creator.name}
	</td>
	<td class="{$Versions:sequence}" valign="top">
	{$Versions:item.created|l10n(datetime)}
	</td>
	<td class="{$Versions:sequence}" valign="top">
	{$Versions:item.modified|l10n(datetime)}
	</td>
	<td class="{$Versions:sequence}" valign="top">

	<input type="radio" name="RevertToVersionID" value="{$Versions:item.version}" />
	</td>
</tr>
{/section}
</table>

<input type="submit" name="RevertButton" value="Revert and delete new versions" />
<input type="submit" name="CopyRevertButton" value="Copy as current" />
<br />

<a href="/content/edit/{$object.id}/">Back to edit object</a>
</form>