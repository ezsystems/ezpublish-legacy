<h1>{"create policy"|i18n('role/edit')} {$role.name}</h1>

<form action={concat($module.functions.edit.uri,"/",$role.id,"/")|ezurl} method="post" >

 Give access to module:
    <select  name="Modules" size="1">
    <option value="*"> Every module </option>
    {section name=All loop=$modules }
      <option value="{$All:item}">{$All:item}</option>
    {/section}
    </select>
    <input type="submit" name="AddModule" value="Allow all" /> <input type="submit" name="CustomFunction" value="Allow limited" />
<br/>
<br/>
<br/>
<input type="submit" value="Cancel" />
</form>