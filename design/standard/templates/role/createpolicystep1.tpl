<div class="maincontentheader">
<h1>{"create policy"|i18n('role/edit')} {$role.name}</h1>
</div>

<form action={concat($module.functions.edit.uri,"/",$role.id,"/")|ezurl} method="post" >

<h2>Step 1</h2>
<div class="block">
<label>Give access to module:</label><div class="labelbreak"></div>
    <select  name="Modules" size="1">
    <option value="*"> Every module </option>
    {section name=All loop=$modules }
      <option value="{$All:item}">{$All:item}</option>
    {/section}
    </select>
</div>
<div class="buttonblock">
<input class="button" type="submit" name="AddModule" value="Allow all" />
<input class="button" type="submit" name="CustomFunction" value="Allow limited" />
</div>
<div class="buttonblock">
<input class="button" type="submit" value="Cancel" />
</div>

</form>