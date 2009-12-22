{* States window. *}
<form name="statesform" method="post" action={'state/assign'|ezurl}>
<input type="hidden" name="ObjectID" value="{$node.object.id}" />
<input type="hidden" name="RedirectRelativeURI" value="{$node.url_alias}" />

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Object states'|i18n( 'design/admin/node/view/full' )}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">
<table class="list" cellspacing="0" summary="{'States and their states groups for current object.'|i18n( 'design/admin/node/view/full' )}">
{if $node.object.allowed_assign_state_list|count}
    <tr>
        <th class="tight">{'Content object state group'|i18n( 'design/admin/node/view/full' )}</th>
        <th class="wide">{'Available states'|i18n( 'design/admin/node/view/full' )}</th>
    </tr>

    {foreach $node.object.allowed_assign_state_list as $allowed_assign_state_info sequence array( bglight, bgdark ) as $sequence}
    <tr class="{$sequence}">
        <td>{$allowed_assign_state_info.group.current_translation.name|wash}</td>
        <td>
            <select name="SelectedStateIDList[]" {if $allowed_assign_state_info.states|count|eq(1)}disabled="disabled"{/if}>
            {foreach $allowed_assign_state_info.states as $state}
                <option value="{$state.id}" {if $node.object.state_id_array|contains($state.id)}selected="selected"{/if}>{$state.current_translation.name|wash}</option>
            {/foreach}
            </select>
        </td>
    </tr>
    {/foreach}
{else}
    <tr class="bgdark">
    <td colspan="2">
    <em>{'No content object state is configured. This can be done %urlstart here %urlend.'|i18n( 'design/admin/node/view/full', '', hash( '%urlstart', concat( '<a href=', 'state/groups'|ezurl, '>' ), 
                                                                                                                                          '%urlend', '</a>' ) )}</em>
    </td>
    </tr>
{/if}
</table>
{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<div class="button-left">
    {if $node.object.allowed_assign_state_list|count}
    <input type="submit" value="{'Set states'|i18n( 'design/admin/node/view/full' )}" name="AssignButton" class="button" title="{'Apply states from the list above.'|i18n( 'design/admin/node/view/full' )}" />
    {else}
    <input type="submit" value="{'Set states'|i18n( 'design/admin/node/view/full' )}" name="AssignButton" class="button-disabled" title="{'No state to be applied to this content object. You might need to be assigned a more permissive access policy.'|i18n( 'design/admin/node/view/full' )}"/>
    {/if}
</div>

<div class="break"></div>
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>
</div>
</form>