{let object_align='center'}

    {switch match=$object_parameters.align}
        {case match='left'}
            {set object_align='left'}
        {/case}
        {case match='right'}
            {set object_align='right'}
        {/case}
        {case/}
    {/switch}

    <div class="object-{$object_align}">
        {content_view_gui view=$view attribute_parameters=$object_parameters content_object=$object}
    </div>

{/let}

{*
Set variable to true if the object should be rendered as a block
tag. If it should be rendered as inline use false.
{set-block scope=root variable=is_block}true{/set-block}

*}
