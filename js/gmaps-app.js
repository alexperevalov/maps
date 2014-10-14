/**
 * Created by alexp on 10/8/14.
 */
var map;
$(document).ready(function () {
    map = new GMaps({
        div: '#map',
        lat: 0,
        lng: 0,
        zoom: 1
    });

    map.addControl({
        position: 'top_right',
        content: '<b>Geolocate</b>',
        style: {
            margin: '5px',
            padding: '1px 6px',
            border: 'solid 1px #717B87',
            background: '#fff'
        },
        events: {
            click: function(){
                //console.log(this);
                alert('Click!');
            }
        }
    });
});