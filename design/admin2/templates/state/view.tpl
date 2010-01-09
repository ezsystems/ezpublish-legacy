<form action={concat("/state/view/", $group.identifier, "/", $state.identifier)|ezurl} method="post" id="state">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'%state_name [Content object state]'|i18n('design', '', hash( '%state_name', $state.current_translation.name))|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
    <div class="element">
    <label>{"ID"|i18n('design/admin/state/view')}</label>
    {$state.id}
    </div>

    <div class="element">
    <label>{"Identifier"|i18n('design/admin/state/view')}</label>
    {$state.identifier}
    </div>

    <div class="element">
    <label>{"Name"|i18n('design/admin/state/view')}</label>
    {$state.current_translation.name|wash}
    </div>

    <div class="break"></div>
</div>

<div class="block">
    <div class="element">
    <label>{"Description"|i18n('design/admin/state/view')}</label>
    <p>
    {$state.current_translation.description|nl2br}
    </p>
    </div>

    <div class="break"></div>
</div>

</div>
{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
  {if $state.group.is_internal|not}
  <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design')}" />
  {else}
  <input class="button-disabled" type="submit" name="EditButton" value="{'Edit'|i18n('design')}" disabled="disabled" />
  {/if}
</div>

{* DESIGN: Control bar END *}</div></div>

</div>{* class="controlbar" *}

</div>{* class="context-block" *}



<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h2 class="context-title">{'Translations [%translations]'|i18n( 'design/admin/node/view/full',, hash( '%translations', $state.languages|count ) )}</h2>



{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th>{'Language'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Locale'|i18n( 'design/admin/node/view/full' )}</th>
    <th class="tight">{'Main'|i18n( 'design/admin/node/view/full' )}</th>
</tr>

{foreach $state.languages as $language sequence array( 'bglight', 'bgdark' ) as $sequence}
<tr class="{$sequence}">
    <td><img src="{$language.locale|flag_icon}" alt="{$language.locale}" />&nbsp;<a href={concat( '/state/view/', $group.identifier,'/',$state.identifier , '/', $language.locale )|ezurl} title="{'View translation.'|i18n( 'design/admin/node/view/full' )}">{$language.name|wash}</td>
    <td>{$language.locale}</td>
    <td>{if $language.id|eq($state.default_language_id)}Yes{/if}</td>
</tr>
{/foreach}

</table>

{* DESIGN: Content END *}</div></div></div>

</div>{* class="context-block" *}

</form>