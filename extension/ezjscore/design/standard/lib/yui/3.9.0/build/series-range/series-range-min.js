/* YUI 3.9.0 (build 5827) Copyright 2013 Yahoo! Inc. http://yuilibrary.com/license/ */
YUI.add("series-range",function(e,t){function n(){n.superclass.constructor.apply(this,arguments)}n.NAME="rangeSeries",n.ATTRS={type:{value:"range"},ohlckeys:{valueFn:function(){return{open:"open",high:"high",low:"low",close:"close"}}}},e.extend(n,e.CartesianSeries,{drawSeries:function(){var e=this.get("xcoords"),t=this.get("ycoords"),n=this.get("styles"),r=n.padding,i,s=e.length,o=this.get("width")-(r.left+r.right),u=this.get("ohlckeys"),a=t[u.open],f=t[u.high],l=t[u.low],c=t[u.close],h=o/s,p=h/2;this._drawMarkers(e,a,f,l,c,s,h,p,n)}}),e.RangeSeries=n},"3.9.0",{requires:["series-cartesian"]});
