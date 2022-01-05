(()=>{var O=Object.defineProperty,x=Object.defineProperties;var P=Object.getOwnPropertyDescriptors;var b=Object.getOwnPropertySymbols;var j=Object.prototype.hasOwnProperty,F=Object.prototype.propertyIsEnumerable;var m=(r,i,o)=>i in r?O(r,i,{enumerable:!0,configurable:!0,writable:!0,value:o}):r[i]=o,g=(r,i)=>{for(var o in i||(i={}))j.call(i,o)&&m(r,o,i[o]);if(b)for(var o of b(i))F.call(i,o)&&m(r,o,i[o]);return r},C=(r,i)=>x(r,P(i));(function(){"use strict";var r={props:{blueprint:String,lock:[Boolean,Object],help:String,name:String,parent:String,timestamp:Number},methods:{load(){return this.$api.get(`${this.parent}/sections/${this.name}`)}}},i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("section",{staticClass:"k-info-section"},[n("k-headline",{staticClass:"k-info-section-headline"},[t._v(" "+t._s(t.headline)+" ")]),n("k-box",{attrs:{theme:t.theme}},[n("k-text",t._l(t.text,function(c,s){return n("div",{key:s,staticClass:"k-text-punctuation"},[n("span",[t._v(t._s(c.label)+":")]),t._l(c.chars,function(_,h){return n("button",{key:h,staticClass:"k-button k-button-punctuation",attrs:{type:"button"},on:{click:function(p){return t.writeToClipboard(_)}}},[t._v(" "+t._s(_)+" ")])})],2)}),0)],1)],1)},o=[],N="";function $(t,e,n,c,s,_,h,p){var a=typeof t=="function"?t.options:t;e&&(a.render=e,a.staticRenderFns=n,a._compiled=!0),c&&(a.functional=!0),_&&(a._scopeId="data-v-"+_);var u;if(h?(u=function(l){l=l||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext,!l&&typeof __VUE_SSR_CONTEXT__!="undefined"&&(l=__VUE_SSR_CONTEXT__),s&&s.call(this,l),l&&l._registeredComponents&&l._registeredComponents.add(h)},a._ssrRegister=u):s&&(u=p?function(){s.call(this,(a.functional?this.parent:this).$root.$options.shadowRoot)}:s),u)if(a.functional){a._injectStyles=u;var w=a.render;a.render=function(R,v){return u.call(v),w(R,v)}}else{var f=a.beforeCreate;a.beforeCreate=f?[].concat(f,u):[u]}return{exports:t,options:a}}const k={mixins:[r],props:{fieldsets:Object},data(){return{headline:null,text:null,theme:null}},computed:{currentLanguage(){var t,e,n;return(n=(e=(t=this.$store.state)==null?void 0:t.languages)==null?void 0:e.current)!=null?n:this==null?void 0:this.$language}},async created(){const t=await this.load();this.headline=t.headline,this.theme=t.theme||"none",this.text=this.fieldsets.map(e=>{var n,c,s;return C(g({},e),{label:typeof e.label=="string"?e.label:(s=(c=e.label)==null?void 0:c[(n=this.currentLanguage)==null?void 0:n.code])!=null?s:Object.values(e.label)[0]})})},methods:{async writeToClipboard(t){try{await navigator.clipboard.writeText(t)}catch{console.error(`Failed writing "${t}" to clipboard. The Clipboard API is only available to secure contexts (HTTPS).`)}}}},d={};var S=$(k,i,o,!1,y,null,null,null);function y(t){for(let e in d)this[e]=d[e]}var T=function(){return S.exports}();window.panel.plugin("johannschopplich/punctuation-section",{sections:{punctuation:T}})})();})();