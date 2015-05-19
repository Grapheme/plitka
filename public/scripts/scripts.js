"use strict";angular.module("plitkaApp",["ngAnimate","ngCookies","ngResource","ngRoute","ngSanitize","ngTouch","seo"]).config(["$httpProvider","$locationProvider",function(a,b){a.defaults.cache=!0,b.hashPrefix("!")}]).run(["$http",function(a){a.get("http://plitka.dev.grapheme.ru/application/get").success(function(){1==window.__loaded?$(".loader").fadeOut(400):window.__loaded=!0})}]).config(["$routeProvider",function(a){a.when("/",{templateUrl:"views/main.html",controller:"MainCtrl",controllerAs:"MCtrl"}).when("/about",{templateUrl:"views/about.html",controller:"AboutCtrl",controllerAs:"AbCtrl"}).when("/contacts",{templateUrl:"views/contacts.html",controller:"ContactsCtrl",controllerAs:"ContCtrl"}).when("/articles",{templateUrl:"views/articles.html",controller:"ArticlesCtrl",controllerAs:"ArtCtrl"}).when("/catalog",{templateUrl:"views/catalog.html",controller:"CatalogCtrl",controllerAs:"CatCtrl"}).when("/catalog/:id",{templateUrl:"views/catalog.html",controller:"CatalogCtrl",controllerAs:"CatCtrl"}).when("/projects",{templateUrl:"views/projects.html",controller:"ProjectsCtrl",controllerAs:"ProjCtrl"}).when("/article/:id",{templateUrl:"views/separticle.html",controller:"SeparticleCtrl",controllerAs:"SepCtrl"}).when("/404",{templateUrl:"views/404.html"}).when("/search-results",{templateUrl:"views/search-results.html",controller:"SearchResultsCtrl",controllerAs:"SResultsCtrl"}).when("/catalog-item/:id",{templateUrl:"views/catalog-item.html",controller:"CatalogItemCtrl",controllerAs:"CatItemCtrl"}).when("/collection/:id",{templateUrl:"views/collection.html",controller:"CollectionCtrl",controllerAs:"CollCtrl"}).when("/pages/:id",{templateUrl:"views/page.html",controller:"PagesCtrl",controllerAs:"PageCtrl"}).otherwise({redirectTo:"/404"})}]),angular.module("plitkaApp").controller("MainCtrl",["$http","$rootScope","$scope",function(a,b,c){var d=this;b.route="",a.get("http://plitka.dev.grapheme.ru/application/get").success(function(a){d.data=a,d.articles=d.data.articles,d.photos=d.data.photos,d.galleries=d.data.galleries,d.factories=d.data.factory,d.countries=d.data.countries,d.collectionPrices=d.data.collections_prices,d.articlesArr=$.map(d.articles,function(a){return[a]}),d.promo=d.data.promo,d.collections=d.data.collections,d.recCollections=[];for(var e in d.collections)1==d.collections[e].show_on_mainpage&&d.recCollections.push(d.collections[e]);b.h1=d.data.pages.index.seo.h1,b.title=d.data.pages.index.seo.title,b.description=d.data.pages.index.seo.description,b.keywords=d.data.pages.index.seo.keywords,setTimeout(function(){jQuery(".fotorama").fotorama({width:"100%",height:"400",nav:!1,arrows:"always",autoplay:3e3,loop:!0})},100),c.htmlReady()})}]),angular.module("plitkaApp").controller("AboutCtrl",["$http","$scope","$rootScope",function(a,b,c){var d=this;c.route="about",a.get("http://plitka.dev.grapheme.ru/application/get").success(function(a){d.data=a,d.photos=d.data.photos,d.gallery=d.data.galleries[69],d.slides=d.gallery.photos,setTimeout(function(){jQuery(".fotorama").fotorama({width:"100%",height:"400",nav:!1,arrows:"always",autoplay:3e3,loop:!0})},100),c.h1=d.data.pages.about.seo.h1,c.title=d.data.pages.about.seo.title,c.description=d.data.pages.about.seo.description,c.keywords=d.data.pages.about.seo.keywords,b.htmlReady()})}]),angular.module("plitkaApp").controller("ContactsCtrl",["$http","$scope","$rootScope",function(a,b,c){var d=this;c.route="contacts",this.mapTo="salon",this.mapCoords={salon:{x:47.244747,y:39.723161,zoom:15},store:{x:47.249539,y:39.621147,zoom:14}},this.setMap=function(a){this.mapTo=a,this.initialize(this.mapCoords[this.mapTo])},this.checkMap=function(a){return this.mapTo===a},this.initialize=function(a){{var b={zoom:a.zoom,zoomControl:!1,draggable:!1,scrollwheel:!1,center:new google.maps.LatLng(a.x,a.y),mapTypeId:google.maps.MapTypeId.ROADMAP},c=new google.maps.Map(document.getElementById("map_canvas"),b);new google.maps.Marker({position:b.center,map:c})}},this.setMap("salon"),this.formData={},this.sendForm=function(a){$.ajax({type:"POST",url:"http://plitka.dev.grapheme.ru/ajax/feedback",data:{name:a.name,email:a.email,text:a.text},dataType:"json"}).done(function(){$(".feedback-form").addClass("sended").html("<p><strong>Спасибо!</strong></p><p>Мы получили ваше сообщение и постараемся<br>как можно быстрее ответить</p>")}).fail(function(){}).always(function(){})},a.get("http://plitka.dev.grapheme.ru/application/get").success(function(a){d.data=a,d.contactsDataBlocks=d.data.pages.contacts.blocks,d.header=d.data.pages.contacts.name,d.phones=d.contactsDataBlocks.phones.content,d.email=d.contactsDataBlocks.email.content,d.workTime=d.contactsDataBlocks["work-time-clearfix"].content,d.contactsColumn=d.contactsDataBlocks["contacts-column"].content,c.h1=d.data.pages.contacts.seo.h1,c.title=d.data.pages.contacts.seo.title,c.description=d.data.pages.contacts.seo.description,c.keywords=d.data.pages.contacts.seo.keywords,b.htmlReady()})}]),angular.module("plitkaApp").controller("ArticlesCtrl",["$http","$rootScope","$scope",function(a,b,c){var d=this;b.route="articles",a.get("http://plitka.dev.grapheme.ru/application/get").success(function(a){d.data=a;var e=[],f={};$.each(d.data.articles,function(a,b){b.this_id=a,e.push(b)});for(var g=e.length-1;g>=0;g--){var h=e[g];f[h.this_id]=h}console.log(d.data.articles),console.log(f),d.articles=e,d.photos=d.data.photos,b.h1=d.data.pages.articles.seo.h1,b.title=d.data.pages.articles.seo.title,b.description=d.data.pages.articles.seo.description,b.keywords=d.data.pages.articles.seo.keywords,c.htmlReady()})}]),angular.module("plitkaApp").controller("PagesCtrl",["$http","$routeParams","$scope","$rootScope",function(a,b,c,d){a.get("http://plitka.dev.grapheme.ru/application/get").success(function(a){var e=b.id;self.data=a,self.page=self.data.pages[e],self.blocks=[],$.each(self.page.blocks,function(a,b){self.blocks.push("<p>"+b.content+"</p>")}),d.blocks=self.blocks.join(""),d.h1=self.data.pages[e].seo.h1,d.title=self.data.pages[e].seo.title,d.description=self.data.pages[e].seo.description,d.keywords=self.data.pages[e].seo.keywords,c.htmlReady()})}]),angular.module("plitkaApp").controller("CatalogCtrl",["$http","$routeParams","$cookies","$scope","$rootScope",function(a,b,c,d,e){var f=this;e.route="catalog",a.get("http://plitka.dev.grapheme.ru/application/get").success(function(a){if(f.data=a,f.catalogHeader="Каталог",setTimeout(function(){if(c.countryFilter)for(var a=c.countryFilter.split(","),b=0;b<a.length;b++)$('[data-country="'+a[b]+'"]').trigger("click");if(c.factoryFilter)for(var d=c.factoryFilter.split(","),b=0;b<d.length;b++)$('[data-factory="'+d[b]+'"]').trigger("click");if(c.colorFilter)for(var e=c.colorFilter.split(","),b=0;b<e.length;b++)$('[data-color="'+e[b]+'"]').trigger("click");if(c.placeFilter)for(var f=c.placeFilter.split(","),b=0;b<f.length;b++)$('[data-place="'+f[b]+'"]').trigger("click");if(c.formatFilter)for(var g=c.formatFilter.split(","),b=0;b<g.length;b++)$('[data-format="'+g[b]+'"]').trigger("click");if(c.surfaceTypesFilter)for(var h=c.surfaceTypesFilter.split(","),b=0;b<h.length;b++)$('[data-surface-type="'+h[b]+'"]').trigger("click");if(c.surfaceFilter)for(var i=c.surfaceFilter.split(","),b=0;b<i.length;b++)$('[data-surface="'+i[b]+'"]').trigger("click")},1e3),f.productType=f.data.product_type,f.collections=f.data.collections,f.materialsCollections=f.data.product_type_others_collections,f.products=f.data.products,f.countries=f.data.countries,f.factories=f.data.factory,f.photos=f.data.photos,f.galleries=f.data.galleries,f.colors=f.data.colors,f.collectionColors=f.data.collections_colors,f.collectionPrices=f.data.collections_prices,f.surfaceTypes=f.data.surface_type,f.collectionSurfaces=f.data.collections_surface_types,f.places=f.data.scope,f.collectionPlaces=f.data.collections_scopes,f.formats=f.data.format,f.collectionFormats=f.data.collections_formats,f.surfaces=f.data.surface,f.collectionSurfacesTypes=f.data.collections_surfaces,f.chosenProduct=f.productType[Object.keys(f.productType)[0]],f.chosenProductId=f.chosenProduct.id,f.collectionsFilter=[],f.catalogPos=b.type,"plitka"==b.id){for(var g in f.collections)75==f.collections[g].product_type_id&&f.collectionsFilter.push(f.collections[g]);f.collections=f.collectionsFilter,f.catalogHeader=f.productType[75].name||"Каталог"}if("iskusstvenniy_kamen"==b.id){for(var g in f.collections)76==f.collections[g].product_type_id&&f.collectionsFilter.push(f.collections[g]);f.collections=f.collectionsFilter,f.catalogHeader=f.productType[76].name||"Каталог"}if("kamen"==b.id){for(var g in f.collections)(76==f.collections[g].product_type_id||77==f.collections[g].product_type_id)&&f.collectionsFilter.push(f.collections[g]);f.collections=f.collectionsFilter,f.catalogHeader="Камень"}if("naturalniy_kamen"==b.id){for(var g in f.collections)77==f.collections[g].product_type_id&&f.collectionsFilter.push(f.collections[g]);f.collections=f.collectionsFilter,f.catalogHeader=f.productType[77].name||"Каталог"}if("mozaika"==b.id){for(var g in f.collections)78==f.collections[g].product_type_id&&f.collectionsFilter.push(f.collections[g]);f.collections=f.collectionsFilter,f.catalogHeader=f.productType[78].name||"Каталог"}if("soputstvuiuschie_materiali"==b.id){for(var g in f.materialsCollections)79==f.materialsCollections[g].product_type_id&&f.collectionsFilter.push(f.materialsCollections[g]);f.collections=f.collectionsFilter,f.catalogHeader=f.productType[79].name||"Каталог"}f.clearFiltersByURL=function(){c.placeFilter=[],c.surfaceTypesFilter=[],c.factoryFilter=[]},1==b.id&&b.places&&(f.clearFiltersByURL(),setTimeout(function(){$('[data-place="'+b.places+'"]').trigger("click")},1001)),1==b.id&&b.surface&&(f.clearFiltersByURL(),setTimeout(function(){$('[data-surface-type="'+b.surface+'"]').trigger("click")},1001)),1==b.id&&b.factory&&(f.clearFiltersByURL(),setTimeout(function(){$('[data-factory="'+b.factory+'"]').trigger("click")},1001)),f.collectionsArr=[];for(var g in f.collections)f.collections[g].product_type_id===f.chosenProductId&&f.collectionsArr.push(f.collections[g]);f.collections=$.map(f.collections,function(a){return[a]}),f.countryFilter=[],f.factoryFilter=[],f.colorFilter=[],f.colorFilterArr=[],f.minPrice="",f.surfaceFilter=[],f.surfaceFilterArr=[],f.placeFilter=[],f.placeFilterArr=[],f.formatFilter=[],f.formatFilterArr=[],f.surfaceTypesFilter=[],f.surfaceTypesFilterArr=[],f.setCountryFilter=function(a){var b=$(".filter-countries");-1==f.countryFilter.indexOf(a)?(f.countryFilter.push(a),b.find('[data-country="'+a+'"]').addClass("active")):(f.countryFilter.splice(f.countryFilter.indexOf(a),1),b.find('[data-country="'+a+'"]').removeClass("active")),c.countryFilter=f.countryFilter;for(var d=b.find(".filter-chosen"),e="",g=0;g<f.countryFilter.length;g++)e+="<li>"+f.countries[f.countryFilter[g]].name+"</li> ";d.html(e)},f.filterByCountries=function(a){return f.countryFilter.length>0?-1!==f.countryFilter.indexOf(a.country_id):!0},f.setFactoryFilter=function(a){var b=$(".filter-factories");-1==f.factoryFilter.indexOf(a)?(f.factoryFilter.push(a),b.find('[data-factory="'+a+'"]').addClass("active")):(f.factoryFilter.splice(f.factoryFilter.indexOf(a),1),b.find('[data-factory="'+a+'"]').removeClass("active")),c.factoryFilter=f.factoryFilter;for(var d=b.find(".filter-chosen"),e="",g=0;g<f.factoryFilter.length;g++)e+="<li>"+f.factories[f.factoryFilter[g]].name+"</li> ";d.html(e)},f.filterByFactories=function(a){return f.factoryFilter.length>0?-1!==f.factoryFilter.indexOf(a.factory_id):!0},f.setColorFilter=function(a){var b=$(".filter-colors");-1==f.colorFilter.indexOf(a)?(f.colorFilter.push(a),b.find('[data-color="'+a+'"]').addClass("active")):(f.colorFilter.splice(f.colorFilter.indexOf(a),1),b.find('[data-color="'+a+'"]').removeClass("active")),c.colorFilter=f.colorFilter;for(var d=b.find(".filter-chosen"),e="",g=0;g<f.colorFilter.length;g++)e+='<li style="background-color: #'+f.colors[f.colorFilter[g]].css_code+'"></li> ';if(d.html(e),f.colorFilterArr=[],f.colorFilter.length>0)for(var h=0;h<f.colorFilter.length;h++)for(var i=0;i<f.collectionColors[f.colorFilter[h]].length;i++)-1==f.colorFilterArr.indexOf(f.collectionColors[f.colorFilter[h]][i])&&f.colorFilterArr.push(f.collectionColors[f.colorFilter[h]][i])},f.filterByColors=function(a){return f.colorFilterArr.length>0?-1!==f.colorFilterArr.indexOf(+a.id):!0},f.setPriceFilter=function(a){var b=$(".filter-prices");b.find('[data-price="'+a+'"]').hasClass("active")?(b.find("[data-price]").removeClass("active"),f.minPrice=""):(b.find("[data-price]").removeClass("active"),b.find('[data-price="'+a+'"]').toggleClass("active"),f.minPrice=+a);var d=b.find(".filter-chosen"),e="";e=""!=f.minPrice?b.find('[data-price="'+a+'"]').text():"",c.filterPriceText=e,d.html("<li>"+e+"</li>")},f.filterByPrice=function(a){return 1e3==f.minPrice?f.collectionPrices[a.id]<=1e3:2e3==f.minPrice?f.collectionPrices[a.id]>1e3&&f.collectionPrices[a.id]<=2e3:2001==f.minPrice?f.collectionPrices[a.id]>=2001:!0},f.setSurfaceFilter=function(a){var b=$(".filter-surface");-1==f.surfaceFilter.indexOf(a)?(f.surfaceFilter.push(a),b.find('[data-surface="'+a+'"]').addClass("active")):(f.surfaceFilter.splice(f.surfaceFilter.indexOf(a),1),b.find('[data-surface="'+a+'"]').removeClass("active")),c.surfaceFilter=f.surfaceFilter;for(var d=b.find(".filter-chosen"),e="",g=0;g<f.surfaceFilter.length;g++)e+="<li>"+f.surfaceTypes[f.surfaceFilter[g]].name+"</li> ";if(d.html(e),f.surfaceFilterArr=[],f.surfaceFilter.length>0)for(var h=0;h<f.surfaceFilter.length;h++)for(var i=0;i<f.collectionSurfaces[f.surfaceFilter[h]].length;i++)-1==f.surfaceFilterArr.indexOf(f.collectionSurfaces[f.surfaceFilter[h]][i])&&f.surfaceFilterArr.push(f.collectionSurfaces[f.surfaceFilter[h]][i])},f.filterBySurface=function(a){return f.surfaceFilterArr.length>0?-1!==f.surfaceFilterArr.indexOf(+a.id):!0},f.setPlacesFilter=function(a){var b=$(".filter-place");-1==f.placeFilter.indexOf(a)?(f.placeFilter.push(a),b.find('[data-place="'+a+'"]').addClass("active")):(f.placeFilter.splice(f.placeFilter.indexOf(a),1),b.find('[data-place="'+a+'"]').removeClass("active")),c.placeFilter=f.placeFilter;for(var d=b.find(".filter-chosen"),e="",g=0;g<f.placeFilter.length;g++)e+="<li>"+f.places[f.placeFilter[g]].name+"</li> ";if(d.html(e),f.placeFilterArr=[],f.placeFilter.length>0)for(var h=0;h<f.placeFilter.length;h++)for(var i=0;i<f.collectionPlaces[f.placeFilter[h]].length;i++)-1==f.placeFilterArr.indexOf(f.collectionPlaces[f.placeFilter[h]][i])&&f.placeFilterArr.push(f.collectionPlaces[f.placeFilter[h]][i])},f.filterByPlace=function(a){return f.placeFilterArr.length>0?-1!==f.placeFilterArr.indexOf(a.id):!0},f.setFormatsFilter=function(a){var b=$(".filter-format");-1==f.formatFilter.indexOf(a)?(f.formatFilter.push(a),b.find('[data-format="'+a+'"]').addClass("active")):(f.formatFilter.splice(f.formatFilter.indexOf(a),1),b.find('[data-format="'+a+'"]').removeClass("active")),c.formatFilter=f.formatFilter;for(var d=b.find(".filter-chosen"),e="",g=0;g<f.formatFilter.length;g++)e+="<li>"+f.formats[f.formatFilter[g]].name+"</li> ";if(d.html(e),f.formatFilterArr=[],f.formatFilter.length>0)for(var h=0;h<f.formatFilter.length;h++)for(var i=0;i<f.collectionFormats[f.formatFilter[h]].length;i++)-1==f.formatFilterArr.indexOf(f.collectionFormats[f.formatFilter[h]][i])&&f.formatFilterArr.push(f.collectionFormats[f.formatFilter[h]][i])},f.filterByFormat=function(a){return f.formatFilterArr.length>0?-1!==f.formatFilterArr.indexOf(+a.id):!0},f.setSurfaceTypesFilter=function(a){var b=$(".filter-surface-type");-1==f.surfaceTypesFilter.indexOf(a)?(f.surfaceTypesFilter.push(a),b.find('[data-surface-type="'+a+'"]').addClass("active")):(f.surfaceTypesFilter.splice(f.surfaceTypesFilter.indexOf(a),1),b.find('[data-surface-type="'+a+'"]').removeClass("active")),c.surfaceTypesFilter=f.surfaceTypesFilter;for(var d=b.find(".filter-chosen"),e="",g=0;g<f.surfaceTypesFilter.length;g++)e+="<li>"+f.surfaces[f.surfaceTypesFilter[g]].name+"</li> ";if(d.html(e),f.surfaceTypesFilterArr=[],f.surfaceTypesFilter.length>0)for(var h=0;h<f.surfaceTypesFilter.length;h++)for(var i=0;i<f.collectionSurfacesTypes[f.surfaceTypesFilter[h]].length;i++)-1==f.surfaceTypesFilterArr.indexOf(f.collectionSurfacesTypes[f.surfaceTypesFilter[h]][i])&&f.surfaceTypesFilterArr.push(f.collectionSurfacesTypes[f.surfaceTypesFilter[h]][i])},f.filterBySurfaceTypes=function(a){return f.surfaceTypesFilterArr.length>0?-1!==f.surfaceTypesFilterArr.indexOf(a.id):!0},f.showFilters=function(a){$(a).hasClass("active")?$(a).removeClass("active"):($(".filter").removeClass("active"),$(a).addClass("active"))},$(".filter").click(function(a){a.stopPropagation()}),$(document).click(function(){$(".filter").removeClass("active")}),e.h1=f.data.pages.catalog.seo.h1,e.title=f.data.pages.catalog.seo.title,e.description=f.data.pages.catalog.seo.description,e.keywords=f.data.pages.catalog.seo.keywords,d.htmlReady()})}]),angular.module("plitkaApp").controller("ProjectsCtrl",["$http","$scope","$rootScope",function(a,b,c){var d=this;c.route="projects",a.get("http://plitka.dev.grapheme.ru/application/get").success(function(a){d.data=a,d.projects=d.data.projects,d.galleries=d.data.galleries,d.photos=d.data.photos,d.collections=d.data.collections,d.products=d.data.products,d.factory=d.data.factory,d.productTypes=d.data.product_type,c.h1=d.data.pages.projects.seo.h1,c.title=d.data.pages.projects.seo.title,c.description=d.data.pages.projects.seo.description,c.keywords=d.data.pages.projects.seo.keywords,b.htmlReady()});$(".fancybox").fancybox({padding:0,helpers:{title:{type:"inside"}}})}]),angular.module("plitkaApp").directive("promoSlider",function(){return{templateUrl:"views/partials/slider.html",restrict:"E",controllee:"SliderCtrl"}}),angular.module("plitkaApp").controller("SliderCtrl",["$scope",function(a){a.awesomeThings=["HTML5 Boilerplate","AngularJS","Karma"]}]),angular.module("plitkaApp").controller("SeparticleCtrl",["$http","$routeParams","$scope","$rootScope",function(a,b,c,d){$("html, body").animate({scrollTop:$("main").offset().top},400);var e=this;a.get("http://plitka.dev.grapheme.ru/application/get").success(function(a){e.data=a,e.articles=e.data.articles,e.photos=e.data.photos,e.articleBySlug={};for(var f in e.articles)e.articleBySlug[e.articles[f].slug]=e.articles[f];e.articleId=e.articleBySlug[b.id].id,d.h1=e.articles[e.articleId].seo.h1,d.title=e.articles[e.articleId].seo.title,d.description=e.articles[e.articleId].seo.description,d.keywords=e.articles[e.articleId].seo.keywords,c.htmlReady()})}]),angular.module("plitkaApp").controller("SearchCtrl",["$http","$location","$rootScope","$scope","$route",function(a,b,c,d,e){var f=this;f.searchStr="",f.sendForm=function(a){$.ajax({type:"POST",url:"http://plitka.dev.grapheme.ru/ajax/search",data:{q:a},dataType:"json"}).done(function(a){c.searchData=a,c.searchData.queryStr=f.searchStr,d.$apply(function(){b.path("/search-results")}),e.reload()}).fail(function(){}).always(function(){})}}]),angular.module("plitkaApp").controller("SearchResultsCtrl",["$rootScope","$location","$scope","$http",function(a,b,c,d){var e=this;d.get("http://plitka.dev.grapheme.ru/application/get").success(function(b){e.data=b;var b=a.searchData;if(b){e.queryStr=b.queryStr,e.articles=b.results.articles,e.collections=b.results.collections,e.allCollections=e.data.collections,e.allArticles=e.data.articles,e.photos=e.data.photos,e.galleries=e.data.galleries,e.factories=e.data.factory,e.collectionPrices=e.data.collections_prices,e.articlesArr=[],e.collectionsArr=[],e.resultsCount=0;for(var d in e.articles)e.resultsCount++,e.articlesArr.push(d);for(var f in e.collections)e.resultsCount++,e.collectionsArr.push(f);e.searchCollections=[];for(var g=0;g<e.collectionsArr.length;g++)for(var h in e.allCollections)e.collectionsArr[g]==h&&e.searchCollections.push(e.allCollections[h]);e.searchArticles=[];for(var i=0;i<e.articlesArr.length;i++)for(var h in e.allArticles)e.articlesArr[i]==h&&e.searchArticles.push(e.allArticles[h]);c.htmlReady()}})}]),angular.module("plitkaApp").controller("CatalogItemCtrl",["$scope",function(a){a.awesomeThings=["HTML5 Boilerplate","AngularJS","Karma"]}]),angular.module("plitkaApp").controller("CollectionCtrl",["$http","$routeParams","$scope","$rootScope",function(a,b,c,d){var e=this;a.get("http://plitka.dev.grapheme.ru/application/get").success(function(a){e.data=a,$.each(e.data.options,function(a,b){"course_euro_rub"==b.slug&&(e.course_euro_rub=b.name)}),e.collectionId=b.id,e.products=e.data.products,e.collections=e.data.collections,e.photos=e.data.photos,e.countries=e.data.countries,e.factories=e.data.factory,e.galleries=e.data.galleries,e.productTypes=e.data.product_type,e.surfaceTypes=e.data.surface_type,e.formats=e.data.format,e.collectionBySlug={};for(var f in e.collections)e.collectionBySlug[e.collections[f].slug]=e.collections[f];e.collections=e.collectionBySlug,e.collection=e.collections[e.collectionId],e.collectionPhoto=e.photos[e.galleries[e.collection.gallery_id].photos[0]].full,e.country=e.countries[e.collections[e.collectionId].country_id],e.factories[e.collections[e.collectionId].factory_id]&&(e.factoryName=e.factories[e.collections[e.collectionId].factory_id].name,e.factoryImg=e.factories[e.collections[e.collectionId].factory_id].image_id?e.photos[e.factories[e.collections[e.collectionId].factory_id].image_id].full:""),e.collectionSurfType=e.productTypes[e.collection.product_type_id].name,e.collectionSurfId=e.productTypes[e.collection.product_type_id].id,e.slides=e.galleries[e.collection.gallery_id].photos,e.sameCollections=[],e.mainFactory=e.collection.factory_id;for(var f in e.collections)e.collections[f].factory_id==e.mainFactory&&e.sameCollections.push(e.collections[f]);e.collectionPos=e.sameCollections.map(function(a){return a.id}).indexOf(e.collection.id),e.leftLink=e.sameCollections[e.collectionPos-1]?e.sameCollections[e.collectionPos-1].slug:"",e.rightLink=e.sameCollections[e.collectionPos+1]?e.sameCollections[e.collectionPos+1].slug:"",setTimeout(function(){jQuery(".fotorama").fotorama({width:"60%",height:"400",nav:!1,arrows:"always",autoplay:3e3,loop:!0})},100);$(".fancybox").fancybox({maxWidth:450,wrapCSS:"collFancybox",padding:0,helpers:{title:{type:"inside"}}});if(e.dicvals=e.collection.related_dicvals,e.dicvalsArr=[],e.dicvals.length>0)for(var g=0;g<e.dicvals.length;g++)e.dicvalsArr.push(e.dicvals[g].name);e.collectionNumId=e.collectionBySlug[e.collectionId].id,e.productsArr=[];for(var f in e.products)if(e.products[f].collection_id==e.collectionNumId){var h=e.products[f];if(h.unit&&(h.price=parseInt(h.price,10)),h.price_euro){var i=h.price_euro*e.course_euro_rub;h.price=i}if(h.unit&&e.data.units[h.unit]){var j=e.data.units[h.unit].name;h.price+=" руб/"+j}e.productsArr.push(h)}e.realName=e.collection.name;var k=e.collection.seo.h1;k&&""!=k&&(e.realName=k),d.title=e.collection.seo.title,d.description=e.collection.seo.title,d.keywords=e.collection.seo.title,c.htmlReady()})}]),angular.module("plitkaApp").controller("SubmenuCtrl",["$http","$routeParams","$location",function(a,b){var c=this;c.isActive=function(a){return a===b.type},a.get("http://plitka.dev.grapheme.ru/application/get").success(function(a){c.data=a,c.places=c.data.scope,c.collectionPlaces=c.data.collections_scopes,c.materials=c.data.product_type_others_collections})}]),angular.module("plitkaApp").controller("HeadCtrl",["$rootScope",function(){}]),angular.module("plitkaApp").controller("NavCtrl",["$scope","$routeParams",function(){}]);