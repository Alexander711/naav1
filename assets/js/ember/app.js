/*************************************************
/* APPLICATIONS
/************************************************/
var QFilter = Em.Application.create({
	rootElement: '#qfilter',
    ready: function(){
        this.initialize();
    }
});

/*************************************************
/* CONTROLLERS
/************************************************/
QFilter.ApplicationController = Em.Controller.extend();

QFilter.LoadableObject = Em.Mixin.create({
	isLoaded: false,
	deferred: null,
	init: function(){
		this._super();
		this.set('deferred', $.Deferred());
	},
	didIsLoadedChange: function(){
		if (this.get('isLoaded') === false)
			this.set('deferred', $.Deferred());
		else
			this.get('deferred').resolve();
	}.observes('isLoaded')
});



QFilter.FiltersController = Em.ArrayController.extend(QFilter.LoadableObject, {

	content: [],

	selectedFilter: function(){
		var selected = this.get('content').filterProperty('selected', true).get('firstObject');
		if (selected !== undefined)
			return selected.get('type');
		else
			return null;
	}.property('content.@each.selected'), 

	
	// Загрузка фильтров
	init: function(){
		this._super();

		var that = this;

		$.ajax({
			url: '/rest/fastfilter/get_filters',
			dataType: 'json'
		}).done(function(result){

			that.beginPropertyChanges();
			$(result.data).each(function(index, value){
				var filter = QFilter.Filter.create({
					type: value.type,
					text: value.text,
					selected: value.selected
				});   
                that.pushObject(filter);
			});
			that.endPropertyChanges();

			that.set('isLoaded', true);

		});
			
	},

	changeCurrentFilter: function(filter){
		var selected = this.get('content').filterProperty('type', filter).get('firstObject');
		if (selected === undefined || selected.get('selected') === true)
			return false;
		
		this.get('content').filterProperty('selected', true).get('firstObject').set('selected', false);
		
		selected.set('selected', true);
		//this.notifyPropertyChange('content');
	}
});

QFilter.CitiesController = Em.ArrayController.extend(QFilter.LoadableObject, {
	content: [],
	otherCities: [],

	selectedCity: function(){
		var selected = this.get('content').filterProperty('selected', true).get('firstObject');
		if (selected !== undefined){
		
			return selected.get('id');
		}
		else{
			return null;
		}
			
	}.property('content.@each.selected'),


	xhr: null,

	changeCurrentCity: function(city){

		var selectedOnMain = this.get('content').filterProperty('id', city).get('firstObject');
		var selectedOnOthers = this.get('otherCities').filterProperty('id', city).get('firstObject');
		if ((selectedOnMain === undefined && selectedOnOthers === undefined) || ((selectedOnMain !== undefined && selectedOnMain.get('selected') === true) && (selectedOnOthers !== undefined && selectedOnOthers.get('selected') === true)))
			return false;

		this.get('content').filterProperty('selected', true).forEach(function(item, index, self){
			item.set('selected', false);
		});

		if (selectedOnMain !== undefined)
		{
			selectedOnMain.set('selected', true);	
		}
		else
		{
			selectedOnOthers.set('selected', true);
			// Меняем местами выбранный доп. город и последний основной город
			var mainCities = this.get('content');
			mainCities.unshiftObject(selectedOnOthers);
			this.get('otherCities').removeObject(selectedOnOthers).pushObject(mainCities.popObject());
		}

	},

	

	// Обновление городов, метод-наблюдатель
	// Observing -  QFilter.router.filtersController.content.@each.selected
	updateCities: function(obj, key, value){

		//if (QFilter.get('router').get('filtersController').get('selectedFilter') === null )
		//	return false;

		var currentFilter = QFilter.get('router').get('filtersController').get('selectedFilter');

		var xhr = this.get('xhr');

		if (xhr !== null)
			xhr.abort();
		
			
		this.set('isLoaded', false);
		
		this.set('content', []);
		this.set('otherCities', []);

		var that = this;
		
		this.set('xhr', 
			// Пишем ajax запрос, если несколько раз нажмут - убъем запрос
			$.ajax({
				url: '/rest/fastfilter/get_cities',
				type: 'post',
				dataType: 'json',
				data: {filter: currentFilter}
			}).done(function(result){


				that.beginPropertyChanges('content');
				$(result.data).each(function(index, value){
					that.get('content').pushObject(QFilter.City.create({
	                    id: value.id,
						name: value.name,
						selected: value.selected
					}));
				});
				that.endPropertyChanges('content');
				



				if (that.get('content').length > 5)
				{
					that.set('otherCities', that.get('content').splice(6, that.get('content').length-1));
				}
				else
				{
					that.set('otherCities', []);
				}
					



				that.set('isLoaded', true);

			})
		);
	}.observes('QFilter.router.filtersController.selectedFilter')
});

