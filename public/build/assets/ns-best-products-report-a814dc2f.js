import"./npm~moment-fbc5633a.js";import{c as y,e as f}from"./components-dd2eca3e.js";import{a as v,b as k}from"./bootstrap-77c64f15.js";import{_ as a,n as w}from"./currency-ab26e44d.js";import{_ as D}from"./_plugin-vue_export-helper-c27b6911.js";import{ak as b,ac as i,A as n,D as t,c as m,F,ai as C,z as l,I as _}from"./npm~@vue~runtime-core_-ff0546fa.js";import{U as e,z as p}from"./npm~@vue~shared_-3ce114fe.js";import"./ns-alert-popup-b14f8b15.js";import"./npm~numeral-85b533a7.js";import"./npm~@ckeditor~ckeditor5-build-classic_-27365139.js";import"./npm~currency.js-57f74176.js";import"./npm~@vue~reactivity_-547540a3.js";import"./ns-avatar-image-c7eda85f.js";import"./npm~@dicebear~avatars_-e718895d.js";import"./npm~pure-color-7e91fe3a.js";import"./npm~svgson-b28014ad.js";import"./npm~deep-rename-keys-b2342f4f.js";import"./npm~kind-of-f578382b.js";import"./npm~is-buffer-793dba8c.js";import"./npm~rename-keys-392f1d0e.js";import"./npm~xml-reader-442daf8d.js";import"./npm~eventemitter3-3e95be7d.js";import"./npm~xml-lexer-4446ceee.js";import"./npm~@dicebear~avatars-avataaars-sprites_-c793f8f6.js";import"./npm~@vue~runtime-dom_-aa3a306b.js";import"./ns-prompt-popup-5faf7c75.js";import"./npm~@ckeditor~ckeditor5-vue_-be5de702.js";import"./npm~vue-4c913503.js";import"./npm~@vue~compiler-dom_-85e36bcf.js";import"./npm~@vue~compiler-core_-be14e06b.js";import"./npm~vue2-daterange-picker-ccd86bea.js";import"./npm~vuedraggable-4b4454f2.js";import"./npm~sortablejs-314556ad.js";import"./npm~vue-upload-component-6f0f62ed.js";import"./npm~lodash-9a4afe3d.js";import"./npm~rxjs-eeb06f17.js";import"./npm~tslib-8dbab242.js";import"./npm~laravel-echo-198fd56d.js";import"./npm~pusher-js-a179ff58.js";import"./npm~axios-28bc18a3.js";import"./npm~chart.js-3fed1729.js";import"./npm~rx-da74c705.js";import"./npm~@wordpress~hooks_-bd0b7221.js";import"./npm~mathjs-d162420d.js";import"./npm~@babel~runtime_-57eb5ba4.js";import"./npm~decimal.js-d133ee8e.js";import"./npm~complex.js-2f8f27d8.js";import"./npm~fraction.js-704ae1f4.js";import"./npm~typed-function-de33f2d8.js";import"./npm~seedrandom-4e17e537.js";import"./npm~javascript-natural-sort-82d7d2c3.js";import"./npm~escape-latex-6c71499e.js";const N={name:"ns-best-products-report",mounted(){},components:{nsDatepicker:y,nsDateTimePicker:f},data(){return{ns:window.ns,startDateField:{name:"start_date",type:"datetime",value:ns.date.moment.startOf("day").format()},endDateField:{name:"end_date",type:"datetime",value:ns.date.moment.endOf("day").format()},report:null,sortField:{name:"sort",type:"select",label:a("Sort Results"),value:"using_quantity_asc",options:[{value:"using_quantity_asc",label:a("Using Quantity Ascending")},{value:"using_quantity_desc",label:a("Using Quantity Descending")},{value:"using_sales_asc",label:a("Using Sales Ascending")},{value:"using_sales_desc",label:a("Using Sales Descending")},{value:"using_name_asc",label:a("Using Name Ascending")},{value:"using_name_desc",label:a("Using Name Descending")}]}}},computed:{totalDebit(){return 0},totalCredit(){return 0}},props:["storeLogo","storeName"],methods:{nsCurrency:w,__:a,printSaleReport(){this.$htmlToPaper("best-products-report")},loadReport(){v.post("/api/reports/products-report",{startDate:this.startDateField.value,endDate:this.endDateField.value,sort:this.sortField.value}).subscribe({next:d=>{d.current.products=Object.values(d.current.products),this.report=d},error:d=>{k.error(d.message).subscribe()}})}}},S={id:"report-section",class:"px-4"},U={class:"flex -mx-2"},B={class:"px-2"},P={class:"px-2"},R={class:"px-2"},q={class:"ns-button"},V=t("i",{class:"las la-sync-alt text-xl"},null,-1),j={class:"pl-2"},A={class:"px-2"},L={class:"ns-button"},z=t("i",{class:"las la-print text-xl"},null,-1),O={class:"pl-2"},Q={class:"flex -mx-2"},T={class:"px-2"},E={id:"best-products-report",class:"anim-duration-500 fade-in-entrance"},H={class:"flex w-full"},I={class:"my-4 flex justify-between w-full"},G={class:"text-primary"},J={class:"pb-1 border-b border-dashed"},K={class:"pb-1 border-b border-dashed"},M={class:"pb-1 border-b border-dashed"},W=["src","alt"],X={class:"my-4"},Y={class:"shadow ns-box"},Z={class:"ns-box-body"},$={class:"table ns-table border w-full"},tt={class:""},et={class:"p-2 text-left"},st={width:"150",class:"p-2 text-right"},rt={width:"150",class:"p-2 text-right"},ot={width:"150",class:"p-2 text-right"},it={width:"150",class:"p-2 text-right"},nt={key:0,class:""},at={class:"p-2 border"},lt={class:"p-2 border text-right"},dt={class:"p-2 border text-right"},ct={class:"flex flex-col"},_t={key:0},pt={class:"p-2 border text-right"},mt={class:"flex flex-col"},ut={key:0},ht={key:0},bt=t("i",{class:"las la-arrow-up"},null,-1),xt={key:1},gt=t("i",{class:"las la-arrow-down"},null,-1),yt={key:0,class:""},ft={colspan:"5",class:"border text-center p-2"},vt={key:1},kt={colspan:"5",class:"text-center p-2 border"},wt={key:2,class:"font-semibold"},Dt=t("td",{colspan:"3",class:"p-2 border"},null,-1),Ft={class:"p-2 border text-right"},Ct=t("td",{class:"p-2 border text-right"},null,-1);function Nt(d,c,u,St,o,r){const h=b("ns-date-time-picker"),x=b("ns-field");return i(),n("div",S,[t("div",U,[t("div",B,[m(h,{field:o.startDateField},null,8,["field"])]),t("div",P,[m(h,{field:o.endDateField},null,8,["field"])]),t("div",R,[t("div",q,[t("button",{onClick:c[0]||(c[0]=s=>r.loadReport()),class:"rounded flex justify-between border-box-background text-primary shadow py-1 items-center px-2"},[V,t("span",j,e(r.__("Load")),1)])])]),t("div",A,[t("div",L,[t("button",{onClick:c[1]||(c[1]=s=>r.printSaleReport()),class:"rounded flex justify-between border-box-background text-primary shadow py-1 items-center px-2"},[z,t("span",O,e(r.__("Print")),1)])])])]),t("div",Q,[t("div",T,[m(x,{field:o.sortField},null,8,["field"])])]),t("div",E,[t("div",H,[t("div",I,[t("div",G,[t("ul",null,[t("li",J,e(r.__("Date Range : {date1} - {date2}").replace("{date1}",o.startDateField.value).replace("{date2}",o.endDateField.value)),1),t("li",K,e(r.__("Document : Best Products")),1),t("li",M,e(r.__("By : {user}").replace("{user}",o.ns.user.username)),1)])]),t("div",null,[t("img",{class:"w-24",src:u.storeLogo,alt:u.storeName},null,8,W)])])]),t("div",X,[t("div",Y,[t("div",Z,[t("table",$,[t("thead",tt,[t("tr",null,[t("th",et,e(r.__("Product")),1),t("th",st,e(r.__("Unit")),1),t("th",rt,e(r.__("Quantity")),1),t("th",ot,e(r.__("Value")),1),t("th",it,e(r.__("Progress")),1)])]),o.report?(i(),n("tbody",nt,[(i(!0),n(F,null,C(o.report.current.products,(s,g)=>(i(),n("tr",{key:g,class:p(s.evolution==="progress"?"bg-success-primary":"bg-error-primary")},[t("td",at,e(s.name),1),t("td",lt,e(s.unit_name),1),t("td",dt,[t("div",ct,[t("span",null,[t("span",null,e(s.quantity),1)]),t("span",{class:p([s.evolution==="progress"?"text-success-tertiary":"text-danger-light-tertiary","text-xs"])},[s.evolution==="progress"?(i(),n("span",_t,"+")):l("",!0),_(" "+e(s.quantity-s.old_quantity),1)],2)])]),t("td",pt,[t("div",mt,[t("span",null,e(r.nsCurrency(s.total_price)),1),t("span",{class:p([s.evolution==="progress"?"text-success-tertiary":"text-danger-light-tertiary","text-xs"])},[s.evolution==="progress"?(i(),n("span",ut,"+")):l("",!0),_(" "+e(r.nsCurrency(s.total_price-s.old_total_price)),1)],2)])]),t("td",{class:p([s.evolution==="progress"?"text-success-tertiary":"text-error-light-tertiary","p-2 border text-right"])},[s.evolution==="progress"?(i(),n("span",ht,[_(e(s.difference.toFixed(2))+"% ",1),bt])):l("",!0),s.evolution==="regress"?(i(),n("span",xt,[_(e(s.difference.toFixed(2))+"% ",1),gt])):l("",!0)],2)],2))),128)),o.report.current.products.length===0?(i(),n("tr",yt,[t("td",ft,e(r.__("No results to show.")),1)])):l("",!0)])):l("",!0),o.report?l("",!0):(i(),n("tbody",vt,[t("tr",null,[t("td",kt,e(r.__("Start by choosing a range and loading the report.")),1)])])),o.report?(i(),n("tfoot",wt,[t("tr",null,[Dt,t("td",Ft,e(r.nsCurrency(o.report.current.total_price)),1),Ct])])):l("",!0)])])])])])])}const Ne=D(N,[["render",Nt]]);export{Ne as default};
