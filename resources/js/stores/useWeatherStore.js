import { defineStore } from 'pinia'
import { ref } from 'vue'
export const useWeatherStore = defineStore('weather', () => {
    const current = ref(null)
    const forecast = ref([])

    function getWeatherCurrent(city) {
        fetch(`/api/weather/current?city=${encodeURIComponent(city)}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(res => {
                const { data } = res

                current.value = {
                    temperature: data.temperature + '°C',
                    weather: data.weather,
                    icon: data.icon,
                }
            })
            .catch(error => {
                console.error('Error fetching weather data:', error);
            });
    }
    function getWeatherForecast(city) {
        fetch(`/api/weather/forecast?city=${encodeURIComponent(city)}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(res => {
                const { data } = res
                forecast.value = data.forecast.map(item => {
                    const date = new Date(item.date);
                    const dayWeek = date.toLocaleDateString('es-ES', { weekday: 'long' });
                    return {
                        day: capitalizeFirstLetter(dayWeek), // Ej: "Martes"
                        temp: Math.round(item.temperature) + '°C',
                        icon: item.icon
                    };
                });
            })
            .catch(error => {
                console.error('Error fetching weather data:', error);
            });
    }

    // Utilidad para poner en mayúscula la primera letra
    function capitalizeFirstLetter(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    return {  getWeatherCurrent, getWeatherForecast , current, forecast }
})