QFilter.TagsController = Em.ArrayController.extend(QFilter.LoadableObject, {
	doHide: true,
	content: [],
	xhr: null,

	init: function(){
		this._super();
	},
	updateTags: function(){


		var mapController = QFilter.getPath('router.mapController');
		mapController.get('deferred').done(function(){
			QFilter.getPath('router.mapController.ymap').controls.remove(QFilter.getPath('router.mapController.zoomToolBar'));
		});
		if (QFilter.get('router').get('filtersController').get('selectedFilter') === null || QFilter.get('router').get('filtersController').get('selectedFilter') == 'map' || QFilter.get('router').get('citiesController').get('selectedCity') === null)
			return false;


		
		this.set('isLoaded', false);
		var xhr = this.get('xhr');

		if (xhr !== null)
			xhr.abort();





		var that = this;


		this.set('xhr', 
			$.ajax({
				url: '/rest/fastfilter/tags',
				type: 'post',
				dataType: 'json',
				data: {
					filter: QFilter.get('router').get('filtersController').get('selectedFilter'),
					city_id: QFilter.get('router').get('citiesController').get('selectedCity')
				}
			}).done(function(result){
				that.set('content', []);
								
				$(result.data).each(function(index, value){
					that.pushObject(QFilter.Tag.create({
						url: value.url,
						text: value.text
					}));
				});
				

				that.set('isLoaded', true);

			})
		)
	}.observes('QFilter.router.citiesController.content.@each.selected')
});

