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

                    if (motoristaMarkers.has(motorista.id)) {
                        const entry = motoristaMarkers.get(motorista.id);
                        entry.marker.setPosition(position);
                        entry.infoWindow.setContent(
                            `<div style="background:#111;color:#fff;padding:6px 10px;border-radius:6px;font-weight:600;box-shadow:0 2px 6px rgba(0,0,0,.3);">${motorista.name}</div>`
                        );
                    } else {
                        const marker = new google.maps.Marker({
                            position,
                            map,
                            title: motorista.name,
                        });
                        const infoWindow = new google.maps.InfoWindow({
                            content: `<div style="background:#111;color:#fff;padding:6px 10px;border-radius:6px;font-weight:600;box-shadow:0 2px 6px rgba(0,0,0,.3);">${motorista.name}</div>`,
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
