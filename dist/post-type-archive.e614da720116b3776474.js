!function(){var t={296:function(t){function e(t,e,n){var o,r,a,i,l;function u(){var c=Date.now()-i;c<e&&c>=0?o=setTimeout(u,e-c):(o=null,n||(l=t.apply(a,r),a=r=null))}null==e&&(e=100);var c=function(){a=this,r=arguments,i=Date.now();var c=n&&!o;return o||(o=setTimeout(u,e)),c&&(l=t.apply(a,r),a=r=null),l};return c.clear=function(){o&&(clearTimeout(o),o=null)},c.flush=function(){o&&(l=t.apply(a,r),a=r=null,clearTimeout(o),o=null)},c}e.debounce=e,t.exports=e}},e={};function n(o){var r=e[o];if(void 0!==r)return r.exports;var a=e[o]={exports:{}};return t[o](a,a.exports,n),a.exports}n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,{a:e}),e},n.d=function(t,e){for(var o in e)n.o(e,o)&&!n.o(t,o)&&Object.defineProperty(t,o,{enumerable:!0,get:e[o]})},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},function(){"use strict";var t=n(296),e=n.n(t),o=function(){return o=Object.assign||function(t){for(var e,n=1,o=arguments.length;n<o;n++)for(var r in e=arguments[n])Object.prototype.hasOwnProperty.call(e,r)&&(t[r]=e[r]);return t},o.apply(this,arguments)},r=function(t,e,n){if(n||2===arguments.length)for(var o,r=0,a=e.length;r<a;r++)!o&&r in e||(o||(o=Array.prototype.slice.call(e,0,r)),o[r]=e[r]);return t.concat(o||Array.prototype.slice.call(e))};function a(t){var e=t.reduce((function(t,e){var n;return o(o({},t),((n={})[e.taxonomy]=r(r([],t[e.taxonomy]||[],!0),[e.term],!1),n))}),{});return Object.keys(e).sort().map((function(t){return"".concat(t,"/").concat(e[t].sort().join("/"))})).join("/")}jQuery((function(t){var n=".filter",r=".load-more",i={count:0,page:1,total:0,totalPages:1,filters:[]};function l(t){Object.assign(i,t),i.$loadMore&&i.$loadMore.toggle(i.totalPages>i.page)}function u(){i.$filters&&l({filters:i.$filters.filter((function(t,e){return e.checked})).map((function(t,e){return{taxonomy:e.name,term:e.value}})).get()})}function c(){var e=t(".box");l({$items:e,count:e.length})}function s(){return f({empty:!0})}function f(e){return void 0===e&&(e={}),r=this,u=void 0,f=function(){var r,u,s,f,p,h,d,m,g,y,v,b,w,$,P,x,k,j,T;return function(t,e){var n,o,r,a,i={label:0,sent:function(){if(1&r[0])throw r[1];return r[1]},trys:[],ops:[]};return a={next:l(0),throw:l(1),return:l(2)},"function"==typeof Symbol&&(a[Symbol.iterator]=function(){return this}),a;function l(a){return function(l){return function(a){if(n)throw new TypeError("Generator is already executing.");for(;i;)try{if(n=1,o&&(r=2&a[0]?o.return:a[0]?o.throw||((r=o.return)&&r.call(o),0):o.next)&&!(r=r.call(o,a[1])).done)return r;switch(o=0,r&&(a=[2&a[0],r.value]),a[0]){case 0:case 1:r=a;break;case 4:return i.label++,{value:a[1],done:!1};case 5:i.label++,o=a[1],a=[0];continue;case 7:a=i.ops.pop(),i.trys.pop();continue;default:if(!((r=(r=i.trys).length>0&&r[r.length-1])||6!==a[0]&&2!==a[0])){i=0;continue}if(3===a[0]&&(!r||a[1]>r[0]&&a[1]<r[3])){i.label=a[1];break}if(6===a[0]&&i.label<r[1]){i.label=r[1],r=a;break}if(r&&i.label<r[2]){i.label=r[2],i.ops.push(a);break}r[2]&&i.ops.pop(),i.trys.pop();continue}a=e.call(t,i)}catch(t){a=[6,t],o=0}finally{n=r=0}if(5&a[0])throw a[1];return{value:a[0]?a[1]:void 0,done:!0}}([a,l])}}}(this,(function(C){switch(C.label){case 0:for(r=o(o({},{empty:!1,placeItems:!0}),e),u=r.placeItems,s=r.empty,f=a(i.filters),p={action:"get-posts",page:String(i.page),type:window.PTH.postType,q:f},h=new URL(window.PTH.ajaxUrl),d=0,m=Object.keys(p);d<m.length;d++)g=m[d],h.searchParams.append(g,p[g]);i.getPostsAbort&&i.getPostsAbort.abort(),l({getPostsAbort:y=new AbortController}),C.label=1;case 1:return C.trys.push([1,3,,4]),v={},[4,window.fetch(h.toString(),{signal:y.signal}).then((function(t){return t.json().then((function(t){return t}))}))];case 2:if(b=C.sent(),i.$filtersContainer&&b.filtersContainer&&(w=t(b.filtersContainer.markup),i.$filtersContainer.replaceWith(w),v.$filtersContainer=w,v.$filters=v.$filtersContainer.find(n)),i.$hero&&b.hero&&($=t(b.hero.markup),i.$hero.replaceWith($),v.$hero=$),i.$heroBottom&&b.heroBottom&&(P=t(b.heroBottom.markup),i.$heroBottom.replaceWith(P),v.$heroBottom=P),i.$cont&&i.$page&&(s&&i.$cont.empty(),u)){for(x=i.$page.clone().empty(),k=0,j=b.items;k<j.length;k++)(T=j[k]).markup&&t(T.markup).appendTo(x);i.$cont.append(x),b.cta&&t(b.cta.markup).appendTo(i.$cont),i.$loadMoreCont&&i.$cont.append(i.$loadMoreCont),c()}return l(o(o({},v),{total:b.total,totalPages:b.totalPages,getPostsAbort:void 0})),[3,4];case 3:return C.sent(),[3,4];case 4:return[2]}}))},new((s=void 0)||(s=Promise))((function(t,e){function n(t){try{a(f.next(t))}catch(t){e(t)}}function o(t){try{a(f.throw(t))}catch(t){e(t)}}function a(e){var r;e.done?t(e.value):(r=e.value,r instanceof s?r:new s((function(t){t(r)}))).then(n,o)}a((f=f.apply(r,u||[])).next())}));var r,u,s,f}l({$cont:t(".posts"),$hero:t(".hero"),$heroBottom:t(".hero-bottom"),$page:t(".page"),$filtersContainer:t(".filters-container"),$filters:t(n),$loadMoreCont:t(r),$loadMore:t(".load-more a")}),u(),c(),t(document).on("click",r,(function(t){t.preventDefault(),l({page:i.page+1}),f()})),t(document).on("change",n,(function(t){var n=t.target,o=n.name,r=n.value;if(o&&r){var c;i.getPostsDebounce&&i.getPostsDebounce.clear(),c=i.filters,window.history.replaceState({filters:c},""),u();var f=e()(s,1);f(),l({page:1,getPostsDebounce:f}),function(){var t=[window.PTH.homeUrl,window.PTH.postTypeSlug],e=a(i.filters);e&&t.push(e);var n=t.filter(Boolean).join("/")+"/";window.history.pushState(null,"",n)}()}})),window.addEventListener("popstate",(function(t){l(t.state),s()})),f({placeItems:!1})}))}()}();