<!DOCTYPE html>
<html>
<head>
    <title>WeatherNow - Live Forecast</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body class="p-4 bg-light">
    <div class="container text-center">
        <h1 class="mb-4">🌤️ WeatherNow</h1>
        <p>Get current weather by your location or search a city.</p>

        <input type="text" id="cityInput" class="form-control mb-3" placeholder="Enter city name">
        <button onclick="searchCity()" class="btn btn-primary mb-4 me-2">Search</button>
        <button onclick="getLocation()" class="btn btn-secondary mb-4">Use My Location</button>

        <div id="weatherResult" class="p-4 bg-white shadow-sm rounded"></div>
    </div>

    <script>
        function displayWeather(data) {
            const html = `
                <h2>${data.name}</h2>
                <h4>${data.weather[0].description}</h4>
                <h3>${data.main.temp} °C</h3>
                <button onclick="saveWeather('${data.name}', '${data.main.temp}', '${data.weather[0].main}')" class='btn btn-success mt-3'>Save to Log</button>
            `;
            $('#weatherResult').html(html);
        }

        function searchCity() {
            const city = $('#cityInput').val();
            if (city) {
                $.get('/get-weather', { city }, function(data) {
                    displayWeather(data);
                }).fail(function() {
                    alert("Could not fetch weather data. Please check the city name or try again.");
                });
            }
        }

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    const apiKey = "ebb26c5e7fe37c3d77a0a87ee9770299";
                    const url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric`;
                    $.get(url, function(data) {
                        displayWeather(data);
                    });
                }, function(error) {
                    alert("Location permission denied.");
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function saveWeather(city, temperature, condition) {
            $.ajax({
                url: '/save-weather',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ city, temperature, condition }),
                success: function(res) {
                    alert('Saved successfully!');
                },
                error: function() {
                    alert('Failed to save weather data.');
                }
            });
        }
    </script>
</body>
</html>
