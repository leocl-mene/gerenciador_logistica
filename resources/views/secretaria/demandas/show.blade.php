<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detalhes da Demanda: {{ $demanda->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- COLUNA ESQUERDA: INFORMACOES GERAIS --}}
            <div class="md:col-span-2 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    Informacoes Gerais
                </h3>

                <p><strong>Status:</strong> {{ $demanda->status }}</p>
                <p><strong>Tipo:</strong> {{ $demanda->tipo ?? 'Normal' }}</p>

                {{-- Quem criou --}}
                @if ($demanda->secretaria)
                    <p>
                        <strong>Criada pela Secretaria:</strong>
                        {{ $demanda->secretaria->name }}
                        em {{ $demanda->created_at->format('d/m/Y H:i') }}
                    </p>
                @elseif ($demanda->tipo === 'urgente' && $demanda->motoboy)
                    <p>
                        <strong>Criada como Urgente pelo Motorista:</strong>
                        {{ $demanda->motoboy->name }}
                        em {{ $demanda->created_at->format('d/m/Y H:i') }}
                    </p>
                @endif

                @if($demanda->veiculo)
                    <p>
                        <strong>Veiculo Utilizado:</strong>
                        {{ $demanda->veiculo->modelo }} ({{ $demanda->veiculo->placa }})
                    </p>
                @endif

                <hr class="my-3 border-gray-700">

                @if($demanda->motoboy)
                    <p><strong>Motorista:</strong> {{ $demanda->motoboy->name }}</p>
                    <p>
                        <strong>Aceita em:</strong>
                        {{ $demanda->data_aceite ? \Carbon\Carbon::parse($demanda->data_aceite)->format('d/m/Y H:i') : 'N/A' }}
                    </p>

                    {{-- KM --}}
                    @if($demanda->km_inicial)
                        <p class="mt-3">
                            <strong>KM Inicial Digitado:</strong>
                            {{ number_format($demanda->km_inicial, 0, ',', '.') }} km
                        </p>
                    @endif

                    @if($demanda->km_final)
                        <p>
                            <strong>KM Final Digitado:</strong>
                            {{ number_format($demanda->km_final, 0, ',', '.') }} km
                        </p>
                        <p class="font-bold text-lg text-indigo-500 dark:text-indigo-400">
                            <strong>KM Rodado (Calculado):</strong>
                            {{ number_format($demanda->km_final - $demanda->km_inicial, 0, ',', '.') }} km
                        </p>
                    @endif
                @endif

                @if($demanda->descricao)
                    <p class="mt-4">
                        <strong>Descricao:</strong><br>
                        {{ $demanda->descricao }}
                    </p>
                @endif

                <hr class="my-6 border-gray-600">

                {{-- RASTREAMENTO GPS (SUBSTITUI O PERCURSO ANTIGO) --}}
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    Rastreamento por GPS
                </h3>

                @php
                    $totalPontos = $demanda->gpsTracks->count();
                @endphp

                @if($totalPontos > 0)
                    <p class="mb-2">
                        <strong>Pontos registrados:</strong> {{ $totalPontos }}
                    </p>

                    @php
                        $primeiro = $demanda->gpsTracks->first();
                        $ultimo   = $demanda->gpsTracks->last();
                    @endphp

                    <div class="space-y-2 text-sm">
                        <p>
                            <strong>Inicio da Rota:</strong><br>
                            {{ \Carbon\Carbon::parse($primeiro->recorded_at)->format('d/m/Y H:i:s') }}<br>
                            Lat: {{ $primeiro->latitude }} | Lon: {{ $primeiro->longitude }}
                        </p>

                        <p>
                            <strong>Fim da Rota:</strong><br>
                            {{ \Carbon\Carbon::parse($ultimo->recorded_at)->format('d/m/Y H:i:s') }}<br>
                            Lat: {{ $ultimo->latitude }} | Lon: {{ $ultimo->longitude }}
                        </p>
                    </div>

                    <div class="mt-4 space-y-4">
                        <div id="gps-map" class="w-full h-80 rounded-lg border border-gray-200 dark:border-gray-700"></div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                                Paradas acima de 8 min
                            </h4>
                            <ul id="stops-list" class="mt-2 space-y-2"></ul>
                        </div>
                    </div>

                    <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                        Para ver o detalhamento completo da rota (ponto a ponto), use o relatorio em Excel.
                    </p>
                @else
                    <p class="text-gray-500 dark:text-gray-400">
                        Nenhuma trilha de GPS foi registrada para esta demanda.
                    </p>
                @endif
            </div>

            {{-- COLUNA DIREITA: FOTOS KM --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 h-fit">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    Fotos do Odometro
                </h3>

                @if($demanda->fotosKm)
                    <div>
                        <p class="font-semibold">KM Inicial:</p>
                        @if($demanda->fotosKm->foto_url_inicio)
                            <a href="{{ $demanda->fotosKm->foto_url_inicio }}" target="_blank">
                                <img src="{{ $demanda->fotosKm->foto_url_inicio }}"
                                     alt="Foto KM Inicial"
                                     class="mt-2 rounded-lg w-full">
                            </a>
                        @else
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Sem foto de KM inicial.
                            </p>
                        @endif
                    </div>

                    @if($demanda->fotosKm->foto_url_final)
                        <div class="mt-4">
                            <p class="font-semibold">KM Final:</p>
                            <a href="{{ $demanda->fotosKm->foto_url_final }}" target="_blank">
                                <img src="{{ $demanda->fotosKm->foto_url_final }}"
                                     alt="Foto KM Final"
                                     class="mt-2 rounded-lg w-full">
                            </a>
                        </div>
                    @else
                        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            Aguardando foto do KM final.
                        </p>
                    @endif
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Nenhuma foto de KM enviada para esta demanda.
                    </p>
                @endif
            </div>

        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
            <a href="{{ route('demandas.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md
                      font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500
                      active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300
                      disabled:opacity-25 transition ease-in-out duration-150">
                Voltar para a Lista
            </a>
        </div>
    </div>
    @if($totalPontos > 0)
        @push('scripts')
        <script>
            const gpsTracks = @json($demanda->gpsTracks->map(function ($t) {
                return [
                    'lat' => (float) $t->latitude,
                    'lng' => (float) $t->longitude,
                    'recorded_at' => \Carbon\Carbon::parse($t->recorded_at)->toIso8601String(),
                ];
            }));

            function toRad(value) {
                return (value * Math.PI) / 180;
            }

            function distanceMeters(a, b) {
                const R = 6371000;
                const dLat = toRad(b.lat - a.lat);
                const dLng = toRad(b.lng - a.lng);
                const lat1 = toRad(a.lat);
                const lat2 = toRad(b.lat);
                const sinDLat = Math.sin(dLat / 2);
                const sinDLng = Math.sin(dLng / 2);
                const h = sinDLat * sinDLat + Math.cos(lat1) * Math.cos(lat2) * sinDLng * sinDLng;
                return 2 * R * Math.asin(Math.sqrt(h));
            }

            function computeStops(points, maxDistanceMeters, minMinutes) {
                if (!points.length) return [];
                const stops = [];
                let startIndex = 0;

                for (let i = 1; i < points.length; i++) {
                    const dist = distanceMeters(points[startIndex], points[i]);
                    if (dist > maxDistanceMeters) {
                        const startTime = new Date(points[startIndex].recorded_at);
                        const endTime = new Date(points[i - 1].recorded_at);
                        const diffMinutes = (endTime - startTime) / 60000;
                        if (diffMinutes >= minMinutes) {
                            stops.push({
                                lat: points[startIndex].lat,
                                lng: points[startIndex].lng,
                                start: startTime,
                                end: endTime,
                                minutes: Math.round(diffMinutes),
                            });
                        }
                        startIndex = i;
                    }
                }

                const lastStart = new Date(points[startIndex].recorded_at);
                const lastEnd = new Date(points[points.length - 1].recorded_at);
                const lastMinutes = (lastEnd - lastStart) / 60000;
                if (lastMinutes >= minMinutes) {
                    stops.push({
                        lat: points[startIndex].lat,
                        lng: points[startIndex].lng,
                        start: lastStart,
                        end: lastEnd,
                        minutes: Math.round(lastMinutes),
                    });
                }

                return stops;
            }

            function formatTime(date) {
                const hh = String(date.getHours()).padStart(2, '0');
                const mm = String(date.getMinutes()).padStart(2, '0');
                return `${hh}:${mm}`;
            }

            function initGpsMap() {
                if (!gpsTracks.length) return;

                const map = new google.maps.Map(document.getElementById('gps-map'), {
                    center: { lat: gpsTracks[0].lat, lng: gpsTracks[0].lng },
                    zoom: 14,
                });

                const path = gpsTracks.map((p) => ({ lat: p.lat, lng: p.lng }));
                const routeLine = new google.maps.Polyline({
                    path,
                    geodesic: true,
                    strokeColor: '#2563eb',
                    strokeOpacity: 0.9,
                    strokeWeight: 4,
                });
                routeLine.setMap(map);

                const bounds = new google.maps.LatLngBounds();
                path.forEach((p) => bounds.extend(p));
                map.fitBounds(bounds);

                const stops = computeStops(gpsTracks, 10, 8);
                const list = document.getElementById('stops-list');

                if (!stops.length) {
                    list.innerHTML = '<li class="text-gray-500">Nenhuma parada longa detectada.</li>';
                    return;
                }

                stops.forEach((stop, index) => {
                    const marker = new google.maps.Marker({
                        position: { lat: stop.lat, lng: stop.lng },
                        map,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            fillColor: '#f97316',
                            fillOpacity: 1,
                            strokeColor: '#111827',
                            strokeWeight: 2,
                            scale: 7,
                        },
                    });

                    const infoWindow = new google.maps.InfoWindow({
                        content: `<div style="font-weight:600;">Parada ${index + 1}</div>
                                  <div style="font-size:12px;">${stop.minutes} min - ${formatTime(stop.start)} - ${formatTime(stop.end)}</div>`,
                    });

                    marker.addListener('click', () => {
                        infoWindow.open({ anchor: marker, map, shouldFocus: false });
                    });

                    const item = document.createElement('li');
                    item.innerHTML = `
                        <button type="button"
                                class="w-full text-left rounded bg-gray-100 dark:bg-gray-700 px-3 py-2 hover:bg-gray-200 dark:hover:bg-gray-600">
                            <div class="font-semibold">Parada ${index + 1}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-300">${stop.minutes} min - ${formatTime(stop.start)} - ${formatTime(stop.end)}</div>
                        </button>
                    `;
                    item.querySelector('button').addEventListener('click', () => {
                        map.panTo({ lat: stop.lat, lng: stop.lng });
                        map.setZoom(16);
                        infoWindow.open({ anchor: marker, map, shouldFocus: false });
                    });
                    list.appendChild(item);
                });
            }

            window.initGpsMap = initGpsMap;
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initGpsMap" async defer></script>
        @endpush
    @endif

</x-app-layout>
