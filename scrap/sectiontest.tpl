 <code>
 array<br/>
 {section name=News max=4 loop=array(1,2,3,4,5,6,7,8,9) offset=1}
 {section-exclude match=array(3,5)|contains($:item)}
 {$:item}<br>
 {/section}
 </code>
 
 <code>
 number<br/>
 {section name=News max=4 loop=9 offset=1}
 {section-exclude match=array(3,5)|contains($:item)}
 {$:item}<br>
 {/section}
 </code>

 <code>
 negative number<br/>
 {section name=News max=4 loop=-9 offset=1}
 {section-exclude match=array(-3,-5)|contains($:item)}
 {$:item}<br>
 {/section}
 </code>

 <code>
 string<br/>
 {section name=News max=4 loop="abcdefhijklm" offset=0}
 {section-exclude match=array("c","e")|contains($:item)}
 {$:item}<br>
 {/section}
 </code>
