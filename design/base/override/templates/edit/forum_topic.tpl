{* Forum topic - Edit *}

<div class="edit">
    <div class="class-forum-topic">

        <form enctype="multipart/form-data" method="post" action={concat( "/content/edit/", $object.id, "/", $edit_version, "/", $edit_language|not|choose( concat( $edit_language, "/" ), '' ) )|ezurl}>

        <h1>{"Edit %1 - %2"|i18n("design/base",,array($class.name|wash,$object.name|wash))}</h1>

        {include uri="design:content/edit_validation.tpl"}

        <input type="hidden" name="MainNodeID" value="{$main_node_id}" />

        <h3>{'Subject'|i18n('design/base')}</h3>
        {attribute_edit_gui attribute=$object.data_map.subject}
        <h3>{'Message'|i18n('design/base')}</h3>
        {attribute_edit_gui attribute=$object.data_map.message}

        <h3>{'Notify me about updates'|i18n('design/base')}</h3>
        {attribute_edit_gui attribute=$object.data_map.notify_me}

        {let current_user=fetch( 'user', 'current_user' )
             sticky_groups=ezini( 'ForumSettings', 'StickyUserGroupArray', 'forum.ini' )}

        {$current_user.groups|contains($sticky)}

            {section var=sticky loop=$sticky_groups}
                {if $current_user.groups|contains($sticky)}
                <h3>{'Sticky'|i18n('design/base')}</h3>
                {attribute_edit_gui attribute=$object.data_map.sticky}
                {/if}
            {/section}
        {/let}

        <br/>

        <div class="buttonblock">
            <input class="defaultbutton" type="submit" name="PublishButton" value="{'Send for publishing'|i18n('design/base')}" />
            <input class="button" type="submit" name="DiscardButton" value="{'Discard'|i18n('design/base')}" />
            <input type="hidden" name="DiscardConfirm" value="0" />
        </div>

        </form>
    </div>
</div>


