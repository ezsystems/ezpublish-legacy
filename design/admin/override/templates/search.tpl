<form method="post" action={"content/action"|ezurl}>


<div class="maincontentheader">
    <h1>{"Search"|i18n("design/standard/content/search")}</h1>
</div>

<div class="block">
    {let adv_url='/content/advancedsearch/'|ezurl}
    <label>{"For more options try the %1Advanced search%2"|i18n("design/standard/content/search","The parameters are link start and end tags.",array(concat("<a href=",$adv_url,">"),"</a>"))}</label>
    {/let}
</div>

{section show=$stop_word_array}
    <p>
    {"The following words were excluded from the search:"|i18n("design/standard/content/search")} 
    {section name=StopWord loop=$stop_word_array}
        {$StopWord:item.word|wash}
        {delimiter}, {/delimiter}
    {/section}
    </p>
{/section}

{switch name=Sw match=$search_count}
{case match=0}
    <div class="warning">
        <h2>{'No results were found when searching for "%1"'|i18n("design/standard/content/search",,array($search_text|wash))}</h2>
    </div>
{/case}
{case}
    <div class="feedback">
    <h2>{'Search for "%1" returned %2 matches'|i18n("design/standard/content/search",,array($search_text|wash,$search_count))}</h2>
    </div>
{/case}
{/switch}

{section name=Child show=$search_result}

    {let name=Child can_remove=false() 
            can_edit=false() 
            can_create=false() 
            can_copy=false()}

    {section loop=$search_result}
        {section show=$:item.object.can_remove}
            {set can_remove=true()}
        {/section}
        {section show=$:item.object.can_edit}
            {set can_edit=true()}
        {/section}
        {section show=$:item.object.can_create}
            {set can_copy=true()}
        {/section}
    {/section}

    <table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
        {section show=$:can_remove}
            <th width="1">
                &nbsp;
            </th>
        {/section}
        <th>
            {"Name"|i18n("design/standard/node/view")}
        </th>
        <th>
            {"Class"|i18n("design/standard/node/view")}
        </th>
        {section show=$:can_edit}
            <th width="1">
                {"Edit"|i18n("design/standard/node/view")}
            </th>
        {/section}
        {section show=$:can_copy}
            <th width="1">
                {"Copy"|i18n("design/standard/node/view")}
            </th>
        {/section}
    </tr>
    {section name=SearchResult loop=$search_result show=$search_result sequence=array(bglight,bgdark)}
        <tr class="{$:sequence}">
            {section show=$:item.object.can_remove}
                <td align="right" width="1">
                    {section show=$:item.object.can_remove}
                        <input type="checkbox" name="DeleteIDArray[]" value="{$:item.node_id}" />
                    {/section}
                </td>
            {/section}
            <td>
                <a href={concat('content/view/full/',$:item.node_id)|ezurl}>
		<a href={concat('content/view/full/',$:item.node_id)|ezurl}>
		{switch match=$:item.object.contentclass_id}
		{case match=4}
		    <img src={"user.gif"|ezimage} border="0" alt="{'User'|i18n('design/standard/node/view')}" />
		{/case}
		{case match=3}
		    <img src={"usergroup.gif"|ezimage} border="0" alt="{'User group'|i18n('design/standard/node/view')}" />
		{/case}
		{case}
		    <img src={"class_2.png"|ezimage} border="0" alt="{'Document'|i18n('design/standard/node/view')}" />
		{/case}
		{/switch}
		&nbsp;
		{$:item.name|wash}</a></a>
            </td>
            <td>
                {$:item.object.class_name|wash}
            </td>

            {section show=$:item.object.can_edit}
                <td width="1">
                    {section show=$:item.object.can_edit}
                        <a href={concat( "content/edit/", $:item.contentobject_id )|ezurl}><img src={"edit.png"|ezimage} alt="Edit" /></a>
                    {/section}
                </td>
            {/section}
            {section show=$:item.object.can_create}
                <td>
                    <a href={concat( "content/copy/", $:item.contentobject_id )|ezurl}><img src={"copy.gif"|ezimage} alt="{'Copy'|i18n( 'design/standard/node/view' )}" /></a>
                </td>
            {/section}
        </tr>
    {/section}
    </table>
    {section show=$:can_remove}
        <input type="submit" name="RemoveButton" value="Remove" />
    {/section}
    {/let}
{/section}


    {let item_previous=sub( $offset, $page_limit ) item_next=sum( $offset, $page_limit )
         page_count=int( ceil( div( $search_count, $page_limit ) ) ) current_page=int( ceil( div( $offset, $page_limit ) ) )}
    <div class="selectbar">
        <table class="selectbar" width="100%" cellpadding="0" cellspacing="2" border="0">
        <tr>
            {switch match=$item_previous|lt(0) }
            {case match=0}
                <td class="selectbar" width="1%">
                    <a class="selectbar" href={concat('/content/search/',$item_previous|gt(0)|choose('',concat('offset/',$item_previous)),'?SearchText=',$search_text_enc)|ezurl}><<&nbsp;{"Previous"|i18n("design/standard/navigator")}</a>
                </td>
           {/case}
           {case match=1}
           {/case}
           {/switch}
           
	   <td width="35%">
                &nbsp;
            </td>

            <td width="10%">
                {section name=Quick loop=$page_count max=10 show=$page_count|gt(1)}
                    {switch match=$Quick:index}
                    {case match=$current_page}
                        <b>{$Quick:number}</b>
                    {/case}
                    {case}
                    {let page_offset=mul( $Quick:index, $page_limit )}
                        <a href={concat( '/content/search/', $Quick:page_offset|gt( 0 )|choose( '', concat( 'offset/', $Quick:page_offset ) ), '?SearchText=', $search_text_enc )|ezurl}>{$Quick:number}</a>
                    {/let}
                    {/case}
                    {/switch}
                {/section}
            </td>
            <td width="35%">
                &nbsp;
            </td>

            {switch match=$item_next|lt($search_count) }
            {case match=1}
                <td class="selectbar" width="1%">
                    <a class="selectbar" href={concat( '/content/search/', $item_next|gt(0)|choose( '', concat( 'offset/', $item_next ) ), '?SearchText=', $search_text_enc )|ezurl}>{"Next"|i18n( "design/standard/navigator" )}&nbsp;&gt;&gt;</a>
                </td>
            {/case}
            {case}
            {/case}
            {/switch}
        </tr>
        </table>
    </div>
    {/let}

</form>