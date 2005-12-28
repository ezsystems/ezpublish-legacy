<div id="package" class="create">
<div id="sid-{$current_step.id|wash}" class="pc-{$creator.id|wash}">

<form enctype="multipart/form-data" method="post" action={'package/create'|ezurl}>

{include uri="design:package/create/error.tpl"}

{include uri="design:package/header.tpl"}

<p>{'Please select an extension to be exported.'|i18n('design/standard/package')}</p>

<ul>
{foreach $extension_list as $extension}
    <li><input class="radiobutton" name="PackageExtensionName" type="radio" value="{$extension|wash}" />{$extension|wash}</li>
{/foreach}
</ul>

{include uri="design:package/navigator.tpl"}

</form>

</div>
</div>

