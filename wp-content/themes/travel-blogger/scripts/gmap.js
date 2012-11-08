function bindInfoWindow(marker, map, infoWindow, html) {
	google.maps.event.addListener(marker, 'click', function() {
		infoWindow.setContent(html);
		infoWindow.open(map, marker);
	});
}

function load(downloadFile,instance) {
	var mapOptions = {
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	
	var map = new google.maps.Map(document.getElementById(instance), mapOptions);
	var infoWindow = new google.maps.InfoWindow;

	downloadUrl(downloadFile, function(data) {
	    var xml = data.responseXML;
	
		if(xml == null || xml.documentElement.getElementsByTagName("marker").length == 0) {
			$('#'+instance).attr('style','padding:20px;');
			$('#'+instance).html('Sorry, no locations found.');
		} else {
		    var markers = xml.documentElement.getElementsByTagName("marker");
			var b = new google.maps.LatLngBounds();

			for (var i = 0; i < markers.length; i++) {
				var address = markers[i].getAttribute("address");
				var permalink = markers[i].getAttribute("permalink");
				var point = new google.maps.LatLng(
					parseFloat(markers[i].getAttribute("lat")),
					parseFloat(markers[i].getAttribute("lng"))
				);
				var html = "<b>" + address + "</b> <br/> " +
					"<a href ='"+ permalink +"'>View related post</a> <br/> ";
				var marker = new google.maps.Marker({
					map: map,
					position: point,
					icon: template_url + "/images/gmap/map_marker-blue.png"
				});
				bindInfoWindow(marker, map, infoWindow, html);

				if (markers.length > 1) {
					b.extend(point);
					if ((i+1) == markers.length) {
						map.fitBounds(b);
					}
				} else {
					map.setZoom(14);
					map.setCenter(point);
				}
			}
		}
	});
}

function downloadUrl(url, callback) {
  var request = window.ActiveXObject ?
      new ActiveXObject('Microsoft.XMLHTTP') :
      new XMLHttpRequest;

  request.onreadystatechange = function() {
    if (request.readyState == 4) {
      request.onreadystatechange = doNothing;
      callback(request, request.status);
    }
  };

  request.open('GET', url, true);
  request.send(null);
}
function doNothing() {}

function exp_render_gmap(address,lat,lng,instance) {
	if(lat != '' && lng !='') {
		var point = new google.maps.LatLng(
			parseFloat(lat),
			parseFloat(lng)
		);
		var mapOptions = {
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		map = new google.maps.Map(document.getElementById(instance), mapOptions);
	 	var infoWindow = new google.maps.InfoWindow;
		var html = "<b>Current location:</b> <br/>" + address;
		var marker = new google.maps.Marker({
			map: map,
			position: point,
			icon: template_url + "/images/gmap/map_marker-blue.png"
		});
		bindInfoWindow(marker, map, infoWindow, html);
		map.setZoom(14);
		map.setCenter(point);
	} else {
		$('#'+instance).attr('style','padding:20px;');
		$('#'+instance).html('Sorry, location not found.');
	}
}
