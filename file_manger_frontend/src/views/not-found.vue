<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
      <!-- Main Container -->
      <div class="max-w-md w-full space-y-6 p-8 bg-white rounded-lg shadow-lg">
        <!-- Title -->
        <h1 class="text-4xl font-bold text-center text-red-500">404</h1>
  
        <!-- Subtitle -->
        <p class="text-2xl text-center text-gray-700">
          Page Not Found
        </p>
  
        <!-- Description -->
        <p class="text-center text-gray-500">
          The page you are looking for doesn't exist or has been moved.
        </p>
  
        <div class="flex justify-center">
          <button
            @click="$router.push('/dashboard')"
            class="cursor-pointer px-6 py-2 text-white bg-red-900 hover:bg-red-800 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-300"
          >
            Go Home
          </button>
        </div>
      </div>
    </div>
  </template>

  
 