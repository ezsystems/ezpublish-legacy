<form action={concat("/content/copy/",$object.id,"/")|ezurl} method="post">

{let object_versions=$object.versions}

<div class="maincontentheader">
<h1>{"Copying %1"|i18n("design/standard/content/copy",,array($object.name))}</h1>
</div>

<div class="block">
<table cellspacing="6" cellpadding="0" border="0">
<tr>
  <th>{"Versions"|i18n("design/standard/content/copy")}</th>
  <td>{$object_versions|count}</td>
</tr>
</table>
</div>

<div class="block">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
  <td><label>{"Copy all versions"|i18n("design/standard/content/copy")}</label></td>
  <td><input type="radio" name="VersionChoice" value="1"/></td>
</tr>
<tr>
  <td><label>{"Copy current version"|i18n("design/standard/content/copy")}</label></td>
  <td><input type="radio" name="VersionChoice" value="2" checked="checked"/></td>
</tr>
</table>
</div>

<div class="buttonblock">
<input class="button" type="submit" name="CopyButton" value="{'Copy'|i18n('design/standard/content/copy')}" />
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('design/standard/content/copy')}" />
</div>

{/let}

</form>
