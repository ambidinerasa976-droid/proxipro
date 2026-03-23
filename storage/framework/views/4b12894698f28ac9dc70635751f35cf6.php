<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo $__env->yieldContent('title', 'ProxiPro'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: linear-gradient(180deg, #2c3e50, #1a252f);
            color: white;
            transition: all 0.3s;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin: 5px 0;
        }
        
        .sidebar-menu a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 10px 20px;
            display: block;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            color: white;
            background: rgba(255,255,255,0.1);
            border-left: 4px solid #3498db;
        }
        
        .stat-card {
            border-radius: 10px;
            border: none;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar.active {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4 class="mb-0">
                <i class="fas fa-crown me-2"></i>Admin Panel
            </h4>
            <small class="text-muted">ProxiPro Platform</small>
        </div>
        
        <ul class="sidebar-menu mt-4">
            <li>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="<?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                    <i class="fas fa-tachometer-alt me-2"></i> Tableau de bord
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.users')); ?>" class="<?php echo e(request()->routeIs('admin.users*') ? 'active' : ''); ?>">
                    <i class="fas fa-users me-2"></i> Utilisateurs
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.ads')); ?>" class="<?php echo e(request()->routeIs('admin.ads*') ? 'active' : ''); ?>">
                    <i class="fas fa-bullhorn me-2"></i> Annonces
                </a>
            </li>
            <li>
                <?php
                    $activeBoostsCount = \App\Models\Ad::where('is_boosted', true)->where('boost_end', '>', now())->count()
                        + \App\Models\Ad::where('is_urgent', true)->where(function($q) { $q->whereNull('urgent_until')->orWhere('urgent_until', '>', now()); })->count();
                ?>
                <a href="<?php echo e(route('admin.boosts')); ?>" class="<?php echo e(request()->routeIs('admin.boosts*') ? 'active' : ''); ?>" style="position: relative;">
                    <i class="fas fa-rocket me-2" style="color: #f59e0b;"></i> Boosts & Urgents
                    <?php if($activeBoostsCount > 0): ?>
                        <span class="badge bg-warning text-dark rounded-pill" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); font-size: 0.7rem;"><?php echo e($activeBoostsCount); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li>
                <?php
                    $pendingVerifCount = \App\Models\IdentityVerification::whereIn('status', ['pending', 'returned'])->count();
                ?>
                <a href="<?php echo e(route('admin.verifications')); ?>" class="<?php echo e(request()->routeIs('admin.verifications*') ? 'active' : ''); ?>" style="position: relative;">
                    <i class="fas fa-shield-alt me-2" style="color: #10b981;"></i> Vérifications
                    <?php if($pendingVerifCount > 0): ?>
                        <span class="badge bg-danger rounded-pill" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); font-size: 0.7rem;"><?php echo e($pendingVerifCount); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.subscriptions')); ?>" class="<?php echo e(request()->routeIs('admin.subscriptions*') ? 'active' : ''); ?>">
                    <i class="fas fa-crown me-2" style="color: #f59e0b;"></i> Abonnements
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.deleted-accounts')); ?>" class="<?php echo e(request()->routeIs('admin.deleted-accounts') ? 'active' : ''); ?>">
                    <i class="fas fa-trash me-2"></i> Comptes supprimés
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.stats')); ?>" class="<?php echo e(request()->routeIs('admin.stats') ? 'active' : ''); ?>">
                    <i class="fas fa-chart-bar me-2"></i> Statistiques
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.advertisements')); ?>" class="<?php echo e(request()->routeIs('admin.advertisements*') ? 'active' : ''); ?>">
                    <i class="fas fa-ad me-2" style="color: #f59e0b;"></i> Publicités
                </a>
            </li>
            <li>
                <?php
                    $pendingReportsCount = \App\Models\Report::where('status', 'pending')->count();
                ?>
                <a href="<?php echo e(route('admin.reports')); ?>" class="<?php echo e(request()->routeIs('admin.reports*') ? 'active' : ''); ?>" style="position: relative;">
                    <i class="fas fa-flag me-2" style="color: #e41e3f;"></i> Signalements
                    <?php if($pendingReportsCount > 0): ?>
                        <span class="badge bg-danger rounded-pill" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); font-size: 0.7rem;"><?php echo e($pendingReportsCount); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.settings')); ?>" class="<?php echo e(request()->routeIs('admin.settings') ? 'active' : ''); ?>">
                    <i class="fas fa-cog me-2"></i> Paramètres
                </a>
            </li>
            <?php if(Auth::user()->email === config('admin.principal_admin.email')): ?>
            <li class="mt-3">
                <a href="<?php echo e(route('admin.admins')); ?>" class="<?php echo e(request()->routeIs('admin.admins*') ? 'active' : ''); ?>" style="border-left: 3px solid #f59e0b;">
                    <i class="fas fa-user-shield me-2" style="color: #f59e0b;"></i> Gestion Admins
                </a>
            </li>
            <?php endif; ?>
            <li class="mt-4">
                <a href="<?php echo e(url('/')); ?>">
                    <i class="fas fa-arrow-left me-2"></i> Retour au site
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                </a>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                    <?php echo csrf_field(); ?>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <nav class="navbar navbar-light bg-white shadow-sm rounded mb-4">
            <div class="container-fluid">
                <button class="btn btn-outline-primary d-md-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="d-flex align-items-center">
                    <span class="navbar-text me-3">
                        <i class="fas fa-user-shield me-2"></i>
                        <?php echo e(Auth::user()->name); ?>

                    </span>
                    <span class="badge bg-success">Administrateur</span>
                </div>
            </div>
        </nav>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Contenu de la page -->
        <div class="container-fluid">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar sur mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\PC\Desktop\MASSIWANI V2\resources\views/admin/layouts/app.blade.php ENDPATH**/ ?>