QFilter.PlacemarksController = Em.ArrayController.extend(QFilter.LoadableObject, {
	xhr: null,
	loadedCities: [],
	updatePlacemarks: function(){


		if (QFilter.get('router').get('filtersController').get('selectedFilter') === null || QFilter.get('router').get('filtersController').get('selectedFilter') != 'map' || QFilter.get('router').get('citiesController').get('selectedCity') === null)
			return false;

		

		QFilter.getPath('router.mapController.deferred').done(function(){
			var that = QFilter.getPath('router.placemarksController');
			var mapController = QFilter.getPath('router.mapController');
			mapController.set('zoomToolBar', new ymaps.control.MapTools(['zoomControl']));
			mapController.get('ymap').controls.add(mapController.get('zoomToolBar'), {top: '100px', right: '10px'});

			that.set('isLoaded', false);
			var xhr = that.get('xhr');
			
			if (xhr !== null)
				xhr.abort();



			
			that.set('xhr', 
				$.ajax({
					url: '/rest/fastfilter/tags',
					type: 'post',
					dataType: 'json',
					data: {
						filter: QFilter.get('router').get('filtersController').get('selectedFilter'),
						city_id: QFilter.get('router').get('citiesController').get('selectedCity')
					}
				}).done(function(result){

					if ($.inArray(QFilter.get('router').get('citiesController').get('selectedCity'), that.get('loadedCities')) == -1)
					{
						that.set('content', []);
						mapController.set('ymapCollection', []);		
						$(result.data).each(function(index, value){

							var placemark = new ymaps.Placemark([value.coords[1], value.coords[0]], {
			                    balloonContentHeader: value.name,
			                    balloonContentBody: value.info
							});
							mapController.get('ymapCollection').push(placemark);

						});
		
						mapController.get('cluster').add(mapController.get('ymapCollection'))
						that.get('loadedCities').push(QFilter.get('router').get('citiesController').get('selectedCity'));
					}

					
					
					
					var geocoder = ymaps.geocode(QFilter.getPath('router.citiesController.content').filterProperty('selected', true).get('firstObject').get('name'), {
						results: 1
					});

					geocoder.then(function(res){
						
						var deferred = $.Deferred();
						mapController.get('didHeightChange').done(function(){
							mapController.get('ymap').panTo(res.geoObjects.get(0).geometry.getCoordinates(), {
								flying: true,
								duration: 2000,
								callback: deferred.resolve
							});

							deferred.done(function(){
								mapController.get('ymap').setZoom(9, {duration: 2000});
							});

						});

						//that.get('ymap').geoObjects.add(res.geoObjects);
					});
					that.set('isLoaded', true);

				})
			)
		});


	}.observes('QFilter.router.citiesController.content.@each.selected')
});
QFilter.MapController = Em.ArrayController.extend(QFilter.LoadableObject, {
	content: [],
	ymap: null,
	ymapCollection: [],
	cluster: null,
	zoomToolBar: null,
	didHeightChange: null,
	init: function(){
		this._super();
		this.set('didHeightChange', $.Deferred()); 
	},
	//geocoder: null,
	// Инициализация карты
	initMap: function(){
		that = this;
		ymaps.ready(function(){
			//var map = 
			//console.log(map);
			that.set('ymap', new ymaps.Map('qfilter-ymap', {
		    	center: [66.30792429301192, 100.48454682513717],
    			zoom: 3
			}));
			


			that.set('cluster', new ymaps.Clusterer());

			that.set('zoomToolBar', new ymaps.control.MapTools(['zoomControl']));
			

			that.get('ymap').geoObjects.add(that.get('cluster'));
			that.set('isLoaded', true);
		});
		this.removeObserver('QFilter.router.citiesController.isLoaded', this, 'initMap');
	},
	isLoadedChange: function(){
	}.observes('isLoaded')

});
/*************************************************
/* MODELS
/************************************************/

QFilter.Filter = Em.Object.extend({
	type: null,
	text: null,
	selected: false
});

QFilter.City = Em.Object.extend({
    id: null,
	name: null,
	selected: false
});
QFilter.Tag = Em.Object.extend({
	url: null,
	text: null
});
QFilter.Placemark = Em.Object.extend({
	lng: null,
	lat: null,
	name: null,
	info: null
});

/*************************************************
/* VIEWS
/************************************************/
QFilter.ApplicationView = Em.View.extend({
	templateName: 'application' 
});

QFilter.FiltersView = Em.View.extend({
	templateName: 'qfilter-filters',
	classNames: ['qfilter-filters-nav'],
	FiltersContainerView: Em.CollectionView.extend({
		tagName: 'ul',
		contentBinding: 'QFilter.router.filtersController.content',
		itemViewClass: Em.View.extend({
			templateName: 'qfilter-filters-item',
			change: function(){
	          	QFilter.get('router').transitionTo('filterType.index', {has_items: this.getPath('content.type')});
			}
		})
	})	
	
});



