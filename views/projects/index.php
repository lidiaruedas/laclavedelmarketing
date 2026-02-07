<div class="content-header">
    <div>
        <h1 class="page-title">Mis Proyectos</h1>
        <p class="text-sm text-muted mt-2">Gestiona todos tus proyectos y nichos de mercado</p>
    </div>
    <a href="<?= url('/projects/create') ?>" class="btn btn-primary">
        ➕ Nuevo Proyecto
    </a>
</div>

<div class="content-body">
    <!-- Filter Stats -->
    <div class="flex gap-4 mb-6" style="flex-wrap: wrap;">
        <a href="<?= url('/projects') ?>" class="btn <?= !$currentStatus ? 'btn-primary' : 'btn-secondary' ?> btn-sm">
            Todos (
            <?= $stats['total'] ?>)
        </a>
        <a href="<?= url('/projects?status=active') ?>"
            class="btn <?= $currentStatus === 'active' ? 'btn-primary' : 'btn-secondary' ?> btn-sm">
            ✅ Activos (
            <?= $stats['active'] ?>)
        </a>
        <a href="<?= url('/projects?status=draft') ?>"
            class="btn <?= $currentStatus === 'draft' ? 'btn-primary' : 'btn-secondary' ?> btn-sm">
            📝 Borrador (
            <?= $stats['draft'] ?>)
        </a>
        <a href="<?= url('/projects?status=paused') ?>"
            class="btn <?= $currentStatus === 'paused' ? 'btn-primary' : 'btn-secondary' ?> btn-sm">
            ⏸️ Pausados (
            <?= $stats['paused'] ?>)
        </a>
        <a href="<?= url('/projects?status=completed') ?>"
            class="btn <?= $currentStatus === 'completed' ? 'btn-primary' : 'btn-secondary' ?> btn-sm">
            🏆 Completados (
            <?= $stats['completed'] ?>)
        </a>
    </div>

    <?php if (empty($projects)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📁</div>
            <h4 class="empty-state-title">
                <?= $currentStatus ? 'No hay proyectos con este estado' : 'Sin proyectos aún' ?>
            </h4>
            <p class="empty-state-text">
                <?php if ($currentStatus): ?>
                    Cambia el filtro o crea un nuevo proyecto.
                <?php else: ?>
                    Crea tu primer proyecto para comenzar a gestionar tu agencia de IA.
                <?php endif; ?>
            </p>
            <a href="<?= url('/projects/create') ?>" class="btn btn-primary">
                ➕ Crear Proyecto
            </a>
        </div>
    <?php else: ?>
        <div class="projects-grid">
            <?php foreach ($projects as $project): ?>
                <div class="project-card" data-animate>
                    <div class="project-card-header">
                        <div>
                            <h3 class="project-card-title">
                                <?= e($project['name']) ?>
                            </h3>
                            <?php if ($project['niche']): ?>
                                <div class="project-card-niche">
                                    <span>🎯</span>
                                    <?= e($project['niche']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <span class="badge badge-<?= $project['status'] ?>">
                            <?= ucfirst($project['status']) ?>
                        </span>
                    </div>

                    <div class="project-card-body">
                        <p class="project-card-description">
                            <?= e($project['description'] ?: 'Sin descripción') ?>
                        </p>
                    </div>

                    <div class="project-card-footer">
                        <span class="project-card-date">
                            Actualizado
                            <?= formatDate($project['updated_at'], 'd M Y') ?>
                        </span>
                        <div class="flex gap-2">
                            <a href="<?= url('/projects/' . $project['id'] . '/edit') ?>" class="btn btn-ghost btn-sm">
                                ✏️
                            </a>
                            <a href="<?= url('/projects/' . $project['id']) ?>" class="btn btn-primary btn-sm">
                                Ver →
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>