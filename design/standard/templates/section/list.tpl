<form method="post" action={"/section/list/"|ezurl}>
<div class="maincontentheader">
<h1>{"Section list"|i18n("design/standard/section")}</h1>
</div>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{"ID:"|i18n("design/standard/section")}</th>
    <th>{"Name:"|i18n("design/standard/section")}</th>
    <th width="1%">{"Edit:"|i18n("design/standard/section")}</th>
    <th width="1%">{"Assign:"|i18n("design/standard/section")}</th>
    <th width="1%">{"Remove:"|i18n("design/standard/section")}</th>
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
	<a href={concat("/section/edit/",$Section:item.id,"/")|ezurl}>[{"edit"|i18n("design/standard/section")}]</a>
	</td>
	<td class="{$Section:sequence}">
	<a href={concat("/section/assign/",$Section:item.id,"/")|ezurl}>[{"assign"|i18n("design/standard/section")}]</a>
	</td>
	<td class="{$Section:sequence}">
	<input type="checkbox" name="SectionIDArray[]" value="{$Section:item.id}" />
	</td>	
</tr>
{/section}
</table>

<div class="buttonblock">
<input class="button" type="submit" name="CreateSectionButton" value="{'New'|i18n('design/standard/section')}" />
<input class="button" type="submit" name="RemoveSectionButton" value="{'Remove'|i18n('design/standard/section')}" /> 
</div>

</form>
