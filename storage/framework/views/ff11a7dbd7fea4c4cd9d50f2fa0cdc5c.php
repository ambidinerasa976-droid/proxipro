
<?php $__env->startSection('title', 'Mes Factures - Espace Pro'); ?>

<?php $__env->startSection('content'); ?>
<div class="pro-content-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1" style="font-size: 0.8rem;">
                <li class="breadcrumb-item"><a href="<?php echo e(route('pro.dashboard')); ?>" style="color: var(--pro-primary);">Espace Pro</a></li>
                <li class="breadcrumb-item active">Factures</li>
            </ol>
        </nav>
        <h1>Mes factures</h1>
    </div>
    <a href="<?php echo e(route('pro.invoices.create')); ?>" class="btn btn-pro-primary">
        <i class="fas fa-plus me-1"></i> Nouvelle facture
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="pro-card text-center py-3 mb-0">
            <div class="fw-bold fs-4 text-primary"><?php echo e($invoices->total()); ?></div>
            <div class="text-muted" style="font-size: 0.78rem;">Total factures</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pro-card text-center py-3 mb-0">
            <div class="fw-bold fs-4 text-warning"><?php echo e($invoices->where('status', 'sent')->count()); ?></div>
            <div class="text-muted" style="font-size: 0.78rem;">En attente</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pro-card text-center py-3 mb-0">
            <div class="fw-bold fs-4 text-success"><?php echo e($invoices->where('status', 'paid')->count()); ?></div>
            <div class="text-muted" style="font-size: 0.78rem;">Payées</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="pro-card text-center py-3 mb-0">
            <div class="fw-bold fs-4 text-danger"><?php echo e($invoices->where('status', 'overdue')->count()); ?></div>
            <div class="text-muted" style="font-size: 0.78rem;">En retard</div>
        </div>
    </div>
</div>

<?php if($invoices->isEmpty()): ?>
    <div class="pro-card">
        <div class="pro-empty">
            <div class="pro-empty-icon">🧾</div>
            <h5>Aucune facture</h5>
            <p>Créez votre première facture pour suivre vos paiements.</p>
            <a href="<?php echo e(route('pro.invoices.create')); ?>" class="btn btn-pro-primary mt-2">
                <i class="fas fa-plus me-1"></i> Nouvelle facture
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="pro-card">
        <div class="table-responsive" style="overflow: visible;">
            <table class="pro-table">
                <thead>
                    <tr>
                        <th>N° Facture</th>
                        <th>Client</th>
                        <th>Montant TTC</th>
                        <th>Date</th>
                        <th>Échéance</th>
                        <th>Statut</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><a href="<?php echo e(route('pro.invoices.show', $invoice->id)); ?>" class="fw-bold" style="color: var(--pro-primary);"><?php echo e($invoice->invoice_number); ?></a></td>
                        <td><?php echo e(Str::limit($invoice->client_name, 25)); ?></td>
                        <td class="fw-bold"><?php echo e(number_format($invoice->total, 2, ',', ' ')); ?>€</td>
                        <td><?php echo e($invoice->created_at->format('d/m/Y')); ?></td>
                        <td><?php echo e($invoice->due_date ? $invoice->due_date->format('d/m/Y') : '-'); ?></td>
                        <td><span class="pro-status pro-status-<?php echo e($invoice->getStatusColor()); ?>"><?php echo e($invoice->getStatusLabel()); ?></span></td>
                        <td style="text-align: right;">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" data-bs-toggle="dropdown" data-bs-display="static" style="border-radius: 8px;">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?php echo e(route('pro.invoices.show', $invoice->id)); ?>"><i class="fas fa-eye me-2"></i>Voir</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('pro.invoices.edit', $invoice->id)); ?>"><i class="fas fa-edit me-2"></i>Modifier</a></li>
                                    <?php if($invoice->status !== 'paid'): ?>
                                    <li>
                                        <form method="POST" action="<?php echo e(route('pro.invoices.status', $invoice->id)); ?>">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                            <input type="hidden" name="status" value="paid">
                                            <button class="dropdown-item text-success"><i class="fas fa-check me-2"></i>Marquer payée</button>
                                        </form>
                                    </li>
                                    <?php endif; ?>
                                    <?php if($invoice->status === 'draft'): ?>
                                    <li>
                                        <form method="POST" action="<?php echo e(route('pro.invoices.status', $invoice->id)); ?>">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                            <input type="hidden" name="status" value="sent">
                                            <button class="dropdown-item"><i class="fas fa-paper-plane me-2"></i>Envoyer</button>
                                        </form>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3"><?php echo e($invoices->links()); ?></div>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pro.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Desktop\MASSIWANI V2\resources\views/pro/invoices.blade.php ENDPATH**/ ?>