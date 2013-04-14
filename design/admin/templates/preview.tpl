{* Content (pre)view in content window. *}
{def $custom_actions = $node.object.content_action_list}
{if $custom_actions}
    <form method="post" action={'content/action'|ezurl}>
        <input type="hidden" name="TopLevelNode" value="{$node.object.main_node_id}" />
        <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
        <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id}" />
{/if}

    {node_view_gui content_node=$node view='admin_preview'}

{if $custom_actions}
        <div class="button-right">
        <div class="block">
            {* Custom content action buttons. *}
            {foreach $custom_actions as $custom_action}
                <input class="button" type="submit" name="{$custom_action.action}" value="{$custom_action.name}" />
            {/foreach}
        </div>
        </div>
    </form>
{/if}