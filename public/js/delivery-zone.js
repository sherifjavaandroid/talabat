var map; // Global declaration of the map
var drawingManager;
var lastpolygon = null;
var polygons = [];


function initMap() {

    map = new google.maps.Map(document.getElementById("map"), {
        center: {
            lat: 0.00
            , lng: 0.00
        }
        , zoom: 8
        ,
    });


    drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: google.maps.drawing.OverlayType.POLYGON
        , drawingControl: true
        , drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER
            , drawingModes: [
                google.maps.drawing.OverlayType.POLYGON
                ,]
            ,
        }
        , markerOptions: {
            icon: "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png"
            ,
        }
        , circleOptions: {
            fillColor: "#ffff00"
            , fillOpacity: 1
            , strokeWeight: 5
            , clickable: false
            , editable: true
            , zIndex: 1
            ,
        }
        ,
    });

    drawingManager.setMap(map);

    //get current location block
    // infoWindow = new google.maps.InfoWindow();
    // Try HTML5 geolocation.
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const pos = {
                    lat: position.coords.latitude
                    , lng: position.coords.longitude
                    ,
                };
                map.setCenter(pos);
            });
    }


    google.maps.event.addListener(drawingManager, 'overlaycomplete', function (event) {

        if (lastpolygon != null) {
            lastpolygon.setMap(null);
        }
        var coordinates = event.overlay.getPath().getArray();
        lastpolygon = event.overlay;
        livewire.emit('selectedCoordinates', coordinates);
    });
}

function initEditMap(coordinates) {

    map = new google.maps.Map(document.getElementById("editMap"), {
        center: {
            lat: 0.00,
            lng: 0.00
        },
        zoom: 8,
    });


    drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: google.maps.drawing.OverlayType.POLYGON,
        drawingControl: true,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: [
                google.maps.drawing.OverlayType.POLYGON,
            ],

        },
        markerOptions: {
            icon: "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png",

        },
        circleOptions: {
            fillColor: "#ffff00",
            fillOpacity: 1,
            strokeWeight: 5,
            clickable: false,
            editable: true,
            zIndex: 1,
        },
    });
    // 
    drawingManager.setMap(map);
    // set prviouse selected data
    lastpolygon = new google.maps.Polygon({
        paths: coordinates,
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.1,
    });
    lastpolygon.setMap(map);


    var polygonBounds = new google.maps.LatLngBounds();
    lastpolygon.getPaths().forEach(function (path) {
        path.forEach(function (latlng) {
            polygonBounds.extend(latlng);
            map.fitBounds(polygonBounds);
        });
    });

    var boundsCenter = polygonBounds.getCenter();
    if (boundsCenter) {
        map.setCenter(boundsCenter);

        const startLat = boundsCenter.lat();
        const startLng = boundsCenter.lng()
        const endLat = coordinates[0].lat;
        const endLng = coordinates[0].lng;
        //
        const distance = distanceFrom(startLat, startLng, endLat, endLng) / 1000;
        var zoomLevel = Math.log2(40000 * Math.cos(startLat * Math.PI / 180) / distance);
        if (zoomLevel) {
            if (zoomLevel > 0) {
                zoomLevel = zoomLevel - 1;
            }
            map.setZoom(zoomLevel);
        }

    }



    google.maps.event.addListener(drawingManager, 'overlaycomplete', function (event) {

        if (lastpolygon != null) {
            lastpolygon.setMap(null);
        }
        var coordinates = event.overlay.getPath().getArray();
        lastpolygon = event.overlay;
        livewire.emit('selectedCoordinates', coordinates);
    });
}


//
livewire.on("initiateEditMap", (data) => {
    initEditMap(data);
});

//
livewire.on("resetMap", (data) => {
    if (lastpolygon != null) {
        lastpolygon.setMap(null);
    }
});


function distanceFrom(lat1, lng1, lat2, lng2) {
    var lat = [lat1, lat2]
    var lng = [lng1, lng2]
    var R = 6378137;
    var dLat = (lat[1] - lat[0]) * Math.PI / 180;
    var dLng = (lng[1] - lng[0]) * Math.PI / 180;
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat[0] * Math.PI / 180) * Math.cos(lat[1] * Math.PI / 180) *
        Math.sin(dLng / 2) * Math.sin(dLng / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c;
    return Math.round(d);
}




