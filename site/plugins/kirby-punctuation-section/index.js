(()=>{(function(){"use strict";var d={props:{blueprint:String,lock:[Boolean,Object],help:String,name:String,parent:String,timestamp:Number},methods:{load(){return this.$api.get(`${this.parent}/sections/${this.name}`)}}},p=function(){var t=this,n=t.$createElement,o=t._self._c||n;return o("section",{staticClass:"k-info-section"},[o("k-headline",{staticClass:"k-info-section-headline"},[t._v(" "+t._s(t.headline)+" ")]),o("k-box",{attrs:{theme:t.theme},on:{click:t.handleClick}},[o("k-text",{domProps:{innerHTML:t._s(t.text)}})],1)],1)},f=[],T="";function v(t,n,o,a,r,l,u,g){var e=typeof t=="function"?t.options:t;n&&(e.render=n,e.staticRenderFns=o,e._compiled=!0),a&&(e.functional=!0),l&&(e._scopeId="data-v-"+l);var s;if(u?(s=function(i){i=i||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext,!i&&typeof __VUE_SSR_CONTEXT__!="undefined"&&(i=__VUE_SSR_CONTEXT__),r&&r.call(this,i),i&&i._registeredComponents&&i._registeredComponents.add(u)},e._ssrRegister=s):r&&(s=g?function(){r.call(this,(e.functional?this.parent:this).$root.$options.shadowRoot)}:r),s)if(e.functional){e._injectStyles=s;var k=e.render;e.render=function(S,_){return s.call(_),k(S,_)}}else{var h=e.beforeCreate;e.beforeCreate=h?[].concat(h,s):[s]}return{exports:t,options:e}}const m={mixins:[d],props:{fieldsets:Object},data(){return{headline:null,text:null,theme:null}},async created(){const t=await this.load();this.headline=t.headline,this.theme=t.theme||"none",this.text=this.fieldsets.map(({label:n,chars:o})=>{var a;return`${(a=n==null?void 0:n[this.$language.code])!=null?a:Object.values(n)[0]}: ${o.map(r=>`<button class="k-button-character" type="button">${r}</button>`).join(" ")}`}).join("<br>")},methods:{async handleClick({target:t}){if(!!t.matches("button"))try{await navigator.clipboard.writeText(t.textContent)}catch{console.error(`Failed writing "${t.textContent}" to clipboard. The Clipboard API is only available to secure contexts (HTTPS).`)}}}},c={};var C=v(m,p,f,!1,$,null,null,null);function $(t){for(let n in c)this[n]=c[n]}var b=function(){return C.exports}();window.panel.plugin("johannschopplich/punctuation-section",{sections:{punctuation:b}})})();})();
