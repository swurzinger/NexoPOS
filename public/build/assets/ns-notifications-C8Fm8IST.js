import{G as _,b as d,d as p}from"./bootstrap-pPUfSozf.js";import{_ as f,d as b}from"./currency-Dtag6qPd.js";import{n as v}from"./ns-prompt-popup-ChEVvMVa.js";import{h as x}from"./components-Dxf8jObT.js";import{_ as k}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{r as g,o as c,c as r,a as e,t as a,e as u,n as y,F as N,b as w,f as C}from"./runtime-core.esm-bundler-DYLTeUDs.js";import"./chart-D2s_fKm5.js";import"./ns-alert-popup-B3IijhNU.js";import"./ns-avatar-image-CE0FTvGu.js";import"./index.es-ua9LYOjR.js";const E={name:"ns-notifications",data(){return{notifications:[],visible:!1,socketEnabled:!1,interval:null}},components:{nsCloseButton:x},mounted(){document.addEventListener("click",this.checkClickedItem),typeof Echo>"u"?this.interval=setInterval(()=>{this.loadNotifications()},15e3):(this.interval=setInterval(()=>{this.socketEnabled=Echo.connector.pusher.connection.state==="connected"},1e3),Echo.private(`App.User.${ns.user.attributes.user_id}`).listen("NotificationUpdatedEvent",t=>{this.pushNotificationIfNew(t.notification)}).listen("NotificationCreatedEvent",t=>{this.pushNotificationIfNew(t.notification)}).listen("NotificationDeletedEvent",t=>{this.deleteNotificationIfExists(t.notification)})),this.loadNotifications()},unmounted(){clearInterval(this.interval)},methods:{__:f,timespan:_,nsNumberAbbreviate:b,pushNotificationIfNew(t){this.notifications.filter(s=>s.id===t.id).length>0||this.notifications.unshift(t)},deleteNotificationIfExists(t){const i=this.notifications.filter(s=>s.id===t.id);if(i.length>0){const s=this.notifications.indexOf(i[0]);this.notifications.splice(s,1)}},deleteAll(){Popup.show(v,{title:f("Confirm Your Action"),message:f("Would you like to clear all the notifications ?"),onAction:t=>{t&&d.delete("/api/notifications/all").subscribe(i=>{p.success(i.message).subscribe()})}})},checkClickedItem(t){let i;document.getElementById("notification-center")?i=document.getElementById("notification-center").contains(t.srcElement):i=!1;const s=document.getElementById("notification-button").contains(t.srcElement);!i&&!s&&this.visible&&(this.visible=!1)},loadNotifications(){d.get("/api/notifications").subscribe(t=>{this.notifications=t})},triggerLink(t){if(t.url!=="url")return window.open(t.url,"_blank")},closeNotice(t,i){d.delete(`/api/notifications/${i.id}`).subscribe(s=>{this.socketEnabled||this.loadNotifications()}),t.stopPropagation()}}},I={id:"notificaton-wrapper"},B={key:0,class:"relative float-right"},A={class:"absolute -ml-6 -mt-8"},j={class:"bg-info-tertiary text-white w-8 h-8 rounded-full text-xs flex items-center justify-center"},L=e("i",{class:"las la-bell"},null,-1),P={key:0,class:"h-0 w-0",id:"notification-center"},V={class:"absolute left-0 top-0 sm:relative w-screen zoom-out-entrance anim-duration-300 h-5/7-screen sm:w-64 sm:h-108 flex flex-row-reverse"},z={class:"z-50 sm:rounded-lg shadow-lg h-full w-full md:mt-2 overflow-y-hidden flex flex-col"},D=e("h3",{class:"font-semibold hover:text-info-primary"},"Close",-1),F=[D],S={class:"overflow-y-auto flex flex-col flex-auto"},U=["onClick"],G={class:"flex items-center justify-between"},H={class:"font-semibold"},O={class:"py-1 text-sm"},W={class:"flex justify-end"},Y={class:"text-xs date"},q={key:0,class:"h-full w-full flex items-center justify-center"},J={class:"flex flex-col items-center"},K=e("i",{class:"las la-laugh-wink text-5xl text-primary"},null,-1),M={class:"text-secondary text-sm"},Q={class:"cursor-pointer clear-all"};function R(t,i,s,T,o,l){const m=g("ns-close-button");return c(),r("div",I,[e("div",{id:"notification-button",onClick:i[0]||(i[0]=n=>o.visible=!o.visible),class:y([o.visible?"panel-visible border-0 shadow-lg":"border panel-hidden","hover:shadow-lg hover:border-opacity-0 rounded-full h-12 w-12 cursor-pointer font-bold text-2xl justify-center items-center flex"])},[o.notifications.length>0?(c(),r("div",B,[e("div",A,[e("div",j,a(l.nsNumberAbbreviate(o.notifications.length,"abbreviate")),1)])])):u("",!0),L],2),o.visible?(c(),r("div",P,[e("div",V,[e("div",z,[e("div",{onClick:i[1]||(i[1]=n=>o.visible=!1),class:"sm:hidden p-2 cursor-pointer flex items-center justify-center border-b border-gray-200"},F),e("div",S,[(c(!0),r(N,null,w(o.notifications,n=>(c(),r("div",{key:n.id,class:"notification-card notice border-b"},[e("div",{class:"p-2 cursor-pointer",onClick:h=>l.triggerLink(n)},[e("div",G,[e("h1",H,a(n.title),1),C(m,{onClick:h=>l.closeNotice(h,n)},null,8,["onClick"])]),e("p",O,a(n.description),1),e("div",W,[e("span",Y,a(l.timespan(n.updated_at)),1)])],8,U)]))),128)),o.notifications.length===0?(c(),r("div",q,[e("div",J,[K,e("p",M,a(l.__("Nothing to care about !")),1)])])):u("",!0)]),e("div",Q,[e("h3",{onClick:i[2]||(i[2]=n=>l.deleteAll()),class:"text-sm p-2 flex items-center justify-center w-full font-semibold"},a(l.__("Clear All")),1)])])])])):u("",!0)])}const ct=k(E,[["render",R]]);export{ct as default};
