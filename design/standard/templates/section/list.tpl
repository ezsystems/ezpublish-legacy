<form method="post" action={"/section/list/"|ezurl}>
<div class="maincontentheader">
<h1>Section list</h1>
</div>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>ID:</th>
    <th>Name:</th>
    <th colspan="3" width="1%">&nbsp;</th>
</tr>
{section name=Section loop=$section_array sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Section:sequence}">
	{$Section:item.id}
	</td>
	<td class="{$Section:sequence}">
	{$Section:item.name}
	</td>
	<td class="{$Section:sequence}">
	<a href={concat("/section/edit/",$Section:item.id,"/")|ezurl}>[edit]</a>
	</td>
	<td class="{$Section:sequence}">
	<a href={concat("/section/assign/",$Section:item.id,"/")|ezurl}>[assign]</a>
	</td>
	<td class="{$Section:sequence}">
	<input type="checkbox" name="SectionIDArray[]" value="{$Section:item.id}" />
	</td>	
</tr>
{/section}
</table>

<div class="buttonblock">
<input class="button" type="submit" name="CreateSectionButton" value="New" />
<input class="button" type="submit" name="RemoveSectionButton" value="Remove" /> 
</div>

</form>