(function (window) {

  mapboxgl.accessToken = 'pk.eyJ1IjoiY3IwbjBzIiwiYSI6ImNqdHB1cTQ2azA3cmw0M2swdGtiY3Noa3MifQ.EM0c8d_0JEcc3FOLQ8P0CA';
  const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [2.3488, 48.8534],
    zoom: 12,
  });
  const HttpRequest = (DATA) => {
    axios.get('/api/products')
      .then((res) => {
        const Longitude = res.data['hydra:member'].map(e => {
          return e.Longitude;
        });
        const Latitude = res.data['hydra:member'].map(e => {
          return e.Latitude;
        });
        DATA.push(Latitude, Longitude);
      }).then(() => {
        DATA[0].map((e, i) => {
          map.addLayer({
            "id": `point_${i}`,
            "type": "circle",
            "source": {
              "type": "geojson",
              "data": {
                "type": "FeatureCollection",
                "features": [{
                  "type": "Feature",
                  "geometry": {
                    "type": "Point",
                    "coordinates": [DATA[1][i], DATA[0][i]]
                  }
                }]
              }
            },
          })
        })
      })
  }
  map.on("load", (e) => {
    const DATA = []
    HttpRequest(DATA)
  });

})(window)

