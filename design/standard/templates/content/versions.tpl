<h1>Object versions: {$object.name} </h1>

<form action={concat("/content/versions/",$object.id,"/")|ezurl} method="post">
<table width="100%" cellspacing="0" cellpadding="2">
<tr>
	<th>
	Version
	</th>
	<th>
	Status
	</th>
	<th>
	Creator
	</th>
	<th>
	Created
	</th>
	<th>
	Modified
	</th>
</tr>
{section name=Versions loop=$versions sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Versions:sequence}" valign="top">
	<img src={"history.png"|ezimage}  alt="" />
	<a href={concat("/content/versionview/",$object.id,"/",$Versions:item.version,"/")|ezurl}>{$Versions:item.version}</a>

{switch name=sw match=$Versions:item.version}
{case match=$object.current_version}
*
{/case}
{case}

{/case}
{/switch}

	</td>
	<td class="{$Versions:sequence}" valign="top">
	{$Versions:item.status|choose("Draft","Published","Pending","Archived","Rejected")}
	</td>
	<td class="{$Versions:sequence}" valign="top">
	{$Versions:item.creator.name}
	</td>
	<td class="{$Versions:sequence}" valign="top">
	{$Versions:item.created|l10n(shortdatetime)}
	</td>
	<td class="{$Versions:sequence}" valign="top">
	{$Versions:item.modified|l10n(shortdatetime)}
	</td>
	<td class="{$Versions:sequence}" valign="top">

	<input type="radio" name="RevertToVersionID" value="{$Versions:item.version}" />
	</td>
</tr>
{/section}
<tr>
  <td colspan="6" align="right">
    <input type="submit" name="EditButton" value="Object Edit" />
    &nbsp;
    <input type="submit" name="RevertVersionButton" value="Revert To" />
    <input type="submit" name="CopyVersionButton" value="Copy From" />
  </td>
</tr>
</table>

</form>