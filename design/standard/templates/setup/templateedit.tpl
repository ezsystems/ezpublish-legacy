<form method="post" action={concat('/setup/templateedit/',$template)|ezurl}>
<h1>Template edit: {$template}</h1>

<textarea name=TemplateContent cols="80" rows="30">{$template_content|wash(xhtml)}</textarea>

<div class="buttonblock">
<input class="button" type="submit" value="Save" name="SaveButton" />
<input class="button" type="submit" value="Discard" name="DiscardButton" />
</div>

</form>
