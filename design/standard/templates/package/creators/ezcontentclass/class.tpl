{let class_list=fetch( class, list )}
<div id="package">
<form method="post" action={'package/create'|ezurl}>

    {include uri="design:package/create/error.tpl"}

    <input type="hidden" name="CreatorItemID" value="{$creator.id|wash}" />
    <input type="hidden" name="CreatorStepID" value="{$current_step.id|wash}" />
    <input type="hidden" name="PackageStep" value="1" />

    <h1>{$current_step.name|wash}</h1>

    <div class="objectheader">
        <h2>Package {$package.name|wash}-{$package.version-number|wash}-{$package.release-number|wash} ({$package.licence|wash})</h2>
    </div>
    <div class="object">
        <p class="summary">{$package.summary|wash}</p>
        <p class="description">{$package.description|wash}</p>
    </div>

    <p>{'Please choose the content classes you wish to be included in the package.'|i18n('design/standard/package')}</p>

    <div class="block">
        <label>{'Class list'|i18n('design/standard/package')}</label>
        <select name="ClassList[]" multiple="multiple">
        {section var=class loop=$class_list}
            <option value="{$class.id}">{$class.item.name|wash}</option>
        {/section}
        </select>
    </div>

    <div class="navigator">
        <input class="button" type="submit" name="NextStepButton" value="{'Next %arrowright'|i18n( 'design/standard/package',, hash( '%arrowright', '&raquo;' ) )}" />
    </div>

</form>
</div>
{/let}
