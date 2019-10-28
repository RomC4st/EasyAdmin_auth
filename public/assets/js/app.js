(function (window) {
  const mapboxKey=document.getElementById('hidden')
  mapboxgl.accessToken = mapboxKey.innerHTML
  const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [2.3488, 48.8534],
    zoom: 12,
  });
  const HttpRequest = (DATA) => {
    axios.get('/api/products')
      .then((res) => {
        const item = res.data['hydra:member'].map(e => {
          if (e.isActive == 1) {
            return e;
          }
        });
        item.map(e => {
          if (e) {
            return DATA.push(e);
          }
        })
      }).then(() => {
        DATA.map((e, i) => {
          map.loadImage('assets/images/marker.png', function (error, image) {
        if (error) throw error;
        map.addImage(`product_${i}`, image);
          map.addLayer({
            "id": `point_${i}`,
            "type": "symbol",
            "source": {
              "type": "geojson",
              "data": {
                "type": "FeatureCollection",
                "features": [{
                  "type": "Feature",
                  "geometry": {
                    "type": "Point",
                    "coordinates": [DATA[i].longitude, DATA[i].latitude]
                  },
                  "properties": {
                    "description": `<strong>${DATA[i].name}</strong><p>${DATA[i].description}</p>`,
                  },
                }]
              }
            },
            "layout": {
              "icon-image": `product_${i}`,
              "icon-size": 0.75
            }
          })
        })
          map.on('click', `point_${i}`, function (e) {
            const coordinates = e.features[0].geometry.coordinates.slice();
            const description = e.features[0].properties.description;

            // Ensure that if the map is zoomed out such that multiple
            // copies of the feature are visible, the popup appears
            // over the copy being pointed to.
            while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
              coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
            }
            new mapboxgl.Popup()
              .setLngLat(coordinates)
              .setHTML(description)
              .addTo(map);
          });
          map.on('mouseenter', `point_${i}`, function () {
            map.getCanvas().style.cursor = 'pointer';
          });
          map.on('mouseleave',`point_${i}`, function () {
            map.getCanvas().style.cursor = '';
          });
      })
  })
}
  map.on("load", (e) => {
  const DATA = []
  HttpRequest(DATA)
});

}) (window)

