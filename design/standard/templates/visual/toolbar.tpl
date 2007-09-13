<form method="post" action={concat( 'visual/toolbar/', $current_siteaccess, '/', $toolbar_position )|ezurl}>

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Tool list for <Toolbar_%toolbar_position>'|i18n( 'design/standard/visual/toolbar',, hash( '%toolbar_position', $toolbar_position ) )|wash}
</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">
<div class="context-attributes">

{section show=$tool_list}
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
{section var=Tool loop=$tool_list}

<tr>
    <th class="tight"><input type="checkbox" name="deleteToolArray[]" value="{$Tool.index}" /></th>
    <th class="wide">{$Tool.name|wash}</th>
    <th class="tight"><input type="text" name="placement_{$Tool.index}" size="2" value="{sum($Tool.index,1)}" /></th>
</tr>

<tr>
    <td></td>
    <td>
    {section show=eq($toolbar_position,right)}
        <img src={concat( "toolbar/", $Tool.name|wash, ".png" )|ezimage} alt="{$Tool.name|wash}" />
    {section-else}
        <img src={concat( "toolbar/", $Tool.name|wash, "_line.png" )|ezimage} alt="{$Tool.name|wash}" />
    {/section}
    <br /> <!-- Break before list of parameters -->
        <table>
        {section var=Parameter loop=$Tool.parameters}
        <tr>
            <td>
            {$Parameter.description}
            </td>
            <td>
            {* Render the different attribute types each tool can have *}
            {switch match=cond( $Parameter.name|wash|ends_with( '_node' ), 1,
                                $Parameter.name|wash|ends_with( '_classidentifier' ), 2,
                                $Parameter.name|wash|ends_with( '_classidentifiers' ), 3,
                                $Parameter.name|wash|ends_with( '_subtree' ), 4,
                                $Parameter.name|wash|ends_with( '_check' ), 5,
                                0 )}
            {case match=1}
                {let used_node=fetch( content, node, hash( node_id, $Parameter.value ) )}
                {section show=$used_node}
                    {$used_node.object.content_class.identifier|class_icon( small, $used_node.object.content_class.name|wash )}&nbsp;{$used_node.name|wash} ({$Parameter.value})
                {section-else}
                    {$Parameter.value|wash}
                {/section}
                {/let}
                <input type="submit" name="BrowseButton[{$Tool.index}_parameter_{$Parameter.name|wash}]" value="{'Browse'|i18n( 'design/standard/visual/toolbar' )}" />
                <input type="hidden" name="{$Tool.index}_parameter_{$Parameter.name|wash}" size="20" value="{$Parameter.value|wash}">
            {/case}
            {case match=2}
                {let class_list=fetch( class, list )}
                <select name="{$Tool.index}_parameter_{$Parameter.name|wash}">
                {section var=class loop=$class_list}
                    <option value="{$class.identifier|wash}" {section show=eq( $class.identifier, $Parameter.value )}selected="selected"{/section}>{$class.name|wash}</option>
                {/section}
                </select>
                {/let}
            {/case}
            {case match=3}
                {let class_list=fetch( class, list ) match_list=$Parameter.value|explode( ',' )}
                <select multiple="multiple" name="CustomInputList[{$Tool.index}_parameter_{$Parameter.name|wash}][]">
                {section var=class loop=$class_list}
                    <option value="{$class.identifier|wash}" {section show=$match_list|contains( $class.identifier )}selected="selected"{/section}>{$class.name|wash}</option>
                {/section}
                </select>
                {/let}
            {/case}
            {case match=4}
                {let used_node=fetch( content, node, hash( node_id, $Parameter.value ) )}
                {section show=$used_node}
                    {$used_node.object.content_class.identifier|class_icon( small, $used_node.object.content_class.name|wash )}&nbsp;{$used_node.object.name|wash} ({$Parameter.value})
                {section-else}
                    {$Parameter.value|wash}
                {/section}
                {/let}
                <input type="submit" name="BrowseButton[{$Tool.index}_parameter_{$Parameter.name|wash}]" value="{'Browse'|i18n( 'design/standard/visual/toolbar' )}" />
                <input type="hidden" name="{$Tool.index}_parameter_{$Parameter.name|wash}" size="20" value="{$Parameter.value|wash}" />
            {/case}
            {case match=5}
                {section show=array( 'true', 'false' )|contains( $Parameter.value )}
                    <label for="{$Tool.index}_parameter_{$Parameter.name|wash}_true"><input type="radio" name="{$Tool.index}_parameter_{$Parameter.name|wash}" id="{$Tool.index}_parameter_{$Parameter.name|wash}_true" value="true" {section show=$Parameter.value|ne( 'false' )}checked="checked"{/section} />{'True'|i18n( 'design/standard/visual/toolbar' )}</label>
                    <label for="{$Tool.index}_parameter_{$Parameter.name|wash}_false"><input type="radio" name="{$Tool.index}_parameter_{$Parameter.name|wash}" id="{$Tool.index}_parameter_{$Parameter.name|wash}_false" value="false" {section show=$Parameter.value|eq( 'false' )}checked="checked"{/section} />{'False'|i18n( 'design/standard/visual/toolbar' )}</label>
                {section-else}
                  {section show=array( 'yes', 'no' )|contains( $Parameter.value )}
                      <label for="{$Tool.index}_parameter_{$Parameter.name|wash}_true"><input type="radio" name="{$Tool.index}_parameter_{$Parameter.name|wash}" id="{$Tool.index}_parameter_{$Parameter.name|wash}_true" value="yes" {section show=$Parameter.value|ne( 'no' )}checked="checked"{/section} />{'Yes'|i18n( 'design/standard/visual/toolbar' )}</label>
                      <label for="{$Tool.index}_parameter_{$Parameter.name|wash}_false"><input type="radio" name="{$Tool.index}_parameter_{$Parameter.name|wash}" id="{$Tool.index}_parameter_{$Parameter.name|wash}_false" value="no" {section show=$Parameter.value|eq( 'no' )}checked="checked"{/section} />{'No'|i18n( 'design/standard/visual/toolbar' )}</label>
                  {section-else}
                      <input type="text" name="{$Tool.index}_parameter_{$Parameter.name|wash}" size="20" value="{$Parameter.value|wash}" />
                  {/section}
                {/section}
            {/case}
            {case}
                <input type="text" name="{$Tool.index}_parameter_{$Parameter.name|wash}" size="20" value="{$Parameter.value|wash}" />
            {/case}
            {/switch}

            </td>
        </tr>
        {/section}
        </table>
    </td>
