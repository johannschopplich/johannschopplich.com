(function(){"use strict";const m=/\/$|\/\?|\/#/;function $(t="",e){return e?m.test(t):t.endsWith("/")}function b(t="",e){if(!e)return t.endsWith("/")?t:t+"/";if($(t,!0))return t||"/";let n=t,a="";const o=t.indexOf("#");if(o>=0&&(n=t.slice(0,o),a=t.slice(o),!n))return a;const[l,...i]=n.split("?");return l+"/"+(i.length>0?`?${i.join("?")}`:"")+a}function y(t=""){return t.startsWith("/")}function C(t=""){return y(t)?t:"/"+t}function w(t){return t&&t!=="/"}const x=/^\.?\//;function h(t,...e){let n=t||"";for(const a of e.filter(o=>w(o)))if(n){const o=a.replace(x,"");n=b(n)+o}else n=a;return n}const d={props:{blueprint:String,lock:[Boolean,Object],help:String,name:String,parent:String,timestamp:Number},methods:{load(){return this.$api.get(`${this.parent}/sections/${this.name}`)}}},p={computed:{locales(){return this.$languages.reduce((t,e)=>[...t,e.code],[])}},methods:{getNonLocalizedPath(t){const n=new URL(t).pathname.split("/").filter(Boolean);return this.locales.includes(n[0])&&n.shift(),`/${n.join("/")}`}}};function g(t,e,n,a,o,l,i,r){var s=typeof t=="function"?t.options:t;e&&(s.render=e,s.staticRenderFns=n,s._compiled=!0),a&&(s.functional=!0),l&&(s._scopeId="data-v-"+l);var c;if(i?(c=function(u){u=u||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext,!u&&typeof __VUE_SSR_CONTEXT__<"u"&&(u=__VUE_SSR_CONTEXT__),o&&o.call(this,u),u&&u._registeredComponents&&u._registeredComponents.add(i)},s._ssrRegister=c):o&&(c=r?function(){o.call(this,(s.functional?this.parent:this).$root.$options.shadowRoot)}:o),c)if(s.functional){s._injectStyles=c;var f=s.render;s.render=function(A,v){return c.call(v),f(A,v)}}else{var _=s.beforeCreate;s.beforeCreate=_?[].concat(_,c):[c]}return{exports:t,options:s}}const k={mixins:[d,p],data(){return{label:void 0,config:{},site:{},url:""}},computed:{content(){return this.$store.getters["content/values"]()},path(){if(!this.url)return"";let t=this.getNonLocalizedPath(this.url);return this.$language.default||(t=h(this.$language.code,t)),C(t)}},watch:{"$language.code":{async handler(){const{url:t}=await this.$api.get(this.$view.path);this.url=t},immediate:!0}},async created(){var e,n;const t=await this.load();this.label=typeof t.label=="string"?t.label:(n=t.label)==null?void 0:n[(e=this.$language)==null?void 0:e.code],this.config=t.config,this.site=t.site},methods:{joinURL:h}};var L=function(){var e=this,n=e._self._c;return n("k-section",{attrs:{headline:e.label}},[n("div",{staticClass:"overflow-hidden rounded-[var(--input-rounded)] bg-[var(--input-color-back)] p-4"},[n("div",{staticClass:"flex items-center gap-3 mb-2"},[n("figure",{staticClass:"inline-flex aspect-square h-[26px] w-[26px] items-center justify-center rounded-full border border-solid border-[#ecedef] bg-[#f1f3f4]"},[n("img",{staticClass:"block h-[18px] w-[18px]",attrs:{src:e.config.faviconUrl||"/assets/favicon.svg",alt:""}})]),n("div",{staticClass:"flex flex-col"},[n("span",{staticClass:"text-sm text-[#4d5156]"},[e._v(e._s(e.site.title))]),n("span",{staticClass:"line-clamp-1 text-xs text-[#4d5156]"},[e._v(e._s(e.joinURL(e.config.baseUrl||e.site.url,e.path)))])])]),n("h3",{staticClass:"mb-1 line-clamp-1 text-xl text-[#1a0dab]"},[e._v(" "+e._s(e.content.customTitle||`${e.$view.title} – ${e.site.title}`)+" ")]),n("p",{staticClass:"line-clamp-2 text-sm text-[#4d5156]"},[e._v(" "+e._s(e.content.description)+" ")])])])},S=[],T=g(k,L,S,!1,null,null,null,null);const O=T.exports,R=5,N={mixins:[d,p,{methods:{async recursiveTranslateContent(t,{targetLanguage:e="en-US",translatableBlocks:n=[]}){const a=[],o=async(i,r)=>{for(let s=0;s<i.length;s+=r){const c=i.slice(s,s+r);await Promise.all(c.map(f=>f()))}},l=i=>async()=>{const r=await this.$api.post("deepl/translate",{text:t[i],targetLanguage:e});t[i]=r.result.text};for(const i in t)if(typeof t[i]=="string")a.push(l(i));else if(Array.isArray(t[i])){for(const r of t[i])if(!(typeof r.content!="object"||r.content===null||!r.id||r.isHidden)&&Object.keys(n).includes(r.type))for(const s of Object.keys(r.content))n[r.type].includes(s)&&a.push(async()=>{const c=await this.$api.post("deepl/translate",{text:r.content[s],targetLanguage:e});r.content[s]=c.result.text})}else typeof t[i]=="object"&&t[i]!==null&&(t[i]=await this.recursiveTranslateContent(t[i],{targetLanguage:e,translatableBlocks:n}));return await o(a,R),t}}}],data(){return{label:void 0,config:{},defaultLanguage:this.$languages.find(t=>t.default).code,defaultContent:{}}},computed:{currentContent(){return this.$store.getters["content/values"]()},translatableContent(){return Object.fromEntries(Object.entries(this.currentContent).filter(([t])=>this.config.translatableFields.includes(t)))},syncableContent(){return Object.fromEntries(Object.entries(this.defaultContent).filter(([t])=>this.config.syncableFields.includes(t)))}},async created(){var n,a;const t=await this.load();this.label=typeof t.label=="string"?t.label:(a=t.label)==null?void 0:a[(n=this.$language)==null?void 0:n.code],this.config=t.config;const e=async()=>{this.defaultContent=await this.getDefaultContent()};this.$events.$on("model.update",e),e()},methods:{sync(){for(const[t,e]of Object.entries(this.syncableContent))this.$store.dispatch("content/update",[t,e]);this.$panel.notification.success(`${this.$t("confirm")}!`)},async translate(t){this.$panel.view.isLoading=!0;const e=JSON.parse(JSON.stringify(this.translatableContent));try{await this.recursiveTranslateContent(e,{targetLanguage:t.code,translatableBlocks:this.config.translatableBlocks})}catch(n){console.error(n),this.$panel.notification.success(this.$t("error"));return}for(const[n,a]of Object.entries(e))this.$store.dispatch("content/update",[n,a]);this.$panel.view.isLoading=!1,this.$panel.notification.success(`${this.$t("confirm")}!`)},async getDefaultContent(){const{content:t}=await this.$api.get(this.$view.path,{language:this.defaultLanguage});return t}}};var U=function(){var a,o;var e=this,n=e._self._c;return n("k-section",{attrs:{headline:e.label}},[(o=(a=e.config)==null?void 0:a.DeepL)!=null&&o.apiKey?n("k-box",{attrs:{theme:"none"}},[n("k-button-group",{attrs:{layout:"collapsed"}},[n("k-button",{attrs:{disabled:e.$language.default,size:"sm",variant:"filled"},on:{click:function(l){return e.sync()}}},[e._v(" "+e._s(e.$t("johannschopplich.seo-helpers.sync"))+" ")]),e._l(e.$languages.filter(l=>!l.default),function(l){return n("k-button",{key:l.code,attrs:{disabled:e.$language.default,size:"sm",variant:"filled",theme:"notice"},on:{click:function(i){return e.translate(l)}}},[e._v(" "+e._s(e.$t("johannschopplich.seo-helpers.translate",{language:l.name}))+" ")])})],2)],1):n("k-box",{attrs:{theme:"none"}},[n("k-text",[e._v(" You need to set the "),n("code",[e._v("johannschopplich.seo-helpers.DeepL.apiKey")]),e._v(" option in your "),n("code",[e._v("config.php")]),e._v(". ")])],1)],1)},E=[],j=g(N,U,E,!1,null,null,null,null);const I=j.exports,F="";window.panel.plugin("johannschopplich/website",{sections:{"seo-preview":O,"content-translation":I}})})();
