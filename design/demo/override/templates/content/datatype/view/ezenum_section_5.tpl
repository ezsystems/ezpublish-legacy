{section name=EnumObjectList loop=$attribute.content.enumobject_list sequence=array(bglight,bgdark)}
<img src={concat("star-",$EnumObjectList:item.enumelement,".gif")|ezimage}/>{/section}
