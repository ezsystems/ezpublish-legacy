<div id="package">
<form method="post" action={'package/create'|ezurl}>

    {include uri="design:package/create/error.tpl"}

    <input type="hidden" name="CreatorItemID" value="{$creator.id|wash}" />
    <input type="hidden" name="CreatorStepID" value="{$current_step.id|wash}" />
    <input type="hidden" name="PackageStep" value="1" />

    <h1>{$current_step.name|wash}</h1>

    <p>{'Please provide some basic information for your package.'|i18n('design/standard/package')}</p>

    <div class="name">
        <label>{'Package name'|i18n('design/standard/package')}</label>
        <input class="textline" type="text" name="PackageName" value="{$persistent_data.name|wash}" />
    </div>

    <div class="summary">
        <label>{'Summary'|i18n('design/standard/package')}</label>
        <input class="textline" type="text" name="PackageSummary" value="{$persistent_data.summary|wash}" />
    </div>

    <div class="description">
        <label>{'Description'|i18n('design/standard/package')}</label>
        <textarea class="description" name="PackageDescription">{$persistent_data.description|wash}</textarea>
    </div>

    <div class="version">
        <label>{'Version'|i18n('design/standard/package')}</label>
        <input class="textline" type="text" name="PackageVersion" value="{$persistent_data.version|wash}" />
    </div>

    <div class="licence">
        <label>{'Licence'|i18n('design/standard/package')}</label>
        <input type="hidden" name="PackageLicence" value="{$persistent_data.licence|wash}" />
        <p>{$persistent_data.licence|wash}</p>
    </div>

    <div class="navigator">
        <input class="button" type="submit" name="NextStepButton" value="{'Next %arrowright'|i18n( 'design/standard/package',, hash( '%arrowright', '&raquo;' ) )}" />
    </div>

</form>
</div>
