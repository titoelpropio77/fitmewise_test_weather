<script setup>
import { ref } from 'vue'
import { useWeatherStore } from '../js/stores/useWeatherStore.js'
const city = ref('')
const appUrl = import.meta.env.VITE_APP_URL
const weatherStore = useWeatherStore()
const getWeather = async () => {

  if (!city.value) return

    weatherStore.getWeatherCurrent(city.value)
    weatherStore.getWeatherForecast(city.value)
}
</script>

<template>
  <div class="min-h-screen bg-blue-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6 space-y-6">

      <!-- Formulario -->
      <div>
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Consulta el Clima</h1>
        <div class="flex flex-col sm:flex-row gap-4">
          <input
            v-model="city"
            type="text"
            placeholder="Ej. Madrid, ESP"
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-blue-500"
          />
          <button
            @click="getWeather"
            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition"
          >
            Consultar
          </button>
        </div>
      </div>

      <!-- Clima actual -->
      <div v-if="weatherStore.current" class="bg-blue-50 rounded-lg p-4 shadow flex items-center gap-4">
        <img
          :src="`http://openweathermap.org/img/wn/${weatherStore.current.icon}@2x.png`"
          :alt="weatherStore.current.weather"
          class="w-16 h-16"
        />
        <div>
          <h2 class="text-xl font-semibold text-gray-700">Clima actual</h2>
          <p class="text-gray-600">{{ weatherStore.current.weather }}</p>
          <p class="text-gray-800 font-bold">{{ weatherStore.current.temperature }}</p>
        </div>
      </div>

      <!-- Pronóstico de 5 días -->
      <div v-if="weatherStore.forecast.length" class="mt-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Pronóstico 5 días</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
          <div v-for="day in weatherStore.forecast" :key="day.day" class="bg-white p-4 rounded-lg shadow text-center">
            <p class="font-medium text-gray-700">{{ day.day }}</p>
            <img
              :src="`http://openweathermap.org/img/wn/${day.icon}@2x.png`"
              class="mx-auto w-12 h-12"
              alt="icono clima"
            />
            <p class="text-gray-800 font-semibold">{{ day.temp }}</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>
