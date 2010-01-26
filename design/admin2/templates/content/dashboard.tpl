{set scope=global persistent_variable=hash('extra_menu', false())}

<div class="context-block">
    
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Dashboard'|i18n( 'design/admin/content/dashboard' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="block">

{foreach $blocks as $block sequence array( 'left', 'right' ) as $position}

    <div class="{$position}">
    {if $block.template}
        {include uri=concat( 'design:', $block.template )}
    {else}
        {include uri=concat( 'design:dashboard/', $block.identifier, '.tpl' )}
    {/if}
    </div>
    
    {delimiter modulo=2}
    <div class="float-break"></div>
    {/delimiter}

{/foreach}

</div>

<div class="float-break"></div>


{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>