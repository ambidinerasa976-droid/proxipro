

<?php $__env->startSection('title', 'Profil de ' . $user->name . ' - ProxiPro'); ?>

<?php $__env->startPush('meta'); ?>
    
    <meta property="og:type" content="profile">
    <meta property="og:title" content="<?php echo e($user->name); ?><?php echo e($user->profession ? ' — ' . $user->profession : ''); ?> | ProxiPro">
    <meta property="og:description" content="<?php echo e($user->bio ? Str::limit($user->bio, 160) : ($user->profession ? $user->profession . ' sur ProxiPro. ' : '') . ($user->city ? 'Basé à ' . $user->city . '. ' : '') . 'Retrouvez ce professionnel sur ProxiPro.'); ?>">
    <?php if($user->avatar): ?>
        <meta property="og:image" content="<?php echo e(asset('storage/' . $user->avatar)); ?>">
    <?php else: ?>
        <meta property="og:image" content="<?php echo e(asset('favicon.ico')); ?>">
    <?php endif; ?>
    <meta property="og:url" content="<?php echo e(route('profile.public', $user->id)); ?>">
    <meta property="og:site_name" content="ProxiPro">
    <meta property="og:locale" content="fr_FR">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo e($user->name); ?><?php echo e($user->profession ? ' — ' . $user->profession : ''); ?> | ProxiPro">
    <meta name="twitter:description" content="<?php echo e($user->bio ? Str::limit($user->bio, 160) : 'Profil professionnel sur ProxiPro.'); ?>">
    <?php if($user->avatar): ?>
        <meta name="twitter:image" content="<?php echo e(asset('storage/' . $user->avatar)); ?>">
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row">
        <!-- Profile Card - Sidebar gauche -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <!-- Avatar en format carte -->
                    <?php if($user->avatar): ?>
                        <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="Avatar" 
                            class="mb-4" style="width: 180px; height: 180px; object-fit: cover; border-radius: 12px;">
                    <?php else: ?>
                        <div class="bg-primary text-white d-inline-flex align-items-center justify-content-center mb-4" 
                            style="width: 180px; height: 180px; font-size: 64px; border-radius: 12px;">
                            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                        </div>
                    <?php endif; ?>
                    
                    <!-- Name + Pro Badge -->
                    <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 mb-1">
                        <h4 class="fw-bold mb-0"><?php echo e($user->name); ?></h4>
                        <?php if($user->hasActiveProSubscription()): ?>
                            <span class="badge" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                                <i class="fas fa-briefcase me-1"></i>Pro
                            </span>
                        <?php elseif($user->user_type === 'professionnel' || $user->hasCompletedProOnboarding()): ?>
                            <span class="badge" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <i class="fas fa-crown me-1"></i>Pro
                            </span>
                        <?php elseif($user->is_service_provider): ?>
                            <span class="badge" style="background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="fas fa-user-check me-1"></i>Prestataire
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Job Title / Profession -->
                    <p class="text-primary fw-semibold mb-1" style="word-break: break-word; overflow-wrap: break-word;">
                        <?php if($user->profession): ?>
                            <i class="fas fa-briefcase me-1"></i><?php echo e(Str::limit($user->profession, 80)); ?>

                        <?php elseif($user->is_service_provider && $user->services && $user->services->count() > 0): ?>
                            <i class="fas fa-briefcase me-1"></i><?php echo e(Str::limit($user->services->first()->subcategory ?? $user->services->first()->category ?? 'Prestataire de services', 80)); ?>

                        <?php endif; ?>
                    </p>
                    
                    
                    <?php if($user->service_category): ?>
                        <p class="text-muted small mb-1" style="word-break: break-word; overflow-wrap: break-word;">
                            <i class="fas fa-th-large me-1"></i><?php echo e(Str::limit($user->service_category, 120)); ?>

                        </p>
                    <?php endif; ?>

                    
                    <?php if($user->hourly_rate && ($user->show_hourly_rate ?? true)): ?>
                        <div class="mb-2">
                            <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #059669; font-size: 0.95rem; font-weight: 700; border: 1px solid #a7f3d0;">
                                <i class="fas fa-euro-sign me-1"></i><?php echo e(number_format((float)$user->hourly_rate, 0, ',', ' ')); ?> €/h
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    
                    <?php if($user->service_subcategories && count($user->service_subcategories) > 0): ?>
                        <?php
                            $filteredSubcats = collect($user->service_subcategories)->filter(fn($s) => $s !== $user->profession);
                        ?>
                        <?php if($filteredSubcats->isNotEmpty()): ?>
                        <div class="d-flex flex-wrap justify-content-center gap-1 mb-2">
                            <?php $__currentLoopData = $filteredSubcats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-light text-primary border px-2 py-1" style="font-size: 0.75rem; max-width: 100%; white-space: normal; word-break: break-word; text-align: center;">
                                    <?php echo e(Str::limit($subcat, 50)); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <!-- Localisation -->
                    <?php if($user->city && $user->country): ?>
                        <p class="text-muted small mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i><?php echo e($user->city); ?>, <?php echo e($user->country); ?>

                        </p>
                    <?php endif; ?>
                    
                    <!-- Bio courte -->
                    <?php if($user->bio): ?>
                        <p class="text-muted small mb-3"><?php echo e(Str::limit($user->bio, 80)); ?></p>
                    <?php endif; ?>
                    
                    <!-- Rating -->
                    <div class="mb-3">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?php echo e($i <= round($ratingAverage ?? 0) ? 'text-warning' : 'text-muted'); ?>"></i>
                        <?php endfor; ?>
                        <span class="ms-2 fw-bold"><?php echo e(number_format($ratingAverage ?? 0, 1)); ?></span>
                        <span class="text-muted">(<?php echo e($ratingCount ?? 0); ?> avis)</span>
                    </div>
                    
                    <!-- Badges -->
                    <div class="mb-4">
                        <?php if($user->hasActiveProSubscription()): ?>
                            <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white;">
                                <i class="fas fa-briefcase me-1"></i>Professionnel
                            </span>
                        <?php elseif($user->user_type === 'professionnel' || $user->hasCompletedProOnboarding()): ?>
                            <span class="badge bg-primary px-3 py-2">
                                <i class="fas fa-briefcase me-1"></i>Professionnel
                            </span>
                        <?php endif; ?>
                        <?php if($user->is_service_provider && $user->service_provider_verified): ?>
                            <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                                <i class="fas fa-user-check me-1"></i>Prestataire vérifié
                            </span>
                        <?php elseif($user->is_verified): ?>
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i>Profil vérifié
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary px-3 py-2" style="opacity: 0.85;">
                                <i class="fas fa-user-times me-1"></i>Profil non vérifié
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Contact Button -->
                    <div class="d-grid gap-2">
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(auth()->id() !== $user->id): ?>
                                <form action="<?php echo e(route('messages.create.conversation')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="recipient_id" value="<?php echo e($user->id); ?>">
                                    <input type="hidden" name="message" value="Bonjour, je souhaite vous contacter.">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-envelope me-2"></i>Contacter
                                    </button>
                                </form>
                            <?php else: ?>
                                <a href="<?php echo e(route('profile.show')); ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-user me-2"></i>Voir mon tableau de bord
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">
                                <i class="fas fa-envelope me-2"></i>Se connecter pour contacter
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Social Share Buttons -->
                    <?php
                        $shareUrl = urlencode(route('profile.public', $user->id));
                        $shareTitle = urlencode($user->name . ($user->profession ? ' — ' . $user->profession : '') . ' | ProxiPro');
                        $shareDesc = urlencode($user->bio ? Str::limit($user->bio, 120) : 'Découvrez ce professionnel sur ProxiPro.');
                    ?>
                    <div class="mt-3 pt-3 border-top">
                        <p class="text-muted small mb-2 text-center"><i class="fas fa-share-alt me-1"></i>Partager ce profil</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e($shareUrl); ?>" target="_blank" rel="noopener"
                               class="btn btn-sm" style="background: #1877f2; color: white; border-radius: 8px; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;"
                               title="Partager sur Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo e($shareUrl); ?>&text=<?php echo e($shareTitle); ?>" target="_blank" rel="noopener"
                               class="btn btn-sm" style="background: #1da1f2; color: white; border-radius: 8px; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;"
                               title="Partager sur Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo e($shareUrl); ?>&title=<?php echo e($shareTitle); ?>&summary=<?php echo e($shareDesc); ?>" target="_blank" rel="noopener"
                               class="btn btn-sm" style="background: #0a66c2; color: white; border-radius: 8px; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;"
                               title="Partager sur LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://wa.me/?text=<?php echo e($shareTitle); ?>%20<?php echo e($shareUrl); ?>" target="_blank" rel="noopener"
                               class="btn btn-sm" style="background: #25d366; color: white; border-radius: 8px; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;"
                               title="Partager sur WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <button onclick="copyProfileLink()" class="btn btn-sm" style="background: #64748b; color: white; border-radius: 8px; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;" title="Copier le lien" id="copyLinkBtn">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                    <script>
                    function copyProfileLink() {
                        navigator.clipboard.writeText('<?php echo e(route('profile.public', $user->id)); ?>').then(() => {
                            const btn = document.getElementById('copyLinkBtn');
                            btn.innerHTML = '<i class="fas fa-check"></i>';
                            btn.style.background = '#16a34a';
                            setTimeout(() => { btn.innerHTML = '<i class="fas fa-link"></i>'; btn.style.background = '#64748b'; }, 2000);
                        });
                    }
                    </script>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <?php if($user->city && $user->country): ?>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-map-marker-alt text-muted me-3" style="width: 20px;"></i>
                            <?php echo e($user->city); ?>, <?php echo e($user->country); ?>

                        </li>
                        <?php elseif($user->location): ?>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-map-marker-alt text-muted me-3" style="width: 20px;"></i>
                            <?php echo e($user->location); ?>

                        </li>
                        <?php endif; ?>
                        <?php if($user->business_type): ?>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-building text-muted me-3" style="width: 20px;"></i>
                            <?php echo e($user->business_type === 'entreprise' ? 'Entreprise' : 'Auto-entrepreneur'); ?>

                        </li>
                        <?php endif; ?>
                        <?php if($user->hourly_rate && ($user->show_hourly_rate ?? true)): ?>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-euro-sign text-success me-3" style="width: 20px;"></i>
                            <span class="fw-semibold"><?php echo e(number_format((float)$user->hourly_rate, 0, ',', ' ')); ?> €/h</span>
                        </li>
                        <?php endif; ?>
                        <?php if($user->years_experience): ?>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-award text-muted me-3" style="width: 20px;"></i>
                            <?php echo e($user->years_experience); ?> an<?php echo e($user->years_experience > 1 ? 's' : ''); ?> d'expérience
                        </li>
                        <?php endif; ?>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-calendar-alt text-muted me-3" style="width: 20px;"></i>
                            Membre depuis <?php echo e($user->created_at->translatedFormat('F Y')); ?>

                        </li>
                    </ul>
                </div>
            </div>

            <!-- Compétences Prestataire -->
            <?php if($user->is_service_provider && $user->services && $user->services->count() > 0): ?>
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0"><i class="fas fa-tools me-2 text-success"></i>Compétences</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <?php $__currentLoopData = $user->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge bg-light text-primary px-3 py-2">
                                <?php echo e($service->subcategory); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <?php if($user->services->first() && $user->services->first()->experience_years): ?>
                    <div class="mt-3 text-muted">
                        <i class="fas fa-history me-1"></i>
                        <?php echo e($user->services->first()->experience_years); ?> années d'expérience
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Stats -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="display-6 fw-bold text-primary"><?php echo e($stats['total_ads'] ?? 0); ?></div>
                            <div class="text-muted">Annonces actives</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="display-6 fw-bold text-warning"><?php echo e(number_format($ratingAverage ?? 0, 1)); ?></div>
                            <div class="text-muted">Note moyenne</div>
                            <div class="mt-2">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?php echo e($i <= round($ratingAverage ?? 0) ? 'text-warning' : 'text-muted'); ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="display-6 fw-bold text-success"><?php echo e($ratingCount ?? 0); ?></div>
                            <div class="text-muted">Avis reçus</div>
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

            <!-- Leave Review Form -->
            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->id() !== $user->id): ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-transparent">
                            <h6 class="mb-0"><i class="fas fa-star me-2 text-warning"></i>Laisser un avis</h6>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo e(route('reviews.store', $user->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label class="form-label">Note</label>
                                    <div class="d-flex gap-2">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <label class="d-flex align-items-center gap-1">
                                                <input type="radio" name="rating" value="<?php echo e($i); ?>" required>
                                                <span><?php echo e($i); ?></span>
                                            </label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Commentaire (optionnel)</label>
                                    <textarea name="comment" class="form-control" rows="3" maxlength="1000" placeholder="Votre expérience..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Publier l'avis
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Connectez-vous pour laisser un avis
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Ads List -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Annonces de <?php echo e($user->name); ?></h6>
                </div>
                <div class="card-body">
                    <?php if($ads->count() > 0): ?>
                        <div class="row g-3">
                            <?php $__currentLoopData = $ads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-light rounded-3 h-100">
                                        <?php if($ad->photos && count($ad->photos) > 0): ?>
                                            <img src="<?php echo e(asset('storage/' . $ad->photos[0])); ?>" alt="" 
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
                                            <div class="small fw-bold text-primary mt-1">
                                                <?php echo e(number_format($ad->price, 0, ',', ' ')); ?> €
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4">
                            <?php echo e($ads->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-bullhorn fa-3x mb-3 opacity-50"></i>
                            <p class="mb-0">Aucune annonce active pour le moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Reviews -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0"><i class="fas fa-comments me-2"></i>Avis sur <?php echo e($user->name); ?></h6>
                </div>
                <div class="card-body">
                    <?php if(isset($reviews) && $reviews->count() > 0): ?>
                        <div class="d-flex flex-column gap-3">
                            <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="p-3 bg-light rounded-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="fw-semibold">
                                            <a href="<?php echo e($review->reviewer ? route('profile.public', $review->reviewer_id) : '#'); ?>" class="text-decoration-none">
                                                <?php echo e($review->reviewer?->name ?? 'Utilisateur'); ?>

                                            </a>
                                        </div>
                                        <small class="text-muted"><?php echo e($review->created_at->diffForHumans()); ?></small>
                                    </div>
                                    <div class="mb-2">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?php echo e($i <= $review->rating ? 'text-warning' : 'text-muted'); ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <?php if($review->comment): ?>
                                        <div><?php echo e($review->comment); ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-star-half-alt fa-2x mb-2 opacity-50"></i>
                            <p class="mb-0">Aucun avis pour le moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Desktop\MASSIWANI V2\resources\views/profile/public.blade.php ENDPATH**/ ?>