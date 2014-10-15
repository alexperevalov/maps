/**
 * Created by alexp on 10/9/14.
 */
var App;

Handlebars.registerHelper("ifvalue", function(conditional, options) {
    if (options.hash.value === conditional) {
        options.fn(this)
    } else {
        options.inverse(this);
    }
});

$(window).load(function(){
    App = Ember.Application.create();

    App.TileLayer =
        EmberLeaflet.TileLayer.extend({
            tileUrl:
                'http://api.tiles.mapbox.com/v4/' +
                    '{mapid}/' +
                    '{z}/{x}/{y}.png' +
                    '?access_token={token}&clear',
            options: {
                mapid: 'alexanderperevalov.jngdj255',
                token: 'pk.eyJ1IjoiYWxleGFuZGVycGVyZXZhbG92IiwiYSI6InlwSEotTGsifQ.BRwcaB4zh1uexWSNAK5o1g'
            }
        });

    App.ApplicationRoute = Ember.Route.extend({
        model: function(){
            return App.Data.getCountry('us');
        }
    });

    App.Data = Ember.Object.extend();

    App.Data.reopenClass({

        getCountry: function(countryCode) {
            return $.getJSON("" + countryCode + ".json").then(function(response) {

                var items = [];

                response.forEach( function (data) {
                    items.push( {
                            location: L.latLng(data.latitude, data.longitude),
                            radius: data.radius
                        } );
                });
                return items;

            });
        }

    });

    App.CircleCollectionLayer =
        EmberLeaflet.CollectionLayer.extend({

            contentBinding: "controller",
            itemLayerClass: EmberLeaflet.CircleLayer.extend()

        });

    App.IndexView =
        EmberLeaflet.MapView.extend({
            center: L.latLng(20, 0),
            zoom: 2,
            childLayers: [
                App.TileLayer,
                App.CircleCollectionLayer
            ],
            didInsertElement: function(){
                console.log('the view is rendered');
                App.view = this;
                return this._super();
            }
        });

    App.ApplicationController =
        Ember.Controller.extend({

            actions: {

                reloadMarkers: function(e){
                    this.set('model', App.Data.getCountry($('#country_selector').val()));
//                    App.markesLayer._super().rerender();
                    console.log(App.view);
//                    App.view._childLayerClasses.pop();
//                    App.view.rerender();
//                    App.view._childLayerClasses.push(App.CircleCollectionLayer.create());
                    App.view.init();
                }

            }

        });

    App.CountriesController =
        Ember.ArrayController.create();

    Ember.$.getJSON('countries.json').then(function(data) {
        App.CountriesController.set('model', data);
    });

});