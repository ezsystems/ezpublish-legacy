{let image_node=fetch( content, node, hash( node_id, $main_node_id ) )
     comment_limit=5}
<div id="comment">

    <div id="image">
        <div class="maincontentheader">
            <h1>Comment on image {$image_node.name|wash}</h1>
        </div>

        {attribute_view_gui attribute=$image_node.data_map.image image_class=small}
        <p>
            {attribute_view_gui attribute=$image_node.object.data_map.caption}
        </p>
    </div>

<form enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/",$edit_language|not|choose(array($edit_language,"/"),''))|ezurl}>

    {include uri="design:content/edit_validation.tpl"}

    <input type="hidden" name="MainNodeID" value="{$main_node_id}" />
    <input type="hidden" name="DiscardConfirm" value="0" />

    {let subject_attribute=$content_attributes_data_map.subject}
    <div id="subject">
        <label>Subject</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$subject_attribute.id}" />
        {attribute_edit_gui attribute_base=$attribute_base attribute=$subject_attribute}
    </div>
    {/let}

    {let name_attribute=$content_attributes_data_map.name}
    <div id="name">
        <label>Your name</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$name_attribute.id}" />
        {attribute_edit_gui attribute_base=$attribute_base attribute=$name_attribute}
    </div>
    {/let}

    {let email_attribute=$content_attributes_data_map.email}
    <div id="email">
        <label>Your E-Mail</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$email_attribute.id}" />
        {attribute_edit_gui attribute_base=$attribute_base attribute=$email_attribute}
    </div>
    {/let}

    {let url_attribute=$content_attributes_data_map.url}
    <div id="url">
        <label>Homepage URL</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$url_attribute.id}" />
        {attribute_edit_gui attribute_base=$attribute_base attribute=$url_attribute}
    </div>
    {/let}

    {let comment_attribute=$content_attributes_data_map.comment}
    <div id="comment">
        <label>Comment</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$comment_attribute.id}" />
        {attribute_edit_gui attribute_base=$attribute_base attribute=$comment_attribute}
    </div>
    {/let}

    <br/>

    <div class="buttonblock">
        <input class="button" type="submit" name="DiscardButton" value="Back" />
        <input class="button" type="submit" name="PreviewButton" value="Preview" />
        <input class="defaultbutton" type="submit" name="PublishButton" value="Continue" />
    </div>

   {let comments=fetch( 'content', 'list', hash( parent_node_id, $node.node_id,
                                                 sort_by ,array( array( 'published', true() ) ),
                                                 limit, $comment_limit,
                                                 class_filter_type, 'include',
                                                 class_filter_array, array( "comment" ) ) )}
    {section show=$comments}
    <div id="commentlist">
        <h2>Latest comments</h2>
        {section var=comment loop=$comments}
            {node_view_gui view=line content_node=$comment.item}
        {/section}
    </div>
    {/section}
    {/let}
                                                         

</div>

{*
<form enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/",$edit_language|not|choose(array($edit_language,"/"),''))|ezurl}>
<table class="layout" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top">
    <div class="maincontentheader">
    <h1>{"Edit %1 - %2"|i18n("design/standard/content/edit",,array($class.name|wash,$object.name|wash))}</h1>
    </div>

    {include uri="design:content/edit_validation.tpl"}

    <input type="hidden" name="MainNodeID" value="{$main_node_id}" /> 
    <br/>

    {include uri="design:content/edit_attribute.tpl"}
    <br/>
    
    <div class="buttonblock">
    <input class="button" type="submit" name="PreviewButton" value="{'Preview'|i18n('design/standard/content/edit')}" />
    <input class="button" type="submit" name="DiscardButton" value="{'Discard'|i18n('design/standard/content/edit')}" />
    <input class="defaultbutton" type="submit" name="PublishButton" value="{'Post'|i18n('design/standard/content/edit')}" />
    </div>
    </td>
</tr>
</table>

</form>
*}

{/let}
