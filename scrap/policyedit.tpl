<form method="post" action={concat( $Module.functions.policyedit.uri, '/', $policy_id, '/' )|ezurl}>

<div class="contex-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Policy'|i18n( 'design/admin/role' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">


<div class="context-attributes">

<div class="block">
<label>{'Module'|i18n( 'design/admin/role' )}</label>
<p>{$current_module}</p>
</div>

<div class="block">
<label>{'Function'|i18n( 'design/admin/role' )}</label>
<p>{$current_function}</p>
</div>

<br/>

<h2>{'Limitations'|i18n('design/standard/role')}</h2>


    {section name=Limitations loop=$function_limitations}
    {section-exclude match=$Limitations:item.name|eq('Subtree')}
    {section-exclude match=$Limitations:item.name|eq('Node')}
    <div class="element">
        <label>{$Limitations:item.name|wash}</label><div class="labelbreak"></div>
        <select name="{$Limitations:item.name}[]" size="8" multiple >
            <option value="-1" {switch match=$current_limitation_list[$Limitations:item.name]}
                               {case match=-1} selected="selected"{/case}
                               {case/}
                               {/switch}>{"Any"|i18n("design/standard/role")}</option>
            {section name=LimitationValues loop=$Limitations:item.values}
                <option value="{$Limitations:LimitationValues:item.value}" {switch match=$Limitations:LimitationValues:item.value}
                                                                           {case in=$current_limitation_list[$Limitations:item.name]}selected="selected"{/case}
                                                                           {case/}
                                                                           {/switch}>
                {$Limitations:LimitationValues:item.Name}</option>
            {/section}   
        </select>
    </div>
    {/section}

    {section name=Limitations loop=$function_limitations}
    {switch match=$Limitations:item.name} 
      {case match="Node"}
       <div class="element">
        <label>{'Node'|i18n('design/standard/role')}</label><div class="labelbreak"></div>
         {section show=$node_list name=NodeList loop=$node_list}
         {$Limitations:NodeList:item.name}
         <input type="checkbox" name="DeleteNodeIDArray[]" value={$Limitations:NodeList:item.node_id} />
         {section-else}
          {'Not specified.'|i18n('design/standard/role')}
         {/section}
         <input class="menubutton" type="image" name="BrowseLimitationNodeButton" value="{'Find'|i18n('design/standard/shop')}" src={"find.png"|ezimage} />
         <input class="menubutton" type="image" name="DeleteNodeButton" value="{'Remove'|i18n('design/standard/shop')}" src={"trash.png"|ezimage} />
       </div>
      {/case}
      {case match="Subtree"}
       <div class="element">
        <label>{'Subtree'|i18n('design/standard/role')}</label><div class="labelbreak"></div>
         {section show=$subtree_list name=SubtreeList loop=$subtree_list}
         {$Limitations:SubtreeList:item.name}
         <input type="checkbox" name="DeleteSubtreeIDArray[]" value={$Limitations:SubtreeList:item.node_id} />
         {section-else}
          {'Not specified.'|i18n('design/standard/role')}
         {/section}

         <input class="menubutton" type="image" name="BrowseLimitationSubtreeButton" value="{'Find'|i18n('design/standard/shop')}" src={"find.png"|ezimage} />
         <input class="menubutton" type="image" name="DeleteSubtreeButton" value="{'Remove'|i18n('design/standard/shop')}" src={"trash.png"|ezimage} />
       </div>
      {/case}
      {case}
      {/case}
    {/switch}
    {/section}

</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <input type="hidden" name="CurrentModule" value="{$current_module}" />
    <input type="hidden" name="CurrentFunction" value="{$current_function}" />
    <input class="button" type="submit" name="UpdatePolicy" value="{'OK'|i18n( 'design/admin/role' )}" />
    <input class="button" type="submit" name="DiscardChange" value="{'Cancel'|i18n( 'design/admin/role' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>