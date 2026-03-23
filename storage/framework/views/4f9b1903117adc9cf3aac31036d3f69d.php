

<?php $__env->startSection('title', 'Gestion des Publicités'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col">
        <h2 class="h4 fw-bold">
            <i class="fas fa-ad text-warning me-2"></i>Gestion des Publicités
        </h2>
        <p class="text-muted mb-0">Gérez les espaces publicitaires de la plateforme</p>
    </div>
    <div class="col-auto">
        <a href="<?php echo e(route('admin.advertisements.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouvelle publicité
        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Statistiques rapides -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="h3 mb-1 text-primary"><?php echo e($advertisements->total()); ?></div>
                <small class="text-muted">Total publicités</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="h3 mb-1 text-success"><?php echo e($advertisements->where('is_active', true)->count()); ?></div>
                <small class="text-muted">Actives</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="h3 mb-1 text-info"><?php echo e($advertisements->sum('impressions')); ?></div>
                <small class="text-muted">Impressions</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="h3 mb-1 text-warning"><?php echo e($advertisements->sum('clicks')); ?></div>
                <small class="text-muted">Clics</small>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 ps-4">Image</th>
                        <th class="border-0">Titre</th>
                        <th class="border-0">Position</th>
                        <th class="border-0 text-center">Priorité</th>
                        <th class="border-0 text-center">Statut</th>
                        <th class="border-0 text-center">Impressions</th>
                        <th class="border-0 text-center">Clics</th>
                        <th class="border-0 text-center">CTR</th>
                        <th class="border-0 text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $advertisements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4">
                            <?php if($ad->image): ?>
                                <img src="<?php echo e(asset('storage/' . $ad->image)); ?>" alt="<?php echo e($ad->title); ?>" 
                                     style="width: 60px; height: 40px; object-fit: cover; border-radius: 6px;">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 40px; border-radius: 6px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-semibold"><?php echo e(Str::limit($ad->title, 30)); ?></div>
                            <?php if($ad->link): ?>
                                <small class="text-muted"><?php echo e(Str::limit($ad->link, 40)); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($ad->position === 'sidebar'): ?>
                                <span class="badge bg-info">Sidebar</span>
                            <?php elseif($ad->position === 'banner'): ?>
                                <span class="badge bg-primary">Bannière</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Popup</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary"><?php echo e($ad->priority); ?></span>
                        </td>
                        <td class="text-center">
                            <form action="<?php echo e(route('admin.advertisements.toggle', $ad->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm <?php echo e($ad->is_active ? 'btn-success' : 'btn-outline-secondary'); ?>">
                                    <i class="fas <?php echo e($ad->is_active ? 'fa-check' : 'fa-times'); ?>"></i>
                                    <?php echo e($ad->is_active ? 'Active' : 'Inactive'); ?>

                                </button>
                            </form>
                        </td>
                        <td class="text-center"><?php echo e(number_format($ad->impressions)); ?></td>
                        <td class="text-center"><?php echo e(number_format($ad->clicks)); ?></td>
                        <td class="text-center">
                            <?php if($ad->impressions > 0): ?>
                                <?php echo e(number_format(($ad->clicks / $ad->impressions) * 100, 2)); ?>%
                            <?php else: ?>
                                0%
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <a href="<?php echo e(route('admin.advertisements.edit', $ad->id)); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('admin.advertisements.delete', $ad->id)); ?>" method="POST" class="d-inline"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publicité ?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-ad fa-3x mb-3 opacity-25"></i>
                                <p class="mb-2">Aucune publicité créée</p>
                                <a href="<?php echo e(route('admin.advertisements.create')); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Créer une publicité
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php if($advertisements->hasPages()): ?>
    <div class="card-footer bg-white border-0">
        <?php echo e($advertisements->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Desktop\MASSIWANI V2\resources\views/admin/advertisements/index.blade.php ENDPATH**/ ?>