<form method="post" action={concat( $Module.functions.policyedit.uri, '/', $policy_id, '/' )|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Edit <%policy_name> policy for <%role_name> role'|i18n( 'design/admin/role/policyedit',, hash( '%policy_name', concat( $current_module, '/', $current_function ), '%role_name', $role_name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Module'|i18n( 'design/admin/role/policyedit' )}:</label>
{$current_module}
</div>

<div class="block">
<label>{'Function'|i18n( 'design/admin/role/policyedit' )}:</label>
{$current_function}
</div>

<fieldset>
<legend>{'Function limitations'|i18n( 'design/admin/role/policyedit' )}</legend>

{section show=$function_limitations}

<div class="block">
{section name=Limitations loop=$function_limitations}
{section-exclude match=$Limitations:item.name|eq( 'Subtree' )}
{section-exclude match=$Limitations:item.name|eq( 'Node' )}
{if $function_limitations|count|gt( 1 )}
<div class="element">
{/if}
<label for="ezrole_createpolizy_limitation_{$Limitations:item.name|wash}">{$Limitations:item.name|wash}:</label>
<select id="ezrole_createpolizy_limitation_{$Limitations:item.name|wash}" name="{$Limitations:item.name}[]" size="8" {if or( not( is_set( $Limitations:item.single_select ) ), not($Limitations:item.single_select) ) }multiple="multiple"{/if} >
<option value="-1" {switch match=$current_limitation_list[$Limitations:item.name]}
{case match=-1} selected="selected"{/case}{case}{/case}{/switch}>{'Any'|i18n( 'design/admin/role/policyedit' )}</option>
{section name=LimitationValues loop=$Limitations:item.values}
<option value="{$Limitations:LimitationValues:item.value}" {switch match=$Limitations:LimitationValues:item.value}
{case in=$current_limitation_list[$Limitations:item.name]}selected="selected"{/case}{case}{/case}{/switch}>{$Limitations:LimitationValues:item.Name|wash}</option>
{/section}
</select>
{if $function_limitations|count|gt( 1 )}
</div>
{/if}
{/section}
</div>
<div class="break"></div>
</fieldset>
{section var=Limitations loop=$function_limitations}

{switch match=$Limitations.item.name}

{* Nodes *}
{case match='Node'}
<div class="block">
<fieldset>
<legend>{'Nodes (%node_count)'|i18n( 'design/admin/role/policyedit',, hash( '%node_count', $node_list|count ) )}</legend>
{section show=$node_list}
<table class="list" cellspacing="0">
<tr>
<th class="tight">&nbsp;</th>
<th>{'Name'|i18n( 'design/admin/role/policyedit' )}</th>
</tr>
{section var=Nodes loop=$node_list sequence=array( bglight, bgdark )}
<tr class="{$Nodes.sequence}">
<td><input type="checkbox" name="DeleteNodeIDArray[]" value="{$Nodes.item.node_id}" /></td>
<td>{$Nodes.item.name|wash}</td>
</tr>
{/section}
</table>
{section-else}
<p>
{'The node list is empty.'|i18n( 'design/admin/role/policyedit' )}
</p>
{/section}

{if $node_list}
<input class="button" type="submit" name="DeleteNodeButton" value="{'Remove selected'|i18n( 'design/admin/role/policyedit' )}" />
{else}
<input class="button-disabled" type="submit" name="DeleteNodeButton" value="{'Remove selected'|i18n( 'design/admin/role/policyedit' )}" disabled="disabled" />
{/if}

<input class="button" type="submit" name="BrowseLimitationNodeButton" value="{'Add nodes'|i18n( 'design/admin/role/policyedit' )}" />
</fieldset>
</div>
{/case}

{* Subtrees *}
{case match='Subtree'}
<div class="block">
<fieldset>
<legend>{'Subtrees (%subtree_count)'|i18n( 'design/admin/role/policyedit',, hash( '%subtree_count', $subtree_list|count ) )}</legend>
{section show=$subtree_list}
<table class="list" cellspacing="0">
<tr>
<th class="tight">&nbsp;</th>
<th>{'Subtree'|i18n( 'design/admin/role/policyedit' )}</th>
</tr>
{section var=Subtrees loop=$subtree_list sequence=array( bglight, bgdark )}
<tr class="{$Subtrees.sequence}">
<td><input type="checkbox" name="DeleteSubtreeIDArray[]" value="{$Subtrees.item.node_id}" /></td>
<td>{$Subtrees.item.name|wash}</td>
</tr>
{/section}
</table>
{section-else}
<p>{'The subtree list is empty.'|i18n( 'design/admin/role/policyedit' )}</p>
{/section}

{if $subtree_list}
<input class="button" type="submit" name="DeleteSubtreeButton" value="{'Remove selected'|i18n( 'design/admin/role/policyedit' )}" />
{else}
<input class="button-disabled" type="submit" name="DeleteSubtreeButton" value="{'Remove selected'|i18n( 'design/admin/role/policyedit' )}" disabled="disabled" />
{/if}

<input class="button" type="submit" name="BrowseLimitationSubtreeButton" value="{'Add subtrees'|i18n( 'design/admin/role/policyedit' )}" />
</fieldset>
</div>
{/case}

{case}
{/case}

{/switch}

{/section}
{section-else}
<p>{'The function limitations of this policy cannot be edited. This is either because the function does not support limitations or because the function was assigned without limitations when the policy was created.'|i18n( 'design/admin/role/policyedit' )}</p>
{/section}

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
    <input type="hidden" name="CurrentModule" value="{$current_module}" />
    <input type="hidden" name="CurrentFunction" value="{$current_function}" />
    {if $function_limitations}
    <input class="button" type="submit" name="UpdatePolicy" value="{'OK'|i18n( 'design/admin/role/policyedit' )}" />
    {else}
    <input class="button-disabled" type="submit" name="UpdatePolicy" value="{'OK'|i18n( 'design/admin/role/policyedit' )}" disabled="disabled" />
    {/if}

    <input class="button" type="submit" name="DiscardChange" value="{'Cancel'|i18n( 'design/admin/role/policyedit' )}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>
