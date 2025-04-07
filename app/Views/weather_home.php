<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🌤️ WeatherNow - With User Auth</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
        }
        .card {
            background-color: rgba(0, 0, 0, 0.65);
            color: #ffffff;
            border: 1px solid #444;
        }
        .btn-custom {
            background-color: #4ecca3;
            border: none;
        }
        .btn-custom:hover {
            background-color: #3bb494;
        }
        .weather-icon {
            width: 60px;
            animation: floatIcon 2s ease-in-out infinite alternate;
        }
        @keyframes floatIcon {
            from { transform: translateY(0); }
            to { transform: translateY(-10px); }
        }
        .temp-bar-bg {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .temp-bar {
            height: 10px;
            background-color: #4ecca3;
            width: 0%;
            transition: width 1s ease-in-out;
        }
        .auth-bar {
            position: absolute;
            top: 10px;
            right: 20px;
        }
    </style>
</head>
<body class="p-4 position-relative">
    <!-- 🔐 Auth bar -->
    <div class="auth-bar">
        <?php if(session()->get('user')): ?>
            <span>👤 <?= session()->get('user')['name'] ?> | <a href="/logout" class="text-light">Logout</a></span>
        <?php else: ?>
            <a href="/login" class="btn btn-outline-light btn-sm">Login</a>
            <a href="/register" class="btn btn-outline-success btn-sm">Signup</a>
        <?php endif; ?>
    </div>

    <div class="container text-center py-5">
        <h1 class="mb-4">🌤️ WeatherNow</h1>
        <p class="mb-4">Live weather updates in dark mode — with user login & top 5 metro cities!</p>

        <div class="input-group mb-4">
            <input type="text" id="cityInput" class="form-control" placeholder="Search for a city">
            <button onclick="searchCity()" class="btn btn-custom">Search</button>
            <button onclick="getLocation()" class="btn btn-outline-light">📍 Use My Location</button>
        </div>

        <div id="weatherResult" class="p-4 bg-dark border rounded mb-5"></div>

        <h3 class="mb-3">🌆 Live Weather in Major Cities</h3>
        <div id="majorCities" class="row g-4 justify-content-center"></div>
    </div>

<script>
    const apiKey = "ebb26c5e7fe37c3d77a0a87ee9770299";
    const majorCities = ["New York", "Mumbai", "London", "Tokyo", "Wolverhampton"];

    $(document).ready(function() {
        loadMajorCities();
    });

    function loadMajorCities() {
        majorCities.forEach(city => {
            $.get(`https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`, function(data) {
                const icon = `https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png`;
                const condition = data.weather[0].main.toLowerCase();
                let bgClass = "default";
                if (condition.includes("clear")) bgClass = "clear";
                else if (condition.includes("cloud")) bgClass = "clouds";
                else if (condition.includes("rain")) bgClass = "rain";
                else if (condition.includes("snow")) bgClass = "snow";

                const tempPercent = Math.min(100, Math.max(0, (data.main.temp + 10) * 2)); // normalize temp for bar

                $('#majorCities').append(`
                    <div class="col-md-2 col-sm-6">
                        <div class="card p-3 text-center ${bgClass}">
                            <h5>${data.name}</h5>
                            <img src="${icon}" class="weather-icon" alt="${data.weather[0].main}">
                            <p>${data.main.temp}°C</p>
                            <small>${data.weather[0].description}</small>
                            <div class="temp-bar-bg mt-2">
                                <div class="temp-bar" style="width: ${tempPercent}%;"></div>
                            </div>
                        </div>
                    </div>
                `);
            });
        });
    }

    function displayWeather(data) {
        const html = `
            <h2>${data.name}</h2>
            <h4>${data.weather[0].description}</h4>
            <h3>${data.main.temp} °C</h3>
            <button onclick="saveWeather('${data.name}', '${data.main.temp}', '${data.weather[0].main}')" class='btn btn-success mt-3 me-2'>Save to Log</button>
            <a href="/weather-details?city=${data.name}" class='btn btn-info mt-3'>More Weather Info</a>
        `;
        $('#weatherResult').html(html);
    }

    function searchCity() {
        const city = $('#cityInput').val();
        if (city) {
            $.get('/get-weather', { city }, function(data) {
                displayWeather(data);
            });
        }
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                const url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric`;
                $.get(url, function(data) {
                    displayWeather(data);
                });
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
            }
        });
    }
</script>
</body>
</html>
