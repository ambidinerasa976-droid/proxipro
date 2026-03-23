

<?php $__env->startSection('title', 'Gestion des Utilisateurs'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col">
        <h2 class="h4 fw-bold">Gestion des Utilisateurs</h2>
        <p class="text-muted mb-0">Liste de tous les utilisateurs de la plateforme</p>
    </div>
</div>

<!-- Filtres et recherche -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.users')); ?>">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Rechercher par nom ou email..." 
                               value="<?php echo e(request('search')); ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="role" class="form-select">
                        <option value="">Tous les rôles</option>
                        <option value="user" <?php echo e(request('role') == 'user' ? 'selected' : ''); ?>>Utilisateur</option>
                        <option value="admin" <?php echo e(request('role') == 'admin' ? 'selected' : ''); ?>>Admin</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="user_type" class="form-select">
                        <option value="">Tous les types</option>
                        <option value="particulier" <?php echo e(request('user_type') == 'particulier' ? 'selected' : ''); ?>>Particulier</option>
                        <option value="professionnel" <?php echo e(request('user_type') == 'professionnel' ? 'selected' : ''); ?>>Professionnel</option>
                        <option value="entreprise" <?php echo e(request('user_type') == 'entreprise' ? 'selected' : ''); ?>>Entreprise</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="verified" <?php echo e(request('status') == 'verified' ? 'selected' : ''); ?>>Vérifié</option>
                        <option value="unverified" <?php echo e(request('status') == 'unverified' ? 'selected' : ''); ?>>Non vérifié</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Actif</option>
                        <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactif</option>
                    </select>
                </div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Filtrer
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-1"></i>Réinitialiser
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Liste des utilisateurs -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 py-3 ps-4">ID</th>
                        <th class="border-0 py-3">Nom</th>
                        <th class="border-0 py-3">Email</th>
                        <th class="border-0 py-3">Rôle</th>
                        <th class="border-0 py-3">Type</th>
                        <th class="border-0 py-3">Annonces</th>
                        <th class="border-0 py-3">Statut</th>
                        <th class="border-0 py-3">Inscrit le</th>
                        <th class="border-0 py-3 pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $isPrincipalAdmin = $user->email === config('admin.principal_admin.email');
                        $userTypes = config('admin.user_types');
                        $userType = $userTypes[$user->user_type ?? 'particulier'] ?? $userTypes['particulier'];
                    ?>
                    <tr>
                        <td class="ps-4"><?php echo e($user->id); ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-primary text-white me-2">
                                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                </div>
                                <div>
                                    <strong><?php echo e($user->name); ?></strong>
                                    <?php if($isPrincipalAdmin): ?>
                                        <i class="fas fa-shield-alt text-warning ms-1" title="Admin Principal"></i>
                                    <?php endif; ?>
                                    <?php if($user->phone): ?>
                                        <br><small class="text-muted"><?php echo e($user->phone); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($user->email); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($user->role == 'admin' ? 'danger' : 'secondary'); ?>">
                                <?php echo e(ucfirst($user->role ?? 'user')); ?>

                            </span>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo e($userType['color']); ?>" title="<?php echo e($userType['label']); ?>">
                                <i class="fas <?php echo e($userType['icon']); ?> me-1"></i><?php echo e($userType['label']); ?>

                            </span>
                        </td>
                        <td>
                            <span class="badge bg-info"><?php echo e($user->ads_count ?? $user->ads->count()); ?></span>
                        </td>
                        <td>
                            <?php if($user->is_verified ?? false): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Vérifié
                                </span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-clock me-1"></i>Non vérifié
                                </span>
                            <?php endif; ?>
                            <?php if(!($user->is_active ?? true)): ?>
                                <span class="badge bg-danger">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($user->created_at->format('d/m/Y')); ?></td>
                        <td class="pe-4 text-end">
                            <a href="<?php echo e(route('admin.users.show', $user->id)); ?>" 
                               class="btn btn-sm btn-outline-primary" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-warning" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal<?php echo e($user->id); ?>" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            <?php if(Auth::id() !== $user->id): ?>
                            <form action="<?php echo e(route('admin.users.delete', $user->id)); ?>" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- Modal de modification -->
                    <div class="modal fade" id="editModal<?php echo e($user->id); ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Modifier <?php echo e($user->name); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="<?php echo e(route('admin.users.update', $user->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nom</label>
                                            <input type="text" name="name" class="form-control" value="<?php echo e($user->name); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" value="<?php echo e($user->email); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Téléphone</label>
                                            <input type="text" name="phone" class="form-control" value="<?php echo e($user->phone ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Rôle</label>
                                            <select name="role" class="form-select">
                                                <option value="user" <?php echo e(($user->role ?? 'user') == 'user' ? 'selected' : ''); ?>>Utilisateur</option>
                                                <option value="admin" <?php echo e(($user->role ?? 'user') == 'admin' ? 'selected' : ''); ?>>Administrateur</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_verified" value="1"
                                                       <?php echo e($user->is_verified ?? false ? 'checked' : ''); ?>>
                                                <label class="form-check-label">Vérifié</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                       <?php echo e($user->is_active ?? true ? 'checked' : ''); ?>>
                                                <label class="form-check-label">Actif</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun utilisateur trouvé</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($users->hasPages()): ?>
    <div class="card-footer bg-white border-0 py-3">
        <?php echo e($users->links()); ?>

    </div>
    <?php endif; ?>
</div>

<style>
    .avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Desktop\MASSIWANI V2\resources\views/admin/users/index.blade.php ENDPATH**/ ?>