

<?php $__env->startSection('title', 'Mon Profil - ProxiPro'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <!-- Avatar -->
                    <?php if($user->avatar): ?>
                            <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="Avatar" 
                                class="mb-4" style="width: 180px; height: 180px; object-fit: cover; border-radius: 12px;">
                    <?php else: ?>
                            <div class="bg-primary text-white d-inline-flex align-items-center justify-content-center mb-4" 
                                style="width: 180px; height: 180px; font-size: 64px; border-radius: 12px;">
                            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                        </div>
                    <?php endif; ?>
                    
                    <h4 class="fw-bold mb-1"><?php echo e($user->name); ?></h4>
                    
                    
                    <?php if($user->profession): ?>
                        <p class="text-primary fw-semibold mb-1">
                            <i class="fas fa-briefcase me-1"></i><?php echo e($user->profession); ?>

                        </p>
                    <?php endif; ?>
                    
                    
                    <?php if($user->service_category): ?>
                        <p class="text-muted small mb-1">
                            <i class="fas fa-th-large me-1"></i><?php echo e($user->service_category); ?>

                        </p>
                    <?php endif; ?>
                    
                    
                    <?php if($user->service_subcategories && count($user->service_subcategories) > 0): ?>
                        <div class="d-flex flex-wrap justify-content-center gap-1 mb-2">
                            <?php $__currentLoopData = $user->service_subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-light text-primary border px-2 py-1" style="font-size: 0.75rem;">
                                    <?php echo e($subcat); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                    
                    
                    <?php if($user->city && $user->country): ?>
                        <p class="text-muted small mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i><?php echo e($user->city); ?>, <?php echo e($user->country); ?>

                        </p>
                    <?php endif; ?>
                    
                    <p class="text-muted mb-3"><?php echo e($user->email); ?></p>
                    
                    <!-- Badges -->
                    <div class="mb-4">
                        <?php if($user->hasActiveProSubscription()): ?>
                            <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white;">
                                <i class="fas fa-briefcase me-1"></i>Professionnel
                            </span>
                        <?php elseif($user->user_type === 'professionnel' || $user->isProfessionnel()): ?>
                            <span class="badge bg-primary px-3 py-2">
                                <i class="fas fa-briefcase me-1"></i>Entrepreneur
                            </span>
                        <?php endif; ?>
                        
                        
                        <?php if($user->is_service_provider && $user->service_provider_verified): ?>
                            
                            <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                                <i class="fas fa-user-check me-1"></i>Prestataire particulier vérifié
                            </span>
                        <?php elseif($user->is_verified): ?>
                            
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i>Profil vérifié
                            </span>
                        <?php else: ?>
                            
                            <button type="button" class="badge bg-secondary px-3 py-2 border-0" data-bs-toggle="modal" data-bs-target="#verifyProfileModal" style="cursor: pointer;">
                                <i class="fas fa-shield-alt me-1"></i>Vérifier mon profil
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Points -->
                    <div class="bg-light rounded-3 p-3 mb-4">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <i class="fas fa-coins text-warning fa-lg"></i>
                            <span class="fs-4 fw-bold"><?php echo e(number_format($user->available_points ?? 0, 0, ',', ' ')); ?></span>
                            <span class="text-muted">points</span>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier le profil
                        </a>
                        <a href="<?php echo e(route('settings.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-cog me-2"></i>Paramètres
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Compétences Prestataire -->
            <?php if($user->is_service_provider && $user->services && $user->services->count() > 0): ?>
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0"><i class="fas fa-tools me-2 text-success"></i>Mes autres compétences</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <?php $__currentLoopData = $user->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="competence-badge">
                                <?php echo e($service->subcategory); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php if($user->services->first() && $user->services->first()->description): ?>
                    <div class="mt-3 p-3 bg-light rounded">
                        <small class="text-muted d-block mb-1"><i class="fas fa-quote-left me-1"></i>Description</small>
                        <p class="mb-0"><?php echo e($user->services->first()->description); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($user->service_provider_since): ?>
                    <div class="mt-3 text-muted small">
                        <i class="fas fa-calendar-check me-1"></i>
                        Prestataire depuis <?php echo e($user->service_provider_since->format('F Y')); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Info Card -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <?php if($user->phone): ?>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-phone text-muted me-3" style="width: 20px;"></i>
                            <?php echo e($user->phone); ?>

                        </li>
                        <?php endif; ?>
                        <?php if($user->location): ?>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-map-marker-alt text-muted me-3" style="width: 20px;"></i>
                            <?php echo e($user->location); ?>

                        </li>
                        <?php endif; ?>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-calendar-alt text-muted me-3" style="width: 20px;"></i>
                            Membre depuis <?php echo e($user->created_at->format('M Y')); ?>

                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Stats -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="display-6 fw-bold text-primary"><?php echo e($stats['total_ads']); ?></div>
                            <div class="text-muted">Annonces publiées</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="display-6 fw-bold text-success"><?php echo e($stats['active_ads']); ?></div>
                            <div class="text-muted">Annonces actives</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="display-6 fw-bold text-info"><?php echo e(number_format($stats['total_views'], 0, ',', ' ')); ?></div>
                            <div class="text-muted">Vues totales</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bio -->
            <?php if($user->bio): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0"><i class="fas fa-quote-left me-2"></i>À propos</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?php echo e($user->bio); ?></p>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Recent Ads -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Mes dernières annonces</h6>
                    <a href="<?php echo e(route('ads.index')); ?>?user=<?php echo e($user->id); ?>" class="btn btn-sm btn-outline-primary">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    <?php if($ads->count() > 0): ?>
                        <div class="row g-3">
                            <?php $__currentLoopData = $ads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                        <?php if($ad->images && count($ad->images) > 0): ?>
                                            <img src="<?php echo e(asset('storage/' . $ad->images[0])); ?>" alt="" 
                                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-image text-white"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="flex-grow-1 min-width-0">
                                            <h6 class="mb-1 text-truncate">
                                                <a href="<?php echo e(route('ads.show', $ad)); ?>" class="text-decoration-none text-dark">
                                                    <?php echo e($ad->title); ?>

                                                </a>
                                            </h6>
                                            <div class="small text-muted">
                                                <?php echo e($ad->created_at->diffForHumans()); ?>

                                            </div>
                                        </div>
                                        <div class="ms-2">
                                            <span class="badge <?php echo e($ad->status == 'active' ? 'bg-success' : 'bg-secondary'); ?>">
                                                <?php echo e($ad->status == 'active' ? 'Actif' : 'Inactif'); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-bullhorn fa-3x mb-3 opacity-50"></i>
                            <p class="mb-3">Vous n'avez pas encore publié d'annonce</p>
                            <a href="<?php echo e(route('ads.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Publier une annonce
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Desktop\MASSIWANI V2\resources\views/profile/show.blade.php ENDPATH**/ ?>