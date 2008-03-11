<div id="package" class="create">
<div id="sid-{$current_step.id|wash}" class="pc-{$creator.id|wash}">

<form enctype="multipart/form-data" method="post" action={'package/create'|ezurl}>

{include uri="design:package/create/error.tpl"}

{include uri="design:package/header.tpl"}

<p>{'Please select the extensions to be exported.'|i18n('design/standard/package')}</p>

<ul>
{foreach $extension_list as $extension}
    <li style="list-style:none;"><label style="font-weight:normal;"><input name="PackageExtensionNames[]" type="checkbox" value="{$extension|wash}" /> {$extension|wash}</label></li>
{/foreach}
</ul>

{include uri="design:package/navigator.tpl"}

</form>

</div>
</div>

