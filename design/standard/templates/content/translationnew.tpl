<h1>{"New translation for content"|i18n("design/standard/content")}</h1>

<form action={$module.functions.translations.uri|ezurl} method="post" >

Name of translation:<br/><input type="edit" name="TranslationName" value=""  size="20" /><br/>
Locale:<br/><input type="text" name="TranslationLocale" value="" size="8" /><br/>
<input type="submit" name="StoreButton" value="Store" > 
</form>
