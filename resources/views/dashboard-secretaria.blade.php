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
                    <h3 class="text-lg font-medium">Motoboys Ativos em Tempo Real</h3>

                    <div id="map" class="mt-4" style="height: 500px; border-radius: 8px;"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Inicializa o mapa centralizado em Manaus
            const map = L.map('map').setView([-3.1190, -60.0217], 13);

            // 2. Adiciona a camada de mapa do OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // 3. Cria um grupo de marcadores para poder limpar e redesenhar
            let motoboyMarkers = L.layerGroup().addTo(map);

            // 4. Função para buscar e desenhar os motoboys no mapa
            async function fetchMotoboys() {
                try {
                    const response = await fetch('/api/motoboys-online');
                    const motoboys = await response.json();

                    // Limpa os marcadores antigos
                    motoboyMarkers.clearLayers();

                    // Adiciona um novo marcador para cada motoboy online
                    motoboys.forEach(motoboy => {
                        const lat = motoboy.ultima_latitude;
                        const lon = motoboy.ultima_longitude;

                        const marker = L.marker([lat, lon]).addTo(motoboyMarkers);
                        marker.bindPopup(`<b>${motoboy.name}</b>`);
                    });

                } catch (error) {
                    console.error('Erro ao buscar a localização dos motoboys:', error);
                }
            }

            // 5. Busca os motoboys imediatamente ao carregar a página...
            fetchMotoboys();
            // ...e depois busca a cada 10 segundos para atualizar as posições.
            setInterval(fetchMotoboys, 10000); 
        });
    </script>
    @endpush
</x-app-layout>
