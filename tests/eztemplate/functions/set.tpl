{let var1=1
     var2=1
     var3=1
     var4=1}

var1='{$var1}'
var2='{$var2}'
var3='{$var3}'
var4='{$var4}'

{set var1=2
     var2=3
     var3=42}

var1='{$var1}'
var2='{$var2}'
var3='{$var3}'

{let name=OtherSpace var5=1}

{set scope=root var4=43}
var4='{$var4}'

var5='{$:var5}'

{set var5=44}
var5='{$:var5}'

{set scope=relative var5=45}
var5='{$:var5}'

{set name=OtherSpace scope=root var5=46}
var5='{$:var5}'

{include uri="tests/eztemplate/functions/set_included.tpl.tst"}
var5='{$:var5}'

{/let}

var4='{$var4}'

{/let}
