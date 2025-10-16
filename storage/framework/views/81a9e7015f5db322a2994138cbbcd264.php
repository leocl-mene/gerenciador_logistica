<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            
            
            <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-end z-10">
                <?php if(auth()->guard()->check()): ?>
                    
                    <a href="<?php echo e(route('dashboard.secretaria')); ?>" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                        Dashboard
                    </a>
                <?php else: ?>
                    
                    <a href="<?php echo e(route('login')); ?>" class="font-semibold text-gray-300 hover:text-white dark:text-gray-300 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                        Log in
                    </a>

                    
                    <?php if(Route::has('register')): ?>
                        <a href="<?php echo e(route('register')); ?>" class="ms-4 font-semibold text-gray-300 hover:text-white dark:text-gray-300 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            Register
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            
            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex justify-center">
                    
                </div>

                <div class="mt-16">
                   
                </div>

                <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
                    <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-start">
                        <div class="flex items-center gap-4">
                            <a href="https://github.com/sponsors/taylorotwell" class="group inline-flex items-center hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="-mt-px me-1 w-5 h-5 stroke-gray-400 dark:stroke-gray-600 group-hover:stroke-gray-600 dark:group-hover:stroke-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                                Sponsor
                            </a>
                        </div>
                    </div>

                    <div class="ms-4 text-center text-sm text-gray-500 dark:text-gray-400 sm:text-end sm:ms-0">
                        Laravel v<?php echo e(Illuminate\Foundation\Application::VERSION); ?> (PHP v<?php echo e(PHP_VERSION); ?>)
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<?php /**PATH C:\Users\Ti\Desktop\gerenciador_logistica\gerenciador_logistica\resources\views/welcome.blade.php ENDPATH**/ ?>