QFilter.CitiesView = Em.View.extend({
	isVisibleBinding: 'QFilter.router.filtersController.isLoaded',
	templateName: 'qfilter-main-cities',
	classNames: ['qfilter-main-cities'],
	MainCitiesContainerView: Em.CollectionView.extend({
		tagName: 'ul',
		classNames: ['qfilter-cities'],
		contentBinding: 'QFilter.router.citiesController.content',
		itemViewClass: Em.View.extend({
			templateName: 'qfilter-main-cities-item',
			change: function(event){
				QFilter.get('router').send('doCity', {id: event.view.content.id});
			}
		})
	}),

	OtherCitiesView: Em.View.extend({
		templateName: 'qfilter-other-cities',
		classNames: ['qfilter-other-cities'],

		isVisible: function(){
			controller = QFilter.get('router').get('citiesController');
			if (controller.get('otherCities').length < 1)
				return false;
			return true;
		}.property('QFilter.router.citiesController.otherCities'),
		
		// Раскрытие списка других городов
		mouseEnter: function(event, view){
			this.getPath('_childViews.0').set('isVisible', true)
		}, 
		mouseLeave: function(event, view){
			this.getPath('_childViews.0').set('isVisible', false)
			
		},
		
		OtherCitiesBodyView: Em.View.extend({
			templateName: 'qfilter-other-cities-body',
			isVisible: false,
			OtherCitiesContainerView: Em.CollectionView.extend({
				tagName: 'ul',
				isVisible: true,
				contentBinding: 'QFilter.router.citiesController.otherCities',

				didInsertElement: function(event){
					$(this).html('fdfdf');
				},
				itemViewClass: Em.View.extend({
					templateName: 'qfilter-other-cities-item',
					change: function(event){
						QFilter.get('router').send('doCity', {id: event.view.content.id});
					}
				})
			})			
		})

	})


});


QFilter.TagsView = Em.View.extend({
	isVisibleBinding: 'QFilter.router.filtersController.isLoaded',
	classNames: ['qfilter-tags-main'],
	templateName: 'qfilter-tags-main',
	doHideBinding: 'QFilter.router.tagsController.doHide',
	TagsBodyView: Em.View.extend({
		templateName: 'qfilter-tags-body',
		classNames: ['qfilter-tags-body'],
		isVisible: true,
		moreUrl: function(){
			//var currentFilter = 
			switch(QFilter.getPath('router.filtersController.selectedFilter')) 
			{
				case 'works':
					filterType = 'work';
					break
				case 'cars':
					filterType = 'auto';
					break
				case 'districts':
					filterType = 'district';
					break
				case 'metro':
					filterType = 'metro';
					break
				default: 
					filterType = 'auto';
			}
			return '/filter/' + filterType + '/city_' + QFilter.getPath('router.citiesController.selectedCity')
		}.property('QFilter.router.filtersController.selectedFilter', 'QFilter.router.citiesController.selectedCity'),
		moreText: function(){
			var text = 'Просмотреть другие ';
			switch(QFilter.getPath('router.filtersController.selectedFilter')) 
			{
				case 'works':
					text += 'услуги';
					break
				case 'cars':
					text += 'марки автомобилей';
					break
				case 'districts':
					text += 'округи';
					break
				case 'metro':
					text += 'станции метро';
					break
				default: 
					text += 'марки автомобилей';
			}
			return text;
		}.property('QFilter.router.filtersController.selectedFilter'),
		selectedFilterChange: function(){

			if (QFilter.getPath('router.filtersController.selectedFilter') === 'map'){
				if (this.get('isVisible') === true)
				{
					//var deferred = $.D
					this.$().hide('blind');
					this.set('isVisible', false);
				}
				
			}
			else{
				if (this.get('isVisible') == false){
					this.$().show('blind');
					this.set('isVisible', true);
				}

				
			}
		}.observes('QFilter.router.filtersController.selectedFilter'),

		TagsContainerView: Em.CollectionView.extend({
			tagName: 'ul',
			
			classNameBindings: ['mainClass', 'filterTypeClass'], 
			mainClass: 'qfilter-tags-container',
			filterTypeClass: function(){
				return QFilter.getPath('router.filtersController.selectedFilter');
			}.property('QFilter.router.filtersController.selectedFilter'),

			contentBinding: 'QFilter.router.tagsController.content',
			itemViewClass: Em.View.extend({
				templateName: 'qfilter-tags-item',
				didInsertElement: function(key){
					//console.log(key);
					//this.$().columnize({ columns: 3, lastNeverTallest: true });
					//this.$().fadeOut();
;
				}
			})
		}),
		slideUp: function(){

		}
	}),
	toggle: function(){
		QFilter.get('router').send('doCities');
		//this.$().animate({height: '20px'});
		//var child = this.getPath('_childViews.0');
		//child.$().hide('blind');
	}
});

