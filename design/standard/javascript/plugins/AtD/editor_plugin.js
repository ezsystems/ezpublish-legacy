
var EXPORTED_SYMBOLS=['AtDCore'];function AtDCore(){this.ignore_types=['Bias Language','Cliches','Complex Expression','Diacritical Marks','Double Negatives','Hidden Verbs','Jargon Language','Passive voice','Phrases to Avoid','Redundant Expression'];this.ignore_strings={};this.i18n={};};AtDCore.prototype.getLang=function(key,defaultk){if(this.i18n[key]==undefined)
return defaultk;return this.i18n[key];};AtDCore.prototype.addI18n=function(localizations){this.i18n=localizations;};AtDCore.prototype.setIgnoreStrings=function(string){var parent=this;this.map(string.split(/,\s*/g),function(string){parent.ignore_strings[string]=1;});};AtDCore.prototype.showTypes=function(string){var show_types=string.split(/,\s*/g);var types={};types["Double Negatives"]=1;types["Hidden Verbs"]=1;types["Passive voice"]=1;types["Bias Language"]=1;types["Cliches"]=1;types["Complex Expression"]=1;types["Diacritical Marks"]=1;types["Jargon Language"]=1;types["Phrases to Avoid"]=1;types["Redundant Expression"]=1;var ignore_types=[];this.map(show_types,function(string){types[string]=undefined;});this.map(this.ignore_types,function(string){if(types[string]!=undefined)
ignore_types.push(string);});this.ignore_types=ignore_types;};AtDCore.prototype.makeError=function(error_s,tokens,type,seps,pre){var struct=new Object();struct.type=type;struct.string=error_s;struct.tokens=tokens;if(new RegExp("\\b"+error_s+"\\b").test(error_s)){struct.regexp=new RegExp("(?!"+error_s+"<)\\b"+error_s.replace(/\s+/g,seps)+"\\b");}
else if(new RegExp(error_s+"\\b").test(error_s)){struct.regexp=new RegExp("(?!"+error_s+"<)"+error_s.replace(/\s+/g,seps)+"\\b");}
else if(new RegExp("\\b"+error_s).test(error_s)){struct.regexp=new RegExp("(?!"+error_s+"<)\\b"+error_s.replace(/\s+/g,seps));}
else{struct.regexp=new RegExp("(?!"+error_s+"<)"+error_s.replace(/\s+/g,seps));}
struct.used=false;return struct;};AtDCore.prototype.addToErrorStructure=function(errors,list,type,seps){var parent=this;this.map(list,function(error){var tokens=error["word"].split(/\s+/);var pre=error["pre"];var first=tokens[0];if(errors['__'+first]==undefined){errors['__'+first]=new Object();errors['__'+first].pretoks={};errors['__'+first].defaults=new Array();}
if(pre==""){errors['__'+first].defaults.push(parent.makeError(error["word"],tokens,type,seps,pre));}else{if(errors['__'+first].pretoks['__'+pre]==undefined)
errors['__'+first].pretoks['__'+pre]=new Array();errors['__'+first].pretoks['__'+pre].push(parent.makeError(error["word"],tokens,type,seps,pre));}});};AtDCore.prototype.buildErrorStructure=function(spellingList,enrichmentList,grammarList){var seps=this._getSeparators();var errors={};this.addToErrorStructure(errors,spellingList,"hiddenSpellError",seps);this.addToErrorStructure(errors,grammarList,"hiddenGrammarError",seps);this.addToErrorStructure(errors,enrichmentList,"hiddenSuggestion",seps);return errors;};AtDCore.prototype._getSeparators=function(){var re='',i;var str='"s!#$%&()*+,./:;<=>?@[\]^_{|}';for(i=0;i<str.length;i++)
re+='\\'+str.charAt(i);return"(?:(?:[\xa0"+re+"])|(?:\\-\\-))+";};AtDCore.prototype.processXML=function(responseXML){var types={};this.map(this.ignore_types,function(type){types[type]=1;});this.suggestions=[];var errors=responseXML.getElementsByTagName('error');var grammarErrors=[];var spellingErrors=[];var enrichment=[];for(var i=0;i<errors.length;i++){if(errors[i].getElementsByTagName('string').item(0).firstChild!=null){var errorString=errors[i].getElementsByTagName('string').item(0).firstChild.data;var errorType=errors[i].getElementsByTagName('type').item(0).firstChild.data;var errorDescription=errors[i].getElementsByTagName('description').item(0).firstChild.data;var errorContext;if(errors[i].getElementsByTagName('precontext').item(0).firstChild!=null)
errorContext=errors[i].getElementsByTagName('precontext').item(0).firstChild.data;else
errorContext="";if(this.ignore_strings[errorString]==undefined){var suggestion={};suggestion["description"]=errorDescription;suggestion["suggestions"]=[];suggestion["matcher"]=new RegExp('^'+errorString.replace(/\s+/,this._getSeparators())+'$');suggestion["context"]=errorContext;suggestion["string"]=errorString;suggestion["type"]=errorType;this.suggestions.push(suggestion);if(errors[i].getElementsByTagName('suggestions').item(0)!=undefined){var suggestions=errors[i].getElementsByTagName('suggestions').item(0).getElementsByTagName('option');for(var j=0;j<suggestions.length;j++)
suggestion["suggestions"].push(suggestions[j].firstChild.data);}
if(errors[i].getElementsByTagName('url').item(0)!=undefined){var errorUrl=errors[i].getElementsByTagName('url').item(0).firstChild.data;suggestion["moreinfo"]=errorUrl+'&theme=tinymce';}
if(types[errorDescription]==undefined){if(errorType=="suggestion")
enrichment.push({word:errorString,pre:errorContext});if(errorType=="grammar")
grammarErrors.push({word:errorString,pre:errorContext});}
if(errorType=="spelling"||errorDescription=="Homophone")
spellingErrors.push({word:errorString,pre:errorContext});if(errorDescription=='Cliches')
suggestion["description"]='Clich&eacute;s';if(errorDescription=="Spelling")
suggestion["description"]=this.getLang('menu_title_spelling','Spelling');if(errorDescription=="Repeated Word")
suggestion["description"]=this.getLang('menu_title_repeated_word','Repeated Word');if(errorDescription=="Did you mean...")
suggestion["description"]=this.getLang('menu_title_confused_word','Did you mean...');}}}
var errorStruct;var ecount=spellingErrors.length+grammarErrors.length+enrichment.length;if(ecount>0)
errorStruct=this.buildErrorStructure(spellingErrors,enrichment,grammarErrors);else
errorStruct=undefined;return{errors:errorStruct,count:ecount,suggestions:this.suggestions};};AtDCore.prototype.findSuggestion=function(element){var text=element.innerHTML;var context=(this.getAttrib(element,'pre')+"").replace(/[\\,!\\?\\."\s]/g,'');if(this.getAttrib(element,'pre')==undefined)
{alert(element.innerHTML);}
var errorDescription=undefined;var len=this.suggestions.length;for(var i=0;i<len;i++){var key=this.suggestions[i]["string"];if((context==""||context==this.suggestions[i]["context"])&&this.suggestions[i]["matcher"].test(text)){errorDescription=this.suggestions[i];break;}}
return errorDescription;};function TokenIterator(tokens){this.tokens=tokens;this.index=0;this.count=0;this.last=0;};TokenIterator.prototype.next=function(){var current=this.tokens[this.index];this.count=this.last;this.last+=current.length+1;this.index++;if(current!=""){if(current[0]=="'")
current=current.substring(1,current.length);if(current[current.length-1]=="'")
current=current.substring(0,current.length-1);}
return current;};TokenIterator.prototype.hasNext=function(){return this.index<this.tokens.length;};TokenIterator.prototype.hasNextN=function(n){return(this.index+n)<this.tokens.length;};TokenIterator.prototype.skip=function(m,n){this.index+=m;this.last+=n;if(this.index<this.tokens.length)
this.count=this.last-this.tokens[this.index].length;};TokenIterator.prototype.getCount=function(){return this.count;};TokenIterator.prototype.peek=function(n){var peepers=new Array();var end=this.index+n;for(var x=this.index;x<end;x++)
peepers.push(this.tokens[x]);return peepers;};AtDCore.prototype.markMyWords=function(container_nodes,errors){var seps=new RegExp(this._getSeparators());var nl=new Array();var ecount=0;var parent=this;this._walk(container_nodes,function(n){if(n.nodeType==3&&!parent.isMarkedNode(n))
nl.push(n);});var iterator;this.map(nl,function(n){var v;if(n.nodeType==3){v=n.nodeValue;var tokens=n.nodeValue.split(seps);var previous="";var doReplaces=[];iterator=new TokenIterator(tokens);while(iterator.hasNext()){var token=iterator.next();var current=errors['__'+token];var defaults;if(current!=undefined&&current.pretoks!=undefined){defaults=current.defaults;current=current.pretoks['__'+previous];var done=false;var prev,curr;prev=v.substr(0,iterator.getCount());curr=v.substr(prev.length,v.length);var checkErrors=function(error){if(error!=undefined&&!error.used&&foundStrings['__'+error.string]==undefined&&error.regexp.test(curr)){var oldlen=curr.length;foundStrings['__'+error.string]=1;doReplaces.push([error.regexp,'<span class="'+error.type+'" pre="'+previous+'">$&</span>']);error.used=true;done=true;}};var foundStrings={};if(current!=undefined){previous=previous+' ';parent.map(current,checkErrors);}
if(!done){previous='';parent.map(defaults,checkErrors);}}
previous=token;}
if(doReplaces.length>0){newNode=n;for(var x=0;x<doReplaces.length;x++){var regexp=doReplaces[x][0],result=doReplaces[x][1];var bringTheHurt=function(node){if(node.nodeType==3){ecount++;if(parent.isIE()&&node.nodeValue.length>0&&node.nodeValue.substr(0,1)==' ')
return parent.create('<span class="mceItemHidden">&nbsp;</span>'+node.nodeValue.substr(1,node.nodeValue.length-1).replace(regexp,result),false);else
return parent.create(node.nodeValue.replace(regexp,result),false);}
else{var contents=parent.contents(node);for(var y=0;y<contents.length;y++){if(contents[y].nodeType==3&&regexp.test(contents[y].nodeValue)){var nnode;if(parent.isIE()&&contents[y].nodeValue.length>0&&contents[y].nodeValue.substr(0,1)==' ')
nnode=parent.create('<span class="mceItemHidden">&nbsp;</span>'+contents[y].nodeValue.substr(1,contents[y].nodeValue.length-1).replace(regexp,result),true);else
nnode=parent.create(contents[y].nodeValue.replace(regexp,result),true);parent.replaceWith(contents[y],nnode);parent.removeParent(nnode);ecount++;return node;}}
return node;}};newNode=bringTheHurt(newNode);}
parent.replaceWith(n,newNode);}}});return ecount;};AtDCore.prototype._walk=function(elements,f){var i;for(i=0;i<elements.length;i++){f.call(f,elements[i]);this._walk(this.contents(elements[i]),f);}};AtDCore.prototype.removeWords=function(node,w){var count=0;var parent=this;this.map(this.findSpans(node).reverse(),function(n){if(n&&(parent.isMarkedNode(n)||parent.hasClass(n,'mceItemHidden')||parent.isEmptySpan(n))){if(n.innerHTML=='&nbsp;'){var nnode=document.createTextNode(' ');parent.replaceWith(n,nnode);}
else if(!w||n.innerHTML==w){parent.removeParent(n);count++;}}});return count;};AtDCore.prototype.isEmptySpan=function(node){return(this.getAttrib(node,'class')==""&&this.getAttrib(node,'style')==""&&this.getAttrib(node,'id')==""&&!this.hasClass(node,'Apple-style-span')&&this.getAttrib(node,'mce_name')=="");};AtDCore.prototype.isMarkedNode=function(node){return(this.hasClass(node,'hiddenGrammarError')||this.hasClass(node,'hiddenSpellError')||this.hasClass(node,'hiddenSuggestion'));};AtDCore.prototype.applySuggestion=function(element,suggestion){if(suggestion=='(omit)'){this.remove(element);}
else{var node=this.create(suggestion);this.replaceWith(element,node);this.removeParent(node);}};AtDCore.prototype.hasErrorMessage=function(xmlr){return(xmlr!=undefined&&xmlr.getElementsByTagName('message').item(0)!=null);};AtDCore.prototype.getErrorMessage=function(xmlr){return xmlr.getElementsByTagName('message').item(0);};AtDCore.prototype.isIE=function(){return navigator.appName=='Microsoft Internet Explorer';};(function()
{var JSONRequest=tinymce.util.JSONRequest,each=tinymce.each,DOM=tinymce.DOM;tinymce.create('tinymce.plugins.AfterTheDeadlinePlugin',{getInfo:function()
{return
({longname:'After The Deadline',author:'Raphael Mudge',authorurl:'http://blog.afterthedeadline.com',infourl:'http://www.afterthedeadline.com',version:tinymce.majorVersion+"."+tinymce.minorVersion});},initAtDCore:function(editor,plugin)
{var core=new AtDCore();core.map=each;core.getAttrib=function(node,key)
{return editor.dom.getAttrib(node,key);};core.findSpans=function(parent)
{if(parent==undefined)
return editor.dom.select('span');else
return editor.dom.select('span',parent);};core.hasClass=function(node,className)
{return editor.dom.hasClass(node,className);};core.contents=function(node)
{return node.childNodes;};core.replaceWith=function(old_node,new_node)
{return editor.dom.replace(new_node,old_node);};core.create=function(node_html)
{return editor.dom.create('span',{'class':'mceItemHidden'},node_html);};core.removeParent=function(node)
{editor.dom.remove(node,1);return node;};core.remove=function(node)
{editor.dom.remove(node);};core.getLang=function(key,defaultk)
{return editor.getLang("AtD."+key,defaultk);};core.setIgnoreStrings(editor.getParam("atd_ignore_strings",""));core.showTypes(editor.getParam("atd_show_types",""));return core;},init:function(ed,url)
{var t=this;var plugin=this;var editor=ed;var core=this.initAtDCore(editor,plugin);this.url=url;this.editor=ed;ed.core=core;var ignore=tinymce.util.Cookie.getHash('atd_ignore');if(ignore==undefined)
{ignore={};}
editor.addCommand('mceWritingImprovementTool',function(callback)
{if(typeof AtD_proofread_click_count!="undefined")
AtD_proofread_click_count++;plugin.editor.setProgressState(1);plugin._removeWords();plugin.sendRequest('checkDocument',ed.getContent({format:'raw'}),function(data,request,someObject)
{plugin.editor.setProgressState(0);if(request.status!=200||request.responseText.substr(1,4)=='html')
{ed.windowManager.alert(plugin.editor.getLang('AtD.message_server_error','There was a problem communicating with the After the Deadline service. Try again in one minute.'));return;}
if(request.responseXML.getElementsByTagName('message').item(0)!=null)
{ed.windowManager.alert(request.responseXML.getElementsByTagName('message').item(0).firstChild.data);return;}
var results=core.processXML(request.responseXML);var ecount=0;if(results.count>0)
{ecount=plugin.markMyWords(results.errors);ed.suggestions=results.suggestions;}
if(ecount==0&&(!callback||callback==undefined))
ed.windowManager.alert(plugin.editor.getLang('AtD.message_no_errors_found','No writing errors were found.'));else if(callback)
callback(ecount);});});editor.onInit.add(function()
{if(editor.settings.content_css!==false)
{editor.dom.loadCSS(editor.getParam("atd_css_url",url+'/css/content.css'));}});editor.onClick.add(plugin._showMenu,plugin);editor.onContextMenu.add(plugin._showMenu,plugin);editor.onPreProcess.add(function(sender,object)
{var dom=sender.dom;each(dom.select('span',object.node).reverse(),function(n)
{if(n&&(dom.hasClass(n,'hiddenGrammarError')||dom.hasClass(n,'hiddenSpellError')||dom.hasClass(n,'hiddenSuggestion')||dom.hasClass(n,'mceItemHidden')||(dom.getAttrib(n,'class')==""&&dom.getAttrib(n,'style')==""&&dom.getAttrib(n,'id')==""&&!dom.hasClass(n,'Apple-style-span')&&dom.getAttrib(n,'mce_name')=="")))
{dom.remove(n,1);}});});editor.onBeforeExecCommand.add(function(editor,command)
{if(command=='mceCodeEditor')
{plugin._removeWords();}
else if(command=='mceFullScreen')
{plugin._done();}});},createControl:function(name,controlManager)
{var control=this;if(name=='AtD')
{return controlManager.createButton(name,{title:this.editor.getLang('AtD.button_proofread_tooltip','Proofread Writing'),image:this.editor.getParam('atd_button_url',this.url+'/atdbuttontr.gif'),cmd:'mceWritingImprovementTool',scope:control});}},_removeWords:function(w)
{var ed=this.editor,dom=ed.dom,se=ed.selection,b=se.getBookmark();ed.core.removeWords(undefined,w);dom.setHTML(dom.getRoot(),dom.getRoot().innerHTML);se.moveToBookmark(b);},markMyWords:function(errors)
{var ed=this.editor;var se=ed.selection,b=se.getBookmark();var ecount=ed.core.markMyWords(ed.core.contents(this.editor.getBody()),errors);se.moveToBookmark(b);return ecount;},_showMenu:function(ed,e)
{var t=this,ed=t.editor,m=t._menu,p1,dom=ed.dom,vp=dom.getViewPort(ed.getWin());var plugin=this;if(!m)
{p1=DOM.getPos(ed.getContentAreaContainer());m=ed.controlManager.createDropMenu('spellcheckermenu',{offset_x:p1.x,offset_y:p1.y,'class':'mceNoIcons'});t._menu=m;}
if(ed.core.isMarkedNode(e.target))
{m.removeAll();var errorDescription=ed.core.findSuggestion(e.target);if(errorDescription==undefined)
{m.add({title:plugin.editor.getLang('AtD.menu_title_no_suggestions','No suggestions'),'class':'mceMenuItemTitle'}).setDisabled(1);}
else if(errorDescription["suggestions"].length==0)
{m.add({title:errorDescription["description"],'class':'mceMenuItemTitle'}).setDisabled(1);}
else
{m.add({title:errorDescription["description"],'class':'mceMenuItemTitle'}).setDisabled(1);for(var i=0;i<errorDescription["suggestions"].length;i++)
{(function(sugg)
{m.add({title:sugg,onclick:function()
{ed.core.applySuggestion(e.target,sugg);t._checkDone();}});})(errorDescription["suggestions"][i]);}
m.addSeparator();}
if(errorDescription!=undefined&&errorDescription["moreinfo"]!=null)
{(function(url)
{m.add({title:plugin.editor.getLang('AtD.menu_option_explain','Explain...'),onclick:function()
{ed.windowManager.open({url:url,width:480,height:380,inline:true},{theme_url:this.url});}});})(errorDescription["moreinfo"]);m.addSeparator();}
m.add({title:plugin.editor.getLang('AtD.menu_option_ignore_once','Ignore suggestion'),onclick:function()
{dom.remove(e.target,1);t._checkDone();}});if(String(this.editor.getParam("atd_ignore_enable","false"))=="true")
{m.add({title:plugin.editor.getLang('AtD.menu_option_ignore_always','Ignore always'),onclick:function()
{var url=t.editor.getParam('atd_ignore_rpc_url','{backend}');if(url=='{backend}')
{var ignore=tinymce.util.Cookie.getHash('atd_ignore');if(ignore==undefined){ignore={};}
ignore[e.target.innerHTML]=1;tinymce.util.Cookie.setHash('atd_ignore',ignore,new Date((new Date().getTime())+157680000000));}
else
{var id=t.editor.getParam("atd_rpc_id","12345678");tinymce.util.XHR.send({url:url+encodeURI(e.target.innerHTML).replace(/&/g,'%26')+"&key="+id,content_type:'text/xml',async:true,type:'GET',success:function(type,req,o)
{},error:function(type,req,o)
{alert("Ignore preference save failed\n"+type+"\n"+req.status+"\nAt: "+o.url);}});t.editor.core.setIgnoreStrings(e.target.innerHTML);}
t._removeWords(e.target.innerHTML);t._checkDone();}});}
else
{m.add({title:plugin.editor.getLang('menu_option_ignore_all','Ignore all'),onclick:function()
{t._removeWords(e.target.innerHTML);t._checkDone();}});}
ed.selection.select(e.target);p1=dom.getPos(e.target);m.showMenu(p1.x,p1.y+e.target.offsetHeight-vp.y);return tinymce.dom.Event.cancel(e);}
else
{m.hideMenu();}},_checkDone:function()
{var t=this,ed=t.editor,dom=ed.dom,o;each(dom.select('span'),function(n)
{if(n&&dom.hasClass(n,'mceItemHidden'))
{o=true;return false;}});if(!o)
{t._done();}},_done:function()
{var plugin=this;plugin._removeWords();if(plugin._menu)
{plugin._menu.hideMenu();}
plugin.editor.nodeChanged();},sendRequest:function(file,data,success)
{var id=this.editor.getParam("atd_rpc_id","12345678");var url=this.editor.getParam("atd_rpc_url","{backend}");var plugin=this;if(url=='{backend}'||id=='12345678')
{this.editor.setProgressState(0);alert('Please specify: atd_rpc_url and atd_rpc_id');return;}
tinymce.util.XHR.send({url:url+"/"+file,content_type:'text/xml',type:"POST",data:"data="+encodeURI(data).replace(/&/g,'%26')+"&key="+id,async:true,success:success,error:function(type,req,o)
{plugin.editor.setProgressState(0);alert(type+"\n"+req.status+"\nAt: "+o.url);}});}});tinymce.PluginManager.add('AtD',tinymce.plugins.AfterTheDeadlinePlugin);})();