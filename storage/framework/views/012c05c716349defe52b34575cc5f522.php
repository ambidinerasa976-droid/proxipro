<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'ProxiPro')); ?> - <?php echo $__env->yieldContent('title', 'Authentification'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS compilé via Vite -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    
    <style>
        .fade-in { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <div class="min-h-screen flex">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</body>
</html>
<?php /**PATH C:\Users\PC\Desktop\MASSIWANI V2\resources\views/layouts/auth.blade.php ENDPATH**/ ?>