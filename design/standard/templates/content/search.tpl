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
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
{section show=$search_result}
<th>{"Name"|i18n("design/standard/content/search")}</th>
<th>{"Class"|i18n("design/standard/content/search")}</th>
<tr>
  {section name=SearchResult loop=$search_result show=$search_result sequence=array(bglight,bgdark)}
    <td class="{$SearchResult:sequence}">
    <a href={concat("/content/view/full/",$SearchResult:item.main_node_id)|ezurl}>{$SearchResult:item.name|wash}</a>
       
    </td>
    <td class="{$SearchResult:sequence}">
      {$SearchResult:item.object.class_name|wash}
    </td>
  {delimiter modulo=1}
</tr>
<tr>
  {/delimiter}
  {section-else}
  {/section}
{/section}
</tr>
</table>



{let item_previous=sub($offset,$page_limit) item_next=sum($offset,$page_limit)
     page_count=int(ceil(div($search_count,$page_limit))) current_page=int(ceil(div($offset,$page_limit)))}
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
        {let page_offset=mul($Quick:index,$page_limit)}
          <a href={concat('/content/search/',$Quick:page_offset|gt(0)|choose('',concat('offset/',$Quick:page_offset)),'?SearchText=',$search_text_enc)|ezurl}>{$Quick:number}</a>
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
         <a class="selectbar" href={concat('/content/search/',$item_next|gt(0)|choose('',concat('offset/',$item_next)),'?SearchText=',$search_text_enc)|ezurl}>{"Next"|i18n("design/standard/navigator")}&nbsp;&gt;&gt;</a>
        </td>
      {/case}
      {case}
      {/case}
    {/switch}
</tr>
</table>
</div>
{/let}

