var m={exports:{}};(function(x){var w=Object.prototype.hasOwnProperty,h="~";function v(){}Object.create&&(v.prototype=Object.create(null),new v().__proto__||(h=!1));function y(c,s,t){this.fn=c,this.context=s,this.once=t||!1}function f(){this._events=new v,this._eventsCount=0}f.prototype.eventNames=function(){var s=[],t,r;if(this._eventsCount===0)return s;for(r in t=this._events)w.call(t,r)&&s.push(h?r.slice(1):r);return Object.getOwnPropertySymbols?s.concat(Object.getOwnPropertySymbols(t)):s},f.prototype.listeners=function(s,t){var r=h?h+s:s,i=this._events[r];if(t)return!!i;if(!i)return[];if(i.fn)return[i.fn];for(var n=0,l=i.length,u=new Array(l);n<l;n++)u[n]=i[n].fn;return u},f.prototype.emit=function(s,t,r,i,n,l){var u=h?h+s:s;if(!this._events[u])return!1;var e=this._events[u],a=arguments.length,p,o;if(e.fn){switch(e.once&&this.removeListener(s,e.fn,void 0,!0),a){case 1:return e.fn.call(e.context),!0;case 2:return e.fn.call(e.context,t),!0;case 3:return e.fn.call(e.context,t,r),!0;case 4:return e.fn.call(e.context,t,r,i),!0;case 5:return e.fn.call(e.context,t,r,i,n),!0;case 6:return e.fn.call(e.context,t,r,i,n,l),!0}for(o=1,p=new Array(a-1);o<a;o++)p[o-1]=arguments[o];e.fn.apply(e.context,p)}else{var b=e.length,_;for(o=0;o<b;o++)switch(e[o].once&&this.removeListener(s,e[o].fn,void 0,!0),a){case 1:e[o].fn.call(e[o].context);break;case 2:e[o].fn.call(e[o].context,t);break;case 3:e[o].fn.call(e[o].context,t,r);break;case 4:e[o].fn.call(e[o].context,t,r,i);break;default:if(!p)for(_=1,p=new Array(a-1);_<a;_++)p[_-1]=arguments[_];e[o].fn.apply(e[o].context,p)}}return!0},f.prototype.on=function(s,t,r){var i=new y(t,r||this),n=h?h+s:s;return this._events[n]?this._events[n].fn?this._events[n]=[this._events[n],i]:this._events[n].push(i):(this._events[n]=i,this._eventsCount++),this},f.prototype.once=function(s,t,r){var i=new y(t,r||this,!0),n=h?h+s:s;return this._events[n]?this._events[n].fn?this._events[n]=[this._events[n],i]:this._events[n].push(i):(this._events[n]=i,this._eventsCount++),this},f.prototype.removeListener=function(s,t,r,i){var n=h?h+s:s;if(!this._events[n])return this;if(!t)return--this._eventsCount===0?this._events=new v:delete this._events[n],this;var l=this._events[n];if(l.fn)l.fn===t&&(!i||l.once)&&(!r||l.context===r)&&(--this._eventsCount===0?this._events=new v:delete this._events[n]);else{for(var u=0,e=[],a=l.length;u<a;u++)(l[u].fn!==t||i&&!l[u].once||r&&l[u].context!==r)&&e.push(l[u]);e.length?this._events[n]=e.length===1?e[0]:e:--this._eventsCount===0?this._events=new v:delete this._events[n]}return this},f.prototype.removeAllListeners=function(s){var t;return s?(t=h?h+s:s,this._events[t]&&(--this._eventsCount===0?this._events=new v:delete this._events[t])):(this._events=new v,this._eventsCount=0),this},f.prototype.off=f.prototype.removeListener,f.prototype.addListener=f.prototype.on,f.prototype.setMaxListeners=function(){return this},f.prefixed=h,f.EventEmitter=f,x.exports=f})(m);var d=m.exports;export{d as e};
