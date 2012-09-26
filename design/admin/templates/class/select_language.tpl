<form action={concat( $module.functions.edit.uri, '/', $class.id )|ezurl} method="post" id="SelectClassEditLanguageForm" name="SelectClassEditLanguage">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h2 class="context-title">{'Edit <%class_name>'|i18n( 'design/admin/class/select_languages',, hash( '%class_name', $class.name ) )|wash}</h2>



{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
    <fieldset>
    <legend>{'New languages'|i18n('design/admin/class/select_language')}</legend>
    <p>{'Select the language you want to add'|i18n('design/admin/class/select_language')}:</p>

    {def $editLanguages = $class.can_create_languages}

    <div class="indent">
        {foreach $editLanguages as $language}
            <label>
                <input name="EditLanguage" type="radio" value="{$language.locale}"{run-once} checked="checked"{/run-once} /> {$language.name|wash}
            </label>
            <div class="labelbreak"></div>
        {/foreach}
    </div>

    {undef $editLanguages}

    {if $class}
        <p>{'Select the language the added translation will be based on'|i18n('design/admin/class/select_language')}:</p>

        <div class="indent">
        <label>
            <input name="FromLanguage" type="radio" checked="checked" value="" /> {'None'|i18n('design/admin/class/select_language')}
        </label>
        <div class="labelbreak"></div>

        {foreach $class.prioritized_languages as $language}
            <label>
                <input name="FromLanguage" type="radio" value="{$language.locale}" /> {$language.name|wash}
            </label>
            <div class="labelbreak"></div>
        {/foreach}
        </div>
    {/if}

    </fieldset>
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
    <input class="button" type="submit" name="SelectLanguageButton" value="{'Edit'|i18n('design/admin/class/edit_language')}" />
    <input class="button" type="submit" name="DiscardButton" value="{'Cancel'|i18n('design/admin/class/select_language')}" />
</div>
{* DESIGN: Control bar END *}</div></div>

</div>

</div>
</form>


