<div class="content-header">
    <h1 class="page-title">Dashboard</h1>
    <a href="<?= url('/projects/create') ?>" class="btn btn-primary">
        ➕ Nuevo Proyecto
    </a>
</div>

<div class="content-body">
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card" data-animate>
            <div class="stat-card-header">
                <div class="stat-icon primary">📁</div>
            </div>
            <div class="stat-value">
                <?= $projectStats['total'] ?? 0 ?>
            </div>
            <div class="stat-label">Proyectos Totales</div>
        </div>

        <div class="stat-card" data-animate>
            <div class="stat-card-header">
                <div class="stat-icon success">✅</div>
            </div>
            <div class="stat-value">
                <?= $projectStats['active'] ?? 0 ?>
            </div>
            <div class="stat-label">Proyectos Activos</div>
        </div>

        <div class="stat-card" data-animate>
            <div class="stat-card-header">
                <div class="stat-icon warning">⏸️</div>
            </div>
            <div class="stat-value">
                <?= $projectStats['draft'] + $projectStats['paused'] ?>
            </div>
            <div class="stat-label">En Espera</div>
        </div>

        <div class="stat-card" data-animate>
            <div class="stat-card-header">
                <div class="stat-icon accent">🏆</div>
            </div>
            <div class="stat-value">
                <?= $projectStats['completed'] ?? 0 ?>
            </div>
            <div class="stat-label">Completados</div>
        </div>
    </div>

    <!-- Recent Projects -->
    <div class="card" data-animate>
        <div class="card-header">
            <h3 class="card-title">Proyectos Recientes</h3>
            <a href="<?= url('/projects') ?>" class="btn btn-ghost btn-sm">Ver todos →</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentProjects)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📁</div>
                    <h4 class="empty-state-title">Sin proyectos aún</h4>
                    <p class="empty-state-text">
                        Crea tu primer proyecto para comenzar a gestionar tu agencia de IA.
                    </p>
                    <a href="<?= url('/projects/create') ?>" class="btn btn-primary">
                        ➕ Crear Primer Proyecto
                    </a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Proyecto</th>
                                <th>Nicho</th>
                                <th>Estado</th>
                                <th>Actualizado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentProjects as $project): ?>
                                <tr>
                                    <td>
                                        <a href="<?= url('/projects/' . $project['id']) ?>"
                                            style="color: var(--text-primary); font-weight: 500;">
                                            <?= e($project['name']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <?= e($project['niche'] ?: '—') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= $project['status'] ?>">
                                            <?= ucfirst($project['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted text-sm">
                                            <?= formatDate($project['updated_at']) ?>
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <a href="<?= url('/projects/' . $project['id']) ?>" class="btn btn-ghost btn-sm">
                                            Ver →
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Future Modules Preview -->
    <div style="margin-top: var(--space-8);">
        <h3 style="font-size: var(--text-lg); margin-bottom: var(--space-4); color: var(--text-muted);">
            🚀 Próximamente
        </h3>
        <div class="stats-grid">
            <div class="card" style="opacity: 0.6;">
                <div class="card-body text-center">
                    <div style="font-size: 2rem; margin-bottom: var(--space-2);">🎯</div>
                    <h4>Captación de Leads</h4>
                    <p class="text-sm text-muted">Búsqueda automática de clientes potenciales</p>
                </div>
            </div>
            <div class="card" style="opacity: 0.6;">
                <div class="card-body text-center">
                    <div style="font-size: 2rem; margin-bottom: var(--space-2);">✨</div>
                    <h4>Motor de IA</h4>
                    <p class="text-sm text-muted">Prompts y generación de contenido</p>
                </div>
            </div>
            <div class="card" style="opacity: 0.6;">
                <div class="card-body text-center">
                    <div style="font-size: 2rem; margin-bottom: var(--space-2);">📈</div>
                    <h4>Simulador ROI</h4>
                    <p class="text-sm text-muted">Calcula el retorno para tus clientes</p>
                </div>
            </div>
        </div>
    </div>
</div>