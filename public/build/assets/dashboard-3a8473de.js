var m=Object.defineProperty;var a=(s,r,t)=>r in s?m(s,r,{enumerable:!0,configurable:!0,writable:!0,value:t}):s[r]=t;var e=(s,r,t)=>(a(s,typeof r!="symbol"?r+"":r,t),t);import{a as i}from"./bootstrap-77c64f15.js";import{B as o}from"./npm~rxjs-eeb06f17.js";import"./npm~lodash-9a4afe3d.js";import"./npm~@ckeditor~ckeditor5-build-classic_-27365139.js";import"./npm~laravel-echo-198fd56d.js";import"./npm~pusher-js-a179ff58.js";import"./npm~axios-28bc18a3.js";import"./npm~chart.js-3fed1729.js";import"./npm~moment-fbc5633a.js";import"./npm~vue-4c913503.js";import"./npm~@vue~runtime-dom_-aa3a306b.js";import"./npm~@vue~runtime-core_-ff0546fa.js";import"./npm~@vue~reactivity_-547540a3.js";import"./npm~@vue~shared_-3ce114fe.js";import"./npm~@vue~compiler-dom_-85e36bcf.js";import"./npm~@vue~compiler-core_-be14e06b.js";import"./npm~rx-da74c705.js";import"./currency-ab26e44d.js";import"./npm~numeral-85b533a7.js";import"./npm~currency.js-57f74176.js";import"./npm~@wordpress~hooks_-bd0b7221.js";import"./npm~mathjs-d162420d.js";import"./npm~@babel~runtime_-57eb5ba4.js";import"./npm~decimal.js-d133ee8e.js";import"./npm~complex.js-2f8f27d8.js";import"./npm~fraction.js-704ae1f4.js";import"./npm~typed-function-de33f2d8.js";import"./npm~seedrandom-4e17e537.js";import"./npm~javascript-natural-sort-82d7d2c3.js";import"./npm~escape-latex-6c71499e.js";import"./npm~tslib-8dbab242.js";class p{constructor(){e(this,"_day");e(this,"_bestCustomers");e(this,"_bestCashiers");e(this,"_weeksSummary");e(this,"_recentOrders");e(this,"_reports",{day:i.get("/api/dashboard/day"),bestCustomers:i.get("/api/dashboard/best-customers"),weeksSummary:i.get("/api/dashboard/weeks"),bestCashiers:i.get("/api/dashboard/best-cashiers"),recentOrders:i.get("/api/dashboard/recent-orders")});this._day=new o({}),this._bestCustomers=new o([]),this._weeksSummary=new o({}),this._bestCashiers=new o([]),this._recentOrders=new o([]);for(let r in this._reports)this.loadReport(r)}loadReport(r){return this._reports[r].subscribe(t=>{this[`_${r}`].next(t)})}get day(){return this._day}get bestCustomers(){return this._bestCustomers}get bestCashiers(){return this._bestCashiers}get recentOrders(){return this._recentOrders}get weeksSummary(){return this._weeksSummary}}window.Dashboard=new p;
