import{F as y,a as h,b as g}from"./bootstrap-ece284e4.js";import{_ as i}from"./currency-ab26e44d.js";import{_ as k}from"./_plugin-vue_export-helper-c27b6911.js";import{ak as p,ac as s,A as n,y as d,z as c,D as r,F as _,ai as v,c as C,aE as F,I as x}from"./npm~@vue~runtime-core_-ff0546fa.js";import{U as q}from"./npm~@vue~shared_-3ce114fe.js";import"./npm~lodash-9a4afe3d.js";import"./npm~@ckeditor~ckeditor5-build-classic_-27365139.js";import"./npm~laravel-echo-198fd56d.js";import"./npm~pusher-js-a179ff58.js";import"./npm~axios-28bc18a3.js";import"./npm~chart.js-3fed1729.js";import"./npm~rxjs-eeb06f17.js";import"./npm~tslib-8dbab242.js";import"./npm~moment-fbc5633a.js";import"./npm~vue-4c913503.js";import"./npm~@vue~runtime-dom_-aa3a306b.js";import"./npm~@vue~reactivity_-547540a3.js";import"./npm~@vue~compiler-dom_-85e36bcf.js";import"./npm~@vue~compiler-core_-be14e06b.js";import"./npm~rx-da74c705.js";import"./npm~@wordpress~hooks_-bd0b7221.js";import"./npm~mathjs-d162420d.js";import"./npm~@babel~runtime_-57eb5ba4.js";import"./npm~decimal.js-d133ee8e.js";import"./npm~complex.js-2f8f27d8.js";import"./npm~fraction.js-704ae1f4.js";import"./npm~typed-function-de33f2d8.js";import"./npm~seedrandom-4e17e537.js";import"./npm~javascript-natural-sort-82d7d2c3.js";import"./npm~escape-latex-6c71499e.js";import"./npm~numeral-85b533a7.js";import"./npm~currency.js-57f74176.js";const B={data:()=>({form:new y,fields:[],processing:!1,steps:[],failure:0,maxFailure:2,__:i}),methods:{validate(){},verifyDBConnectivity(){},saveConfiguration(e){this.form.disableFields(this.fields);const t=this.form.getValue(this.fields);return t.language=ns.language,this.processing=!0,h.post("/api/setup/configuration",t).subscribe({next:o=>{document.location="/sign-in"},error:o=>{this.processing=!1,this.form.enableFields(this.fields),this.form.triggerFieldsErrors(this.fields,o.data),g.error(o.message,"OK").subscribe()}})},divide(e){var t=Math.floor(e.length/2),o=e.slice(0,t),u=e.slice(t);return[o,u]},checkDatabase(){h.get("/api/setup/database").subscribe({next:e=>{this.fields=this.form.createFields([{label:i("Application"),description:i("That is the application name."),name:"ns_store_name",validation:"required"},{label:i("Username"),description:i("Provide the administrator username."),name:"admin_username",validation:"required"},{label:i("Email"),description:i("Provide the administrator email."),name:"admin_email",validation:"required"},{label:i("Password"),type:"password",description:i("What should be the password required for authentication."),name:"password",validation:"required"},{label:i("Confirm Password"),type:"password",description:i("Should be the same as the password above."),name:"confirm_password",validation:"required"}])},error:e=>{nsRouter.push("/database"),g.error("You need to define database settings","OKAY",{duration:3e3}).subscribe()}})}},mounted(){this.checkDatabase()}},V={key:1,class:"bg-white rounded shadow my-2"},D={class:"welcome-box border-b border-gray-300 p-3 text-gray-700"},N=["innerHTML"],P={class:"flex -mx-2"},S={class:"px-2 w-full md:w-1/2"},E={class:"px-2 w-full md:w-1/2"},O={class:"bg-gray-200 p-3 flex justify-between items-center"};function T(e,t,o,u,z,l){const f=p("ns-spinner"),b=p("ns-field"),w=p("ns-button");return s(),n("div",null,[e.fields.length===0?(s(),d(f,{key:0,size:"12",border:"4",animation:"fast"})):c("",!0),e.fields.length>0?(s(),n("div",V,[r("div",D,[r("div",{class:"border-b pb-3 mb-3",innerHTML:e.__("<strong>NexoPOS</strong> is now able to connect to the database. Start by creating the administrator account and giving a name to your installation. Once installed, this page will no longer be accessible.")},null,8,N),r("div",P,[r("div",S,[(s(!0),n(_,null,v(l.divide(e.fields)[0],(a,m)=>(s(),d(b,{key:m,field:a},null,8,["field"]))),128))]),r("div",E,[(s(!0),n(_,null,v(l.divide(e.fields)[1],(a,m)=>(s(),d(b,{key:m,field:a},null,8,["field"]))),128))])])]),r("div",O,[r("div",null,[e.processing?(s(),d(f,{key:0,size:"8",border:"4"})):c("",!0)]),C(w,{disabled:e.processing,onClick:t[0]||(t[0]=a=>l.saveConfiguration()),type:"info"},{default:F(()=>[x(q(e.__("Install")),1)]),_:1},8,["disabled"])])])):c("",!0)])}const be=k(B,[["render",T]]);export{be as default};
