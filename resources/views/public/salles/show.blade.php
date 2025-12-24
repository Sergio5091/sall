@extends('app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold">GB</span>
                        </div>
                        <span class="font-bold text-xl">GameBook</span>
                    </a>
                </div>
                <nav class="hidden md:flex space-x-8">
                    <a href="/" class="text-gray-700 hover:text-blue-600">Accueil</a>
                    <a href="/search/rooms" class="text-blue-600 font-medium">Salles</a>
                    <a href="/events" class="text-gray-700 hover:text-blue-600">Événements</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <a href="/login" class="text-gray-700 hover:text-blue-600">Connexion</a>
                    <a href="/register" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Inscription</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Room Details -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Image Gallery -->
            <div class="relative h-96 bg-gray-200">
                <img src="{{ $salle->image ?? 'https://picsum.photos/seed/room-' . $salle->id . '/1200/400.jpg' }}" 
                     alt="{{ $salle->nom }}" 
                     class="w-full h-full object-cover">
                
                <!-- Back Button -->
                <div class="absolute top-4 left-4">
                    <a href="/search/rooms" class="bg-white/90 backdrop-blur-sm p-2 rounded-lg hover:bg-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 lg:p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Info -->
                    <div class="lg:col-span-2">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $salle->nom }}</h1>
                        
                        <div class="flex items-center gap-4 text-gray-600 mb-6">
                            <div class="flex items-center gap-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $salle->adresse }}, {{ $salle->ville }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>{{ $salle->capacite_max ?? 10 }} personnes</span>
                            </div>
                        </div>

                        @if($salle->description)
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-3">Description</h2>
                            <p class="text-gray-600 leading-relaxed">{{ $salle->description }}</p>
                        </div>
                        @endif

                        <!-- Features -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-3">Équipements</h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="flex items-center gap-2 text-gray-600">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>WiFi</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Climatisation</span>
                                </div>
                                @if($salle->parking)
                                <div class="flex items-center gap-2 text-gray-600">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Parking</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Location Map -->
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-3">Localisation</h2>
                            <div class="bg-gray-200 rounded-lg h-64 flex items-center justify-center">
                                <p class="text-gray-500">Carte non disponible</p>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 rounded-xl p-6 sticky top-6">
                            <div class="text-center mb-6">
                                <div class="text-3xl font-bold text-blue-600">
                                    @if($salle->prix_heure)
                                        {{ number_format($salle->prix_heure, 0, ',', ' ') }} XOF
                                    @else
                                        Gratuit
                                    @endif
                                </div>
                                <div class="text-gray-600">par heure</div>
                            </div>

                            <div class="space-y-3">
                                <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                    Réserver maintenant
                                </button>
                                <button class="w-full border border-gray-300 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                                    Contacter le propriétaire
                                </button>
                            </div>

                            <!-- Contact Info -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h3 class="font-semibold text-gray-900 mb-3">Informations de contact</h3>
                                <div class="space-y-2 text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1zia.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span>Téléphone non disponible</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>Email non disponible</span>
                                    </div>
                                </div>
                            </div>
确                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