QFilter.MapView = Em.View.extend({
	templateName: 'qfilter-ymap',
	elementId: 'qfilter-ymap',
	activeFilterChange: function(){

		
		var selectedFilter = QFilter.get('router').get('filtersController').get('selectedFilter');
		var that = this;
		QFilter.getPath('router.mapController.deferred').done(function(){
			that.$().css('height', 'auto');
			if (selectedFilter == 'map'){
				QFilter.get('router').get('mapController').set('didHeightChange', $.Deferred()); 

				$(QFilter.get('router').get('mapController').get('ymap').container.getElement()).animate({"height": '500px', complete: QFilter.get('router').get('mapController').get('didHeightChange').resolve()});
			}
			else{
				$(QFilter.get('router').get('mapController').get('ymap').container.getElement()).animate({"height": '350px'});

			}			
			QFilter.get('router').get('mapController').get('ymap').container.fitToViewport();
		});





	}.observes('QFilter.router.filtersController.selectedFilter'), 
	didInsertElement: function(){
		var mapController = QFilter.get('router').get('mapController');
		mapController.addObserver('QFilter.router.citiesController.isLoaded', mapController, 'initMap');
	} 
})
/*************************************************
/* ROUTERS
/************************************************/

QFilter.Router = Em.Router.extend({
	enableLogging: true,
	root: Em.Route.extend({
		index: Em.Route.extend({
			route: '/',
			redirectsTo: 'filters'
		}),
		filters: Em.Route.extend({
			route: '/filters',

            doFilterType: function(router, event){
                router.transitionTo('filterType', {has_items: event.filter});
            },
            doCities: function(router, event){
            	var currentFilter = router.get('filtersController').get('content').filterProperty('selected', true).get('firstObject');
            	router.transitionTo('filterType', {has_items: currentFilter.type});
            	router.transitionTo('cities.index');
            },
            doCity: function(router, event){
            	var currentFilter = router.get('filtersController').get('content').filterProperty('selected', true).get('firstObject');
            	router.transitionTo('filterType', {has_items: currentFilter.type});
                router.transitionTo('cities.city', {id: event.id});
            },

        	// Прикрепляем элементы
            connectOutlets: function(router, event){
				router.get('applicationController').connectOutlet({outletName: 'filters', viewClass: QFilter.FiltersView, controller: router.get('filtersController')});
                router.get('applicationController').connectOutlet({outletName: 'cities', viewClass: QFilter.CitiesView, controller: router.get('citiesController')});
                router.get('applicationController').connectOutlet({outletName: 'tags', viewClass: QFilter.TagsView, controller: router.get('tagsController')});  
                router.get('applicationController').connectOutlet({outletName: 'map', viewClass: QFilter.MapView, controller: router.get('mapController')}); 

			},

			index: Em.Route.extend({
				route: '/'
			}),

			filterType: Em.Route.extend({
				route: '/:has_items',


				deserialize: function(router, params){


					return {has_items: params.has_items};

				},
				serialize: function(router, params){

					router.getPath('filtersController.deferred').done(function(){
						router.get('filtersController').changeCurrentFilter(params.has_items);
					});
					return params;
				},


				index: Em.Route.extend({
					route: '/'
				}),

				cities: Em.Route.extend({
					route: '/cities',

					index: Em.Route.extend({
						route: '/',
						connectOutlets: function(router, event){
							QFilter.getPath('router.mapController').showCities();
						}
					}),
					city: Em.Route.extend({
						route: '/:id',
						deserialize: function(router, params){
							return params;
						},
						serialize: function(router, params){
							router.getPath('citiesController.deferred').done(function(){
								router.get('citiesController').changeCurrentCity(params.id);
							});
							return params;
						}	
					})
				})
			}),
			autoservicesOnMap: Em.Route.extend({
				route: '/autoservicesmap'
			})
		})
	})
});
