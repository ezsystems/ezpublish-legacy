{switch name=sw match=$level}
{case match=1}
<h2>{$content}</h2>
{/case}
{case match=2}
<h3>{$content}</h3>
{/case}
{case match=3}
<h4>{$content}</h4>
{/case}
{case match=4}
<h5>{$content}</h5>
{/case}
{case}
<h2>{$content}</h2>
{/case}
{/switch}
