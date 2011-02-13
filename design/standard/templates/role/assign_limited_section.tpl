<form action={concat('/role/assign/', $role_id, '/', $limit_ident )|ezurl} method="post">

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th width="79%">{"Section"|i18n("design/standard/role")}</th>
    <th width="20%">{"ID"|i18n("design/standard/role")}</th>
</tr>
{section loop=$section_array sequence=array(bglight,bgdark)}
<tr>
    <td class="{$:sequence}"><input type="radio" name="SectionID" value="{$:item.id}" selected="selected" />{$:item.name|wash}</td>
    <td class="{$:sequence}">{$:item.id}</td>
</tr>
{/section}

</table>

<input type="submit" class="button" name="AssignSectionID" value="{'Ok'|i18n( 'design/standard/role' )}" />

</form>
