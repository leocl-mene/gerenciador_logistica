<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Dashboard da Secretaria')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

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

    <?php $__env->startPush('scripts'); ?>
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
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Ti\Desktop\gerenciador_logistica\gerenciador_logistica\resources\views/dashboard-secretaria.blade.php ENDPATH**/ ?>