//fetching APi
let weather = {
    apiKey: "64193f2f918a240dc55542752d5e3706",
    fetchWeather: function (city) {
      fetch(
        "https://api.openweathermap.org/data/2.5/weather?q=" +
          city +
          "&units=metric&appid=" +
          this.apiKey
      )
        .then((response) => {
          if (!response.ok) {
            alert("No weather found.");
            throw new Error("No weather found.");
          }
          return response.json();
        })
        .then((data) => {
          this.displayWeather(data);
          console.log("dataaaaa",data)
          localStorage.setItem(city.toUpperCase(), JSON.stringify(data));
        });
    },
    //displaying data
    displayWeather: function (data) {
      
        
        document.getElementById("humidity").innerHTML = `Humidity: ${data.main.humidity}%`; 
        document.querySelector(".wind").innerHTML =`wind: ${data.wind.speed}km/h`;
 
        image=document.getElementsByClassName("weather-icon")[0];
        image.innerHTML=`<img src="https://openweathermap.org/img/w/${data.weather[0].icon}.png">`
        console.log(image)
        
        

        const { name } = data;
        const { icon, description } = data.weather[0];
        const { temp, humidity } = data.main;
        const { speed } = data.wind;
        console.log(data)
      //Adding Date
      const today = new Date();
      const date = today.getDate();
      const month = today.getMonth() + 1; 
      const year = today.getFullYear();
      //Adding Days
  const days = ["Sunday","Monday","Tuesday", "Wednesday", "Thursday","Friday","Saturday"]
  const currentDate = new Date();
  const currentDay = days[currentDate.getDay()];
  document.getElementById("date").innerHTML=`${date}/ ${month}/ ${year},${currentDay}`;


        document.querySelector(".city").innerText = name;
        document.querySelector(".temp").innerText = Math.round(temp) + "°C";
        document.querySelector(".day").innerText = date.weather.main;
        document.querySelector(".description").innerText = description;        
        document.querySelector(".date").innerText = date.toDateString();
        document.querySelector(".data").classList.remove("loading");
        
    
      },
    search: function () {
      this.fetchWeather(document.querySelector(".search-bar").value);
    },
  };
  
  document.querySelector(".search button").addEventListener("click", function () {
    weather.search();
  });
  
  document.querySelector(".search-bar").addEventListener("keyup", function (event) {
    if (event.key == "Enter") {
      weather.search();
    }
    
  });
  
  weather.fetchWeather("St Helens");
  