<form action={"/state/groups"|ezurl} method="post" id="stateGroupList">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Content object state groups [%group_count]'|i18n('design/admin/state_groups', '', hash( '%group_count', $group_count ))|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{* Items per page selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
<p>
{switch match=$limit}
{case match=25}
<a href={concat('/user/preferences/set/', $list_limit_preference_name, '/1')|ezurl}>10</a>
<span class="current">25</span>
<a href={concat('/user/preferences/set/', $list_limit_preference_name, '/3')|ezurl}>50</a>
{/case}

{case match=50}
<a href={concat('/user/preferences/set/', $list_limit_preference_name, '/1')|ezurl}>10</a>
<a href={concat('/user/preferences/set/', $list_limit_preference_name, '/2')|ezurl}>25</a>
<span class="current">50</span>
{/case}

{case}
<span class="current">10</span>
<a href={concat('/user/preferences/set/', $list_limit_preference_name, '/2')|ezurl}>25</a>
<a href={concat('/user/preferences/set/', $list_limit_preference_name, '/3')|ezurl}>50</a>
{/case}

{/switch}
</p>
</div>
<div class="break"></div>
</div>
</div>

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/state/groups' )}" title="{'Invert selection.'|i18n( 'design/admin/state/groups' )|wash}" onclick="ezjs_toggleCheckboxes( document.getElementById('stateGroupList'), 'DeleteIDArray[]' ); return false;"/></th>
    <th class="tight">{'ID'|i18n('design/admin/state/groups')|wash}</th>
    <th>{'Identifier'|i18n('design/admin/state/groups')|wash}</th>
    <th>{'Name'|i18n('design/admin/state/groups')|wash}</th>
    <th class="tight">&nbsp;</th>
</tr>
{foreach $groups as $group sequence array( 'bglight', 'bgdark' ) as $sequence}
<tr class="{$sequence}">
    <td><input type="checkbox" name="RemoveIDList[]" value="{$group.id}" title="{'Select content object state group for removal.'|i18n( 'design/admin/state/groups' )|wash}" /></td>
    <td class="number">{$group.id}</td>
    <td><a href={concat("/state/group/",$group.id)|ezurl}>{$group.identifier|wash}</a></td>
    <td>{$group.current_translation.name|wash}</td>
    <td>{if $group.can_edit}
        <a href={concat( 'state/group_edit/', $group.id )|ezurl}><img src={'edit.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )|wash}" /></a>
        {else}
        <img src={'edit-disabled.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )|wash}" />
        {/if}
    </td>
</tr>
{/foreach}
</table>

{* Navigator. *}
<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/state/groups'
         item_count=$group_count
         view_parameters=$view_parameters
         item_limit=$limit}
</div>


{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
  <input class="button" type="submit" id="remove_state_group_button" name="RemoveButton" value="{'Remove selected'|i18n('design/admin/state/groups')|wash}" title="{'Remove selected state groups.'|i18n( 'design/admin/state/groups' )|wash}" />
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>{* class="controlbar" *}

</div>{* class="context-block" *}

<div class="context-block">

{if is_set($is_valid)}
{if $is_valid}
    <div class="message-feedback">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The content object state group was successfully created.'|i18n( 'design/admin/state/groups' )}</h2>
    </div>
{else}
    <div class="message-warning">
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The content object state group could not be created.'|i18n( 'design/admin/state/groups' )}</h2>
    <p>{'Required data is either missing or is invalid'|i18n( 'design/admin/state/groups' )}:</p>
    <ul>
    {foreach $validation_messages as $message}
    <li>{$message|wash}</li>
    {/foreach}
    </ul>
    </div>
{/if}
{/if}

<div id="new_state_group_form">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'New state group'|i18n( 'design')}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
<div class="element">
<label>Identifier:</label>
<input type="text" name="ContentObjectStateGroup_identifier" style="margin-bottom:1em;" size="80" maxlength="45" {if is_set($new_group)}value="{$new_group.identifier|wash}"{/if} />
</div>
{if $languages|count|gt(1)}
<div class="element">
<label>Default language:</label>
<select name="ContentObjectStateGroup_default_language_id">
{foreach $languages as $language}
<option value="{$language.id}" {if and(is_set($new_group),$new_group.default_language_id|eq($language.id))}selected="selected"{/if}>{$language.locale_object.intl_language_name|wash}</option>
{/foreach}
</select>
</div>
{/if}
<div class="break"></div>
</div>

<div class="block">
{if is_set($new_group)}
    {foreach $new_group.all_translations as $translation}
    {if $languages|count|gt(1)}
    <fieldset style="margin-bottom:1em;background-color: #fefefb;">
    <legend><img src="{$translation.language.locale|flag_icon}" /> {$translation.language.locale_object.intl_language_name|wash}</legend>
    {/if}
    <label>Name:</label>
    <input type="text" size="80" maxlength="45" name="ContentObjectStateGroup_name[]" value="{$translation.name|wash}" />
    <label>Description:</label>
    <textarea rows="6" name="ContentObjectStateGroup_description[]" style="width:99%">{$translation.description|wash}</textarea>
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
    <input type="text" size="80" maxlength="45" name="ContentObjectStateGroup_name[]" />
    <label>Description:</label>
    <textarea rows="6" name="ContentObjectStateGroup_description[]" style="width:99%"></textarea>
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
    createDiv = document.getElementById( 'new_state_group_form' );
    createDiv.style.display = 'block';

    questionDiv = document.getElementById( 'create_state_group_button' );
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

var removeButton = document.getElementById( 'remove_state_group_button' );

var createButton = document.createElement( 'input' );
createButton.type = 'button';
createButton.value = 'Create new';
createButton.className = 'button';
createButton.id = 'create_state_group_button';
createButton.onclick = showCreateDiv;

var space = document.createTextNode( ' ' );
insertAfter( space, removeButton );
insertAfter( createButton, removeButton );

var createForm = document.getElementById( 'new_state_group_form' );
createForm.style.display = 'none';

{/literal}

-->
</script>
{/if}


</form>