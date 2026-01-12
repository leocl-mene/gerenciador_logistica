<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard da Secretaria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">Motoristas Ativos em Tempo Real</h3>

                    <div id="map" class="mt-4" style="height: 500px; border-radius: 8px;"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let map;
        const motoristaMarkers = new Map();

        function formatUltimoUpdate(isoString) {
            if (!isoString) return 'Sem atualizacao';
            const date = new Date(isoString);
            if (Number.isNaN(date.getTime())) return 'Sem atualizacao';
            const hh = String(date.getHours()).padStart(2, '0');
            const mm = String(date.getMinutes()).padStart(2, '0');
            return `${hh}:${mm}`;
        }

        function getMarkerIcon(isOnline) {
            const color = isOnline ? '#22c55e' : '#9ca3af';
            return {
                path: google.maps.SymbolPath.CIRCLE,
                fillColor: color,
                fillOpacity: 1,
                strokeColor: '#111827',
                strokeWeight: 2,
                scale: 8,
            };
        }

        function buildInfoContent(motorista) {
            const status = motorista.status_online ? 'Online' : 'Offline';
            const ultimo = formatUltimoUpdate(motorista.ultimo_update);
            return `
                <div style="background:#111;color:#fff;padding:8px 10px;border-radius:8px;font-weight:600;box-shadow:0 2px 6px rgba(0,0,0,.3);">
                    <div>${motorista.name}</div>
                    <div style="font-size:12px;font-weight:500;margin-top:2px;opacity:.85;">${status} · ${ultimo}</div>
                </div>
            `;
        }

        async function fetchMotoristas() {
            try {
                const response = await fetch('/api/motoboys-online');
                const motoristas = await response.json();
                const seen = new Set();

                motoristas.forEach(motorista => {
                    const lat = parseFloat(motorista.ultima_latitude);
                    const lng = parseFloat(motorista.ultima_longitude);
                    if (Number.isNaN(lat) || Number.isNaN(lng)) return;

                    const position = { lat, lng };
                    seen.add(motorista.id);
                    const isOnline = Boolean(motorista.status_online);

                    if (motoristaMarkers.has(motorista.id)) {
                        const entry = motoristaMarkers.get(motorista.id);
                        entry.marker.setPosition(position);
                        entry.marker.setIcon(getMarkerIcon(isOnline));
                        entry.infoWindow.setContent(buildInfoContent(motorista));
                    } else {
                        const marker = new google.maps.Marker({
                            position,
                            map,
                            title: motorista.name,
                            icon: getMarkerIcon(isOnline),
                        });
                        const infoWindow = new google.maps.InfoWindow({
                            content: buildInfoContent(motorista),
                        });
                        marker.addListener('click', () => {
                            infoWindow.open({ anchor: marker, map, shouldFocus: false });
                        });
                        motoristaMarkers.set(motorista.id, { marker, infoWindow });
                    }
                });

                motoristaMarkers.forEach((entry, id) => {
                    if (!seen.has(id)) {
                        entry.marker.setMap(null);
                        motoristaMarkers.delete(id);
                    }
                });
            } catch (error) {
                console.error('Erro ao buscar a localizacao dos motoristas:', error);
            }
        }

        function initMap() {
            const center = { lat: -3.1190, lng: -60.0217 };
            map = new google.maps.Map(document.getElementById('map'), {
                center,
                zoom: 13,
            });

            fetchMotoristas();
            setInterval(fetchMotoristas, 3000);
        }

        window.initMap = initMap;
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap" async defer></script>
    @endpush

</x-app-layout>
