<form method="post" action="/section/list/">
<h1>Section list</h1>

<table cellspacing="0" width="100%">
{section name=Section loop=$section_array sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Section:sequence}">
	{$Section:item.id}
	</td>
	<td class="{$Section:sequence}">
	{$Section:item.name}
	</td>
	<td class="{$Section:sequence}">
	<a href="/section/edit/{$Section:item.id}/">[ edit ]</a>
	</td>
	<td class="{$Section:sequence}">
	<a href="/section/assign/{$Section:item.id}/">[ assign ]</a>
	</td>
	<td class="{$Section:sequence}">
	<input type="checkbox" name="SectionIDArray[]" value="{$Section:item.id}" />
	</td>	
</tr>
{/section}
</table>

<input type="submit" name="CreateSectionButton" value="Create section" /> &nbsp; 
<input type="submit" name="RemoveSectionButton" value="Remove section" /> 
</form>