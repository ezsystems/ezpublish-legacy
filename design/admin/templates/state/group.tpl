<form action={concat("/state/group/", $state_group.id)|ezurl} method="post" id="stateList">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'%group_name [Content object state group]'|i18n('design', '', hash( '%group_name', $state_group.current_translation.name))|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
    <div class="element">
    <label>ID</label>
    {$state_group.id}
    </div>

    <div class="element">
    <label>Identifier</label>
    {$state_group.identifier}
    </div>

    <div class="element">
    <label>Name</label>
    {$state_group.current_translation.name|wash}
    </div>

    <div class="break"></div>
</div>

<div class="block">
    <div class="element">
    <label>Description</label>
    <p>
    {$state_group.current_translation.description|nl2br}
    </p>
    </div>

    <div class="break"></div>
</div>

</div>
{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
  <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design')}" />
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>{* class="controlbar" *}

</div>{* class="context-block" *}

<div class="context-block">


{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Object states in this group [%state_count]'|i18n( 'design/admin/state/group', '', hash( '%state_count', $state_group.states|count ))}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/state/group' )}" title="{'Invert selection.'|i18n( 'design/admin/state/group' )|wash}" onclick="ezjs_toggleCheckboxes( document.getElementById('stateList'), 'DeleteIDArray[]' ); return false;"/></th>
    <th class="tight">{'ID'|i18n('design/admin/state/group')|wash}</th>
    <th>{'Identifier'|i18n('design/admin/state/group')|wash}</th>
    <th>{'Name'|i18n('design/admin/state/group')|wash}</th>
    <th class="tight">{'Order'|i18n('design/admin/state/group')|wash}</th>
    <th class="tight">&nbsp;</th>
</tr>
{foreach $state_group.states as $state sequence array( 'bglight', 'bgdark' ) as $sequence}
<tr class="{$sequence}">
    <td><input type="checkbox" name="RemoveIDList[]" value="{$state.id}" title="{'Select content object state group for removal.'|i18n( 'design/admin/state/group' )|wash}" /></td>
    <td class="number">{$state.id}</td>
    <td>{*<a href={concat("/state/view/",$state.id)|ezurl}>*}{$state.identifier|wash}{*</a>*}</td>
    <td>{$state.current_translation.name|wash}</td>
    <td>
        <input type="text" name="Order[{$state.id}]" size="3" value="{$state.priority}" />
    </td>
    <td><a href={concat( 'state/edit/', $state.id )|ezurl}><img src={'edit.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )|wash}" /></a></td>
</tr>
{/foreach}
</table>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
<div class="button-left">
  <input class="button" type="submit" id="remove_state_button" name="RemoveButton" value="{'Remove selected'|i18n('design/admin/state/group')|wash}" title="{'Remove selected states.'|i18n( 'design/admin/state/group' )|wash}" />
</div>

<div class="button-right">
      <input class="button" type="submit" name="UpdateOrderButton" value="{'Update ordering'|i18n('design/admin/state/group')|wash}" title="{'Update the order of the content object states in this group.'|i18n( 'design/admin/state/group' )|wash}" />
</div>

<div class="break"></div>
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>{* class="controlbar" *}

</div>


{if is_set($is_valid)}
{if $is_valid}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The content object state was successfully created.'|i18n( 'design/admin/state/group' )}</h2>
    </div>
{else}
    <div class="message-warning">
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The content object state could not be created.'|i18n( 'design/admin/state/group' )}</h2>
    <p>{'Required data is either missing or is invalid'|i18n( 'design/admin/state/group' )}:</p>
    <ul>
    {foreach $validation_messages as $message}
    <li>{$message|wash}</li>
    {/foreach}
    </ul>
    </div>
{/if}
{/if}

<div id="new_state_form">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'New state'|i18n( 'design')}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
<div class="element">
<label>Identifier:</label>
<input type="text" name="ContentObjectState_identifier" style="margin-bottom:1em;" size="80" maxlength="45" {if is_set($new_state)}value="{$new_state.identifier|wash}"{/if} />
</div>
{if $languages|count|gt(1)}
<div class="element">
<label>Default language:</label>
<select name="ContentObjectState_default_language_id">
{foreach $languages as $language}
<option value="{$language.id}" {if and(is_set($new_state),$new_state.default_language_id|eq($language.id))}selected="selected"{/if}>{$language.locale_object.intl_language_name|wash}</option>
{/foreach}
</select>
</div>
{/if}
<div class="break"></div>
</div>

<div class="block">
{if is_set($new_state)}
    {foreach $new_state.all_translations as $translation}
    {if $languages|count|gt(1)}
    <fieldset style="margin-bottom:1em;background-color: #fefefb;">
    <legend><img src="{$translation.language.locale|flag_icon}" /> {$translation.language.locale_object.intl_language_name|wash}</legend>
    {/if}
    <label>Name:</label>
    <input type="text" size="80" maxlength="45" name="ContentObjectState_name[]" value="{$translation.name|wash}" />
    <label>Description:</label>
    <textarea rows="6" name="ContentObjectState_description[]" style="width:99%">{$translation.description|wash}</textarea>
    {if $languages|count|gt(1)}
    </fieldset>
    {/if}
    {/foreach}
{else}
    {foreach $languages as $language}
    {if $languages|count|gt(1)}
    <fieldset style="margin-bottom:1em;background-color: #fefefb;">
    <legend><img src="{$language.locale|flag_icon}" /> {$language.locale_object.intl_language_name|wash}</legend>
    {/if}
    <label>Name:</label>
    <input type="text" size="80" maxlength="45" name="ContentObjectState_name[]" />
    <label>Description:</label>
    <textarea rows="6" name="ContentObjectState_description[]" style="width:99%"></textarea>
    {if $languages|count|gt(1)}
    </fieldset>
    {/if}
    {/foreach}
{/if}
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
<div class="button-left">
<input class="button" type="submit" value="Create" name="CreateButton" />
</div>

<div class="break"></div>
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>{* class="controlbar" *}

</div>

</div>

{if is_set($is_valid)|not}
<script type="text/javascript">
<!--

{literal}

function showCreateDiv()
{
    createDiv = document.getElementById( 'new_state_form' );
    createDiv.style.display = 'block';

    questionDiv = document.getElementById( 'create_state_button' );
    questionDiv.style.display = 'none';
}

function insertAfter(newElement,targetElement)
{
    var parent = targetElement.parentNode;
    if ( parent.lastchild == targetElement )
    {
        parent.appendChild( newElement );
    }
    else
    {
        parent.insertBefore( newElement, targetElement.nextSibling );
    }
}

var removeButton = document.getElementById( 'remove_state_button' );

var createButton = document.createElement( 'input' );
createButton.type = 'button';
createButton.value = 'Create new';
createButton.className = 'button';
createButton.id = 'create_state_button';
createButton.onclick = showCreateDiv;

var space = document.createTextNode( ' ' );
insertAfter( space, removeButton );
insertAfter( createButton, removeButton );

var createForm = document.getElementById( 'new_state_form' );
createForm.style.display = 'none';

{/literal}

-->
</script>
{/if}

</form>