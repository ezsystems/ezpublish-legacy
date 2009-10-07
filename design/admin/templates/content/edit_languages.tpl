{def $languages               = fetch('content', 'prioritized_languages')
     $object_language_codes   = $object.language_codes
     $object_edit_languages   = $object.can_edit_languages
     $object_create_languages = $object.can_create_languages
     $can_edit                = true()}

<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Object information'|i18n( 'design/admin/content/edit_languages' )}</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{* Object ID *}
<p>
<label>{'ID'|i18n( 'design/admin/content/edit_languages' )}:</label>
{$object.id}
</p>

{* Created *}
<p>
<label>{'Created'|i18n( 'design/admin/content/edit_languages' )}:</label>
{if $object.published}
{$object.published|l10n( shortdatetime )}<br />
{$object.current.creator.name|wash}
{else}
{'Not yet published'|i18n( 'design/admin/content/edit_languages' )}
{/if}
</p>

{* Modified *}
<p>
<label>{'Modified'|i18n( 'design/admin/content/edit_languages' )}:</label>
{if $object.modified}
{$object.modified|l10n( shortdatetime )}<br />
{fetch( content, object, hash( object_id, $object.content_class.modifier_id ) ).name|wash}
{else}
{'Not yet published'|i18n( 'design/admin/content/edit_languages' )}
{/if}
</p>

{* Published version *}
<p>
<label>{'Published version'|i18n( 'design/admin/content/edit_languages' )}:</label>
{if $object.published}
{$object.current_version}
{else}
{'Not yet published'|i18n( 'design/admin/content/edit_languages' )}
{/if}
</p>

</div></div></div></div></div></div>

</div>

</div>
</div>

<div id="maincontent"><div id="fix">
<div id="maincontent-design">
<!-- Maincontent START -->


<form action={concat('content/edit/',$object.id)|ezurl} method="post">


<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Edit <%object_name>'|i18n( 'design/admin/content/edit_languages',, hash( '%object_name', $object.name ) )|wash}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

{if $show_existing_languages}
    {* Translation a user is able to edit *}
    {set-block variable=$existing_languages_output}
    {foreach $object_edit_languages as $language}
        <label>
            <input name="EditLanguage" type="radio" value="{$language.locale}"{run-once} checked="checked"{/run-once} /> {$language.name|wash}
        </label>
        <div class="labelbreak"></div>
    {/foreach}
    {/set-block}

    {if $existing_languages_output|trim}
        <div class="block">
        <fieldset>
        <legend>{'Existing languages'|i18n('design/admin/content/edit_languages')}</legend>
        <p>{'Select the language you want to edit'|i18n('design/admin/content/edit_languages')}:</p>

        <div class="indent">
            {$existing_languages_output}
        </div>
        </fieldset>
        </div>
    {/if}
{/if}

{* Translation a user is able to create *}
{set-block variable=$nonexisting_languages_output}
{foreach $object_create_languages as $language}

    <label>
       <input name="EditLanguage" type="radio" value="{$language.locale}" /> {$language.name|wash}
    </label>
    <div class="labelbreak"></div>
{/foreach}
{/set-block}

{if $nonexisting_languages_output|trim}
    <div class="block">
    <fieldset>
    <legend>{'New languages'|i18n('design/admin/content/edit_languages')}</legend>
    <p>{'Select the language you want to add'|i18n('design/admin/content/edit_languages')}:</p>

    <div class="indent">
        {$nonexisting_languages_output}
    </div>

    <p>{'Select the language the added translation will be based on'|i18n('design/admin/content/edit_languages')}:</p>

    <div class="indent">
    <label>
        <input name="FromLanguage" type="radio" checked="checked" value="" /> {'None'|i18n('design/admin/content/edit_languages')}
    </label>
    <div class="labelbreak"></div>

    {foreach $object.languages as $language}
        <label>
            <input name="FromLanguage" type="radio" value="{$language.locale}" /> {$language.name|wash}
        </label>
        <div class="labelbreak"></div>
    {/foreach}
    </div>

    </fieldset>
    </div>
{else}
    {if $show_existing_languages|not}
        {set $can_edit=false()}
        <p>{'You do not have permission to create a translation in another language.'|i18n('design/admin/content/edit_languages')}</p>

        {* Translation a user is able to edit *}
        {set-block variable=$existing_languages_output}
        {foreach $object_edit_languages as $language}
            <label>
                <input name="EditLanguage" type="radio" value="{$language.locale}"{run-once} checked="checked"{/run-once} /> {$language.name|wash}
            </label>
            <div class="labelbreak"></div>
        {/foreach}
        {/set-block}

        {if $existing_languages_output|trim}
            <div class="block">
            <fieldset>
            {set $can_edit=true()}
            <legend>{'Existing languages'|i18n('design/admin/content/edit_languages')}</legend>
            <p>{'However you can select one of the following languages for editing.'|i18n('design/admin/content/edit_languages')}:</p>

            <div class="indent">
                {$existing_languages_output}
            </div>
            </fieldset>
            </div>
        {/if}
    {elseif $existing_languages_output|trim|not}
        {set $can_edit=false()}
        {'You do not have permission to edit the object in any available languages.'|i18n('design/admin/content/edit_languages')}
    {/if}
{/if}

</div>

{* DESIGN: Content END *}</div></div></div>
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
{if $can_edit}
    <input class="button" type="submit" name="LanguageSelection" value="{'Edit'|i18n('design/admin/content/edit_languages')}" />
{else}
    <input class="button-disabled" disabled="disabled" type="submit" name="LanguageSelection" value="{'OK'|i18n('design/admin/content/edit_languages')}" />
{/if}

<input class="button" type="submit" name="CancelDraftButton" value="{'Cancel'|i18n('design/admin/content/edit_languages')}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>
</div>

</form>

<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>