</tr>
{/section}
</table>
{section-else}
{'There are currently no tools in this toolbar'|i18n( 'design/standard/visual/toolbar' )}
{/section}

<div class="block">
<div class="left">
<input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n('design/standard/visual/toolbar')}" />
</div>
<div class="right">
<input class="button" type="submit" name="UpdatePlacementButton" value="{'Update priorities'|i18n('design/standard/visual/toolbar')}" />
</div>
</div>

<div class="block">
<select name="toolName">
{section var=Tool loop=$available_tool_list}
    <option value="{$Tool}">{$Tool}</option>
{/section}
</select>
<input class="button" type="submit" name="NewToolButton" value="{'Add Tool'|i18n('design/standard/visual/toolbar')}" />
</div>

{* DESIGN: Content END *}</div></div></div>


<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
       <input class="button" type="submit" name="StoreButton" value="{'Apply changes'|i18n('design/standard/visual/toolbar')}" title="{'Click this button to store changes if you have modified the parameters above.'|i18n( 'design/standard/visual/toolbar' )}" />
       <input class="button" type="submit" name="BackToToolbarsButton" value="{'Back to toolbars'|i18n('design/standard/visual/toolbar')}" title="{'Go back to the toolbar list.'|i18n( 'design/standard/visual/toolbar' )}" />
    </div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
