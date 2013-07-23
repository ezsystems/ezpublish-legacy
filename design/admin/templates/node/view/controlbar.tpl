<div class="controlbar">
    {* DESIGN: Control bar START *}

    <form method="post" action={'content/action'|ezurl}>
        <input type="hidden" name="TopLevelNode" value="{$node.object.main_node_id}" />
        <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
        <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id}" />

        <div class="button-left">
            <div class='block'>
                {foreach ezini( 'NodeControlbar', 'Controls', 'controlbar.ini' ) as $cb_control}
                    {def $cb_template=ezini( concat('NodeControlbar_', $cb_control ), 'Template', 'controlbar.ini' )}
                    {def $cb_available_for_all=ezini( concat('NodeControlbar_', $cb_control ), 'AvailableForAllClasses', 'controlbar.ini' )}
                    {def $cb_classes = ezini( concat('NodeControlbar_', $cb_control ), 'AvailableForClasses', 'controlbar.ini' )}

                    {if or( eq($cb_available_for_all, 'true'), $cb_classes|contains( $node.class_identifier ) )}
                        {include uri=concat( 'design:node/view/controlbar/', $cb_template )}
                    {/if}

                    {undef $cb_template $cb_available_for_all $cb_classes}
                {/foreach}
            </div>
        </div>

        <div class="button-right">
            <p class='versions'>
                {* Link to manage versions *}
                <a href="{concat("content/history/", $node.contentobject_id )|ezurl('no')}" title="{'View and manage (copy, delete, etc.) the versions of this object.'|i18n( 'design/admin/content/edit' )}">{'Manage versions'|i18n( 'design/admin/content/edit' )}</a>
            </p>
        </div>

        <div class="float-break"></div>
    </form>
    {* DESIGN: Control bar END *}
</div>