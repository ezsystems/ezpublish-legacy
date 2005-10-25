{let topic_list=fetch('content','list',hash( parent_node_id, $object.main_node_id,
                                             limit, 5,
                                             sort_by, array( array(published,false() ) ) ) )}
<div class="view-embed">
    <div class="class-forum">
        <a href={$object.main_node.url_alias|ezurl}><h3>{"Latest from"|i18n("design/base")}: {$object.name|wash}</h3></a>
        <ul>
        {section var=topic loop=$topic_list}
            <li><a href={$topic.url_alias|ezurl()}>{$topic.name}</a></li>
        {/section}
        </ul>
    </div>
</div>

{/let}