<template>
  <div id="app">
    <!--HEADER-->
    <section id="header" class="banner">
      <h1>WELCOME TO JAPAN!</h1>

      <form @submit.prevent="fetchWeatherData">
        <input type="text" class="search-input" placeholder="Enter City" 
               v-model="selectedCity"
               @input="errorMessage = ''">
        <input type="submit" class="search-btn" value="Search">
      </form>
      <p class="error-msg" v-if="!!errorMessage">{{ errorMessage }}</p>
      <p>or select one of the cities below</p>

      <div class="city-list">
        <div v-for="(city, index) in defaultCities" :key="index"
             @click="selectCity(city.name)"
             class="city">
          <p>{{ city.name }}</p>
        </div>
      </div>
    </section>

    <!--WEATHER INFO-->
    <section id="weather-info" class="banner">
      <h1>City: {{ details.city || 'No city selected  ' }}</h1>

      <div v-if="details.city" class="details">
        <div class="temperature">12&deg;</div>
        <div class="main" v-if="details.main">
          <p><b>Feels like: </b> {{ details.main.feels_like }}</p>
          <p><b>Humidity: </b> {{ details.main.humidity }}</p>
          <p><b>Pressure: </b> {{ details.main.pressure }}</p>
          <p><b>Temp: </b> {{ details.main.temp }}</p>
          <p><b>Wind: </b> {{ details.wind_speed }}</p>
        </div>
      </div>

      <div v-if="details.city" >
        <h1>Places To visit</h1>
        <div  v-for="venue in venues">
          <p><b>Name: </b> {{ venue }}</p>
        </div>
      </div>
    </section>

  </div>
</template>

<script>

export default {
  data() {
    return {
      selectedCity: '',
      defaultCities: [
        { name: 'Tokyo' },
        { name: 'Kyoto' },
        { name: 'Osaka' },
        { name: 'Sapporo' },
        { name: 'Nagoya' },
      ],
      errorMessage: '',
      details: {
        city: '',
        deg: '25',
        main: null,
        wind: null
      },
      venues: []
    }
  },
  methods: {
    fetchWeatherData() {
      if(!this.selectedCity) { 
        this.errorMessage = 'Please enter a city.';
        return; 
      }
      this.errorMessage = '';

      var axios = require("axios").default;
      var url = process.env.VUE_APP_APIHOST+`/api/details/${this.selectedCity}`;
      var options = {
        method: 'GET',
        url: url
      };

      axios.request(options).then((response) => {
        const _data = response.data.weather
        const _venues = response.data.venues.results;
        this.venues =_venues.map(venue => venue.name);

        this.details.city = _data.city.name
        this.details.main = _data.list[0].main;
        this.details.wind_speed = _data.list[0].wind.speed;
      }).catch((error) => {

      });
      
    },
    selectCity(city) {
      this.selectedCity = city;
      this.fetchWeatherData(city)
    }
  }
}
</script>

<style lang="scss">
$primary: #1A2980;

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: white;
}

#header {
  margin: 10px;
  padding: 20px;
  background: #1A2980;  /* fallback for old browsers */
  background: -webkit-linear-gradient(45deg, #26D0CE, #1A2980);  /* Chrome 10-25, Safari 5.1-6 */
  background: linear-gradient(45deg, #26D0CE, #1A2980); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

  h1 {
    color: white
  }

  .search-input {
    font-size: medium;
    padding: 10px;
    border-radius: 10px 0px 0px 10px;
    border: none;
  } 

  .search-btn {
    padding: 10px;
    border-radius: 0px 10px 10px 0px;
    font-size: medium;
    border: none;
    color: $primary;
  } 
  
  @media only screen and (max-width: 380px) {
    .search-btn {
      min-width: 120px;
      margin-top: 10px;
      border-radius: 10px;
    }

    .search-input {
      border-radius: 10px;
    }
  }

  .search-btn:hover {
    cursor: pointer;
    color: #1A2980  ;
  }

  .city-list {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;

    .city {
      margin: 10px;
      min-width: 100px;
      color: #1A2980;  /* fallback for old browsers */
      border-radius: 10px;
      background-color: white;
    }

    .city:hover {
      transform: scale(1.1);
      cursor: pointer;
    }
  }

  .error-msg {
    color: #FF2A00;
  }
}

#weather-info {
  margin: 10px;
  padding: 20px;
  background: $primary;  
  background: -webkit-linear-gradient(45deg, #26D0CE, #1A2980);
  background: linear-gradient(45deg, #26D0CE, #1A2980);

  .details {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;

    .temperature {
      font-size: 100px;
    }

    .main {
      padding: 20px;
      text-align: left;

      p {
        line-height: 1ch
      }
    }
  }
}

/**shared**/
.banner {
  border-radius: 10px;
}

</style>
