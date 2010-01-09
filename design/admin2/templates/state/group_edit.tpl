<form action={concat("/state/group_edit", cond($group.id,concat('/',$group.identifier),true(),''))|ezurl} method="post">

{if is_set($is_valid)}
{if $is_valid}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The content object state group was successfully stored.'|i18n( 'design/admin/state/groups' )}</h2>
    </div>
{else}
    <div class="message-warning">
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The content object state group could not be stored.'|i18n( 'design/admin/state/groups' )}</h2>
    <p>{'Required data is either missing or is invalid'|i18n( 'design/admin/state/groups' )}:</p>
    <ul>
    {foreach $validation_messages as $message}
    <li>{$message|wash}</li>
    {/foreach}
    </ul>
    </div>
{/if}
{/if}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">
{if $group.id}
{'Edit content object state group "%group_name"'|i18n('design/admin/state/group_edit', '', hash( '%group_name', $group.current_translation.name ))|wash}
{else}
{'New content object state group'|i18n('design/admin/state/group_edit')|wash}
{/if}
</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
<div class="element">
<label>{"Identifier:"|i18n('design/admin/state/group_edit')}</label>
<input type="text" name="ContentObjectStateGroup_identifier" style="margin-bottom:1em;" size="80" maxlength="45" value="{$group.identifier|wash}" />
</div>
{if $group.all_translations|count|gt(1)}
<div class="element">
<label>{"Default language:"|i18n('design/admin/state/group_edit')}</label>
<select name="ContentObjectStateGroup_default_language_id">
{foreach $group.all_translations as $translation}
<option value="{$translation.language.id}" {if $group.default_language_id|eq($translation.language.id)}selected="selected"{/if}>{$translation.language.locale_object.intl_language_name|wash}</option>
{/foreach}
</select>
</div>
{/if}
<div class="break"></div>
</div>

<div class="block">
{let $translations=$group.all_translations
     $useFieldsets=$translations|count|gt(1)}
    {foreach $translations as $translation}
    {if $useFieldsets}
    <fieldset style="margin-bottom:1em;background-color: #fefefb;">
    <legend><img src="{$translation.language.locale|flag_icon}" /> {$translation.language.locale_object.intl_language_name|wash}</legend>
    {/if}
    <label>{"Name:"|i18n('design/admin/state/group_edit')}</label>
    <input type="text" size="80" maxlength="45" name="ContentObjectStateGroup_name[]" value="{$translation.name|wash}" />
    <label>{"Description:"|i18n('design/admin/state/group_edit')}</label>
    <textarea rows="6" name="ContentObjectStateGroup_description[]" style="width:99%">{$translation.description|wash}</textarea>
    {if $useFieldsets}
    </fieldset>
    {/if}
    {/foreach}
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
  {if $group.id}
  <input class="button" type="submit" name="StoreButton" value="{'Save changes'|i18n('design/admin/state/group_edit')|wash}" title="{'Save changes to this state group.'|i18n( 'design/admin/state/group_edit' )|wash}" />
  <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('design/admin/state/group_edit')|wash}" title="{'Cancel saving any changes.'|i18n( 'design/admin/state/group_edit' )|wash}" />
  {else}
  <input class="button" type="submit" name="StoreButton" value="{'Create'|i18n('design/admin/state/group_edit')|wash}" title="{'Create this state group.'|i18n( 'design/admin/state/group_edit' )|wash}" />
  <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('design/admin/state/group_edit')|wash}" title="{'Cancel creating this state group.'|i18n( 'design/admin/state/group_edit' )|wash}" />
  {/if}

</div>

{* DESIGN: Control bar END *}</div></div>

</div>{* class="controlbar" *}

</div>{* class="context-block" *}

</form>