/*
Copyright (c) 2009, Yahoo! Inc. All rights reserved.
Code licensed under the BSD License:
http://developer.yahoo.net/yui/license.txt
version: 3.0.0b1
build: 1163
*/
YUI.add("dump",function(G){var B=G.Lang,C="{...}",F="f(){...}",A=", ",D=" => ",E=function(M,L){var I,H,K=[],J=B.type(M);if(!B.isObject(M)){return M+"";}else{if(J=="date"||("nodeType" in M&&"tagName" in M)){return M;}else{if(J=="function"){return F;}}}L=(B.isNumber(L))?L:3;if(J=="array"){K.push("[");for(I=0,H=M.length;I<H;I=I+1){if(B.isObject(M[I])){K.push((L>0)?B.dump(M[I],L-1):C);}else{K.push(M[I]);}K.push(A);}if(K.length>1){K.pop();}K.push("]");}else{if(J=="regexp"){K.push(M.toString());}else{K.push("{");for(I in M){if(M.hasOwnProperty(I)){K.push(I+D);if(B.isObject(M[I])){K.push((L>0)?B.dump(M[I],L-1):C);}else{K.push(M[I]);}K.push(A);}}if(K.length>1){K.pop();}K.push("}");}}return K.join("");};G.dump=E;B.dump=E;},"3.0.0b1");