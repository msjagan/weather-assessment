# Laravel Weather App Assessment 

# Steps I followed to finish the tasks
- Ran the CitySeeder to populate the cities on index page table
- Added the mapped the open weather API key to services.php
- Validated the weather log data
- Modifed the WeatherLog model and SendWeatherNotification to log the custom weather alert based on the condition of 3 degree change
- Ran the queue:work to log the weather alert as the ShouldQueue is mentioned in the SendWeatherNotification


Note: To check the custom log file named "weather-log" is created and listner works , please comment out the line no 43 and 45 on WeatherLog model to check it immediately. 

### Task 1: Display latest weather data in the view

Show the latest weather data for all cities in index route "/"


### Task 2: Integrate Weather API with Error Handling

- Call OpenWeatherMap API on `app/Services/CityWeatherService.php`
- A valid API key is available in the .env file.You must add it to the Laravel config

Example endpoint:
```
https://api.openweathermap.org/data/2.5/weather?q={CITY}&appid={API_KEY}&units=metric
```

- Parse the API response and save a record into the WeatherLog model
```
API Field	    Model Field
main.temp	    temperature
main.humidity	    humidity
wind.speed	    windSpeed
weather[0].description description
```

### Task 3: Event Listener

If the previous weather log temperature is greater than 3°C, trigger an Event Listener
- Listener should write the entry into a custom log file
