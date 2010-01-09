<form action={"/state/groups"|ezurl} method="post" id="stateGroupList">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Content object state groups [%group_count]'|i18n('design/admin/state_groups', '', hash( '%group_count', $group_count ))|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

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
    <td><input type="checkbox" name="RemoveIDList[]" value="{$group.id}" title="{'Select content object state group for removal.'|i18n( 'design/admin/state/groups' )|wash}" {if $group.is_internal}disabled="disabled"{/if}/></td>
    <td class="number">{$group.id}</td>
    <td><a href={concat("/state/group/",$group.identifier)|ezurl}>{$group.identifier|wash}</a></td>
    <td>{$group.current_translation.name|wash}</td>
    <td>{if $group.is_internal|not}
        <a href={concat( 'state/group_edit/', $group.identifier )|ezurl}><img src={'edit.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )|wash}" /></a>
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
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
  <input class="button" type="submit" id="remove_state_group_button" name="RemoveButton" value="{'Remove selected'|i18n('design/admin/state/groups')|wash}" title="{'Remove selected state groups.'|i18n( 'design/admin/state/groups' )|wash}" />
  <input class="button" type="submit" id="create_state_group_button" name="CreateButton" value="{'Create new'|i18n('design/admin/state/groups')|wash}" title="{'Create a new state group.'|i18n( 'design/admin/state/groups' )|wash}" />
</div>

{* DESIGN: Control bar END *}</div></div>

</div>{* class="controlbar" *}

</div>{* class="context-block" *}

</form>