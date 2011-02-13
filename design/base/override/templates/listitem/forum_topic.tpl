{* Forum Topic - List item view *}

<div class="content-view-listitem">
    <div class="class-forum-topic">

    <h3><a href={$node.url_alias|ezurl()}>{$node.name|wash}</a></h3>
    <div class="content-byline">{$node.modified_subnode|l10n(shortdatetime)}</div>

    </div>
</div>