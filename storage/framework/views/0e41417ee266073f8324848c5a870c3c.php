

<?php $__env->startSection('title', 'Annonces - ProxiPro'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    * { font-family: 'Poppins', sans-serif; }
    
    .search-hero { background: #f8f9fa; padding: 20px 0; border-bottom: 1px solid #e9ecef; }
    .search-box { background: white; border-radius: 12px; padding: 15px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
    .form-control-search { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 15px; font-size: 0.95rem; color: #1e293b; }
    .form-control-search:focus { background: white; box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1); border-color: #7c3aed; }
    .btn-search { background: #7c3aed; color: white; border-radius: 8px; padding: 10px 20px; font-weight: 500; font-size: 0.95rem; }
    .btn-search:hover { background: #6d28d9; color: white; }
    
    .content-container { max-width: 1400px; margin: 0 auto; padding: 20px; }
    
    /* Removed sidebar styles */
    .filter-title { color: #2d3748; font-weight: 600; margin-bottom: 20px; }
    .filter-group { margin-bottom: 20px; }
    .filter-label { color: #718096; font-size: 0.9rem; margin-bottom: 8px; }
    .form-control-filter, .form-select-filter { background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 10px; color: #2d3748; padding: 10px 15px; }
    .form-control-filter:focus, .form-select-filter:focus { background: white; border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124, 58, 237,0.15); color: #2d3748; }
    .form-select-filter option { background: white; color: #2d3748; }
    
    .category-chip { display: inline-block; padding: 8px 16px; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 25px; color: #4a5568; font-size: 0.85rem; margin: 3px; cursor: pointer; transition: all 0.3s; text-decoration: none; }
    .category-chip:hover, .category-chip.active { background: #7c3aed; border-color: #7c3aed; color: white; }
    
    .results-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
    .results-count { color: #2d3748; font-size: 1.1rem; }
    .results-count strong { color: #7c3aed; }
    
    .ad-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border-radius: 18px; border: 1px solid rgba(0,0,0,0.05); overflow: hidden; transition: all 0.3s; height: 100%; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
    .ad-card:hover { transform: translateY(-5px); border-color: rgba(124, 58, 237,0.3); box-shadow: 0 15px 40px rgba(124, 58, 237,0.15); }
    .ad-card-image { height: 160px; background: linear-gradient(135deg, #7c3aed 0%, #9333ea 100%); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; }
    .ad-card-image i { font-size: 50px; color: rgba(255,255,255,0.3); }
    .ad-card-image img { width: 100%; height: 100%; object-fit: cover; }
    .ad-badge { position: absolute; top: 12px; left: 12px; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
    .ad-badge-offre { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
    .ad-badge-demande { background: linear-gradient(135deg, #17a2b8, #6f42c1); color: white; }
    .ad-badge-boosted { position: absolute; top: 12px; right: 12px; background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
    .ad-badge-urgent { position: absolute; top: 12px; right: 12px; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; animation: urgentPulse 2s ease-in-out infinite; }
    @keyframes urgentPulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
    .ad-card-body { padding: 20px; }
    .ad-card-category { display: inline-block; background: rgba(124, 58, 237,0.1); color: #7c3aed; padding: 4px 10px; border-radius: 15px; font-size: 0.75rem; font-weight: 500; margin-bottom: 10px; }
    .ad-card-title { color: #2d3748; font-weight: 600; font-size: 1rem; margin-bottom: 8px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .ad-card-location { color: #718096; font-size: 0.85rem; margin-bottom: 10px; }
    .ad-card-price { color: #28a745; font-weight: 700; font-size: 1.1rem; }
    .ad-card-footer { padding: 15px 20px; border-top: 1px solid rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
    .ad-card-user { color: #a0aec0; font-size: 0.8rem; }
    .btn-view { background: linear-gradient(135deg, #7c3aed, #9333ea); color: white; border: none; border-radius: 10px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; }
    .btn-view:hover { color: white; box-shadow: 0 5px 15px rgba(124, 58, 237,0.4); }
    
    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state i { font-size: 80px; color: #cbd5e0; margin-bottom: 20px; }
    .empty-state h4 { color: #2d3748; margin-bottom: 10px; }
    .empty-state p { color: #718096; }
    
    .pagination { gap: 5px; }
    .page-link { background: #f7fafc; border: 1px solid #e2e8f0; color: #4a5568; border-radius: 10px !important; padding: 10px 15px; }
    .page-link:hover { background: white; color: #7c3aed; }
    .page-item.active .page-link { background: #7c3aed; border-color: #7c3aed; color: white; }
    
    @media (max-width: 992px) {
        .filters-sidebar { position: static; margin-bottom: 25px; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Search Section -->
    <div class="search-hero">
        <div class="container">
            <div class="search-box mx-auto" style="max-width: 1000px;">
                <form method="GET" action="<?php echo e(route('ads.index')); ?>">
                    <div class="row g-2 align-items-center">
                        <div class="col-lg-5">
                            <input type="text" class="form-control form-control-search" name="q" value="<?php echo e(request('q')); ?>" placeholder="🔍 Rechercher un service...">
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-search" name="location" value="<?php echo e(request('location')); ?>" placeholder="📍 Ville ou code postal">
                                <button type="button" class="btn btn-outline-secondary bg-white border-start-0" id="detectLocation" title="Ma position" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; border: 1px solid #e2e8f0;">
                                    <i class="fas fa-location-arrow text-secondary"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-search w-100"><i class="fas fa-search me-2"></i>Rechercher</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-container">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-12 mb-4 d-none d-lg-block">
                 <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <a href="<?php echo e(request()->fullUrlWithQuery(['category' => null])); ?>" class="category-chip <?php echo e(!request('category') ? 'active' : ''); ?>">Tout</a>
                    <?php
                        $categories = [
                            'Bricolage & Travaux', 'Jardinage', 'Nettoyage & Foyer', 'Déménagement & Transport',
                            'Cours de langues', 'Cours particuliers', 'Aide à domicile', 'Animaux',
                            'Beauté & Bien-être', 'Événements', 'Sports & Fitness', 'Informatique',
                            'Avocats & Conseil', 'Santé & Médecine', 'Services Pro', 'Covoiturage', 'Vente', 'Emploi'
                        ];
                    ?>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['category' => $cat])); ?>" class="category-chip <?php echo e(request('category') == $cat ? 'active' : ''); ?>"><?php echo e($cat); ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                 </div>
            </div>

            <div class="col-lg-12">

                <div class="results-header">
                    <div class="results-count">
                        <strong><?php echo e($ads->total()); ?></strong> annonces trouvées
                        <?php if(request('location')): ?>
                            près de <strong><?php echo e(request('location')); ?></strong>
                        <?php endif; ?>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-sort me-1"></i>Trier
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'newest'])); ?>">Plus récentes</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'price_low'])); ?>">Prix croissant</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'price_high'])); ?>">Prix décroissant</a></li>
                        </ul>
                    </div>
                </div>
                
                <?php if($ads->isEmpty()): ?>
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h4>Aucune annonce trouvée</h4>
                        <p>Essayez de modifier vos critères de recherche</p>
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('ads.create')); ?>" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Publier une annonce
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php $__currentLoopData = $ads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 col-xl-4">
                                <div class="ad-card">
                                    <div class="ad-card-image">
                                        <span class="ad-badge <?php echo e($ad->service_type == 'offre' ? 'ad-badge-offre' : 'ad-badge-demande'); ?>">
                                            <?php echo e($ad->service_type == 'offre' ? 'Offre' : 'Demande'); ?>

                                        </span>
                                        <?php if($ad->is_urgent && $ad->urgent_until && $ad->urgent_until->isFuture()): ?>
                                            <span class="ad-badge-urgent">
                                                <i class="fas fa-fire me-1"></i>Urgent · <?php echo e(now()->diffInDays($ad->urgent_until, false)); ?>j
                                            </span>
                                        <?php elseif($ad->is_boosted && $ad->boost_end && $ad->boost_end->isFuture()): ?>
                                            <span class="ad-badge-boosted">
                                                <i class="fas fa-rocket me-1"></i>Boosté · <?php echo e(now()->diffInDays($ad->boost_end, false)); ?>j
                                            </span>
                                        <?php endif; ?>
                                        <?php if(!empty($ad->photos) && isset($ad->photos[0])): ?>
                                            <img src="<?php echo e(asset('storage/'.$ad->photos[0])); ?>" alt="Photo">
                                        <?php else: ?>
                                            <i class="fas fa-image"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ad-card-body">
                                        <span class="ad-card-category"><?php echo e($ad->category); ?></span>
                                        <h5 class="ad-card-title"><?php echo e($ad->title); ?></h5>
                                        <p class="ad-card-location"><i class="fas fa-map-marker-alt me-1"></i><?php echo e(Str::limit($ad->location, 25)); ?></p>
                                        <div class="ad-card-price">
                                            <?php if($ad->price): ?>
                                                <?php echo e(number_format($ad->price, 2, ',', ' ')); ?> €
                                            <?php else: ?>
                                                Prix à discuter
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="ad-card-footer">
                                        <a href="<?php echo e(route('profile.public', $ad->user_id)); ?>" class="ad-card-user text-decoration-none">
                                            <i class="fas fa-user me-1"></i><?php echo e($ad->user->name ?? 'Anonyme'); ?>

                                        </a>
                                        <div class="d-flex gap-2 align-items-center">
                                            <a href="<?php echo e(route('ads.show', $ad)); ?>" class="btn btn-view">Voir <i class="fas fa-arrow-right ms-1"></i></a>
                                            <?php if(auth()->guard()->check()): ?>
                                                <?php if(Auth::id() === $ad->user_id): ?>
                                                    <a href="<?php echo e(route('ads.edit', $ad)); ?>" class="btn btn-outline-secondary btn-sm" title="Modifier"><i class="fas fa-edit"></i></a>
                                                    <?php if(!($ad->is_boosted && $ad->boost_end && $ad->boost_end->isFuture()) && !($ad->is_urgent && $ad->urgent_until && $ad->urgent_until->isFuture())): ?>
                                                        <a href="<?php echo e(route('boost.show', $ad)); ?>" class="btn btn-warning btn-sm" title="Booster" style="color: white;"><i class="fas fa-rocket"></i></a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <?php if($ads->hasPages()): ?>
                        <div class="d-flex justify-content-center mt-5">
                            <?php echo e($ads->withQueryString()->links()); ?>

                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.getElementById('detectLocation')?.addEventListener('click', function() {
        const btn = this;
        const input = document.querySelector('input[name="location"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(async (pos) => {
                try {
                    const res = await fetch(`/api/reverse-geocode?lat=${pos.coords.latitude}&lng=${pos.coords.longitude}`);
                    const data = await res.json();
                    input.value = data.city || data.address?.split(',')[0] || `${pos.coords.latitude.toFixed(4)}, ${pos.coords.longitude.toFixed(4)}`;
                } catch(e) {
                    input.value = `${pos.coords.latitude.toFixed(4)}, ${pos.coords.longitude.toFixed(4)}`;
                }
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-location-arrow"></i>';
            }, () => {
                alert('Impossible de détecter votre position');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-location-arrow"></i>';
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Desktop\MASSIWANI V2\resources\views/ads/index.blade.php ENDPATH**/ ?>