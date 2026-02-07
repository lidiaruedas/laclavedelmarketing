<div class="content-header">
    <div>
        <div class="flex items-center gap-3 mb-2">
            <span class="badge badge-<?= $project['status'] ?>">
                <?= ucfirst($project['status']) ?>
            </span>
            <?php if ($project['niche']): ?>
                <span class="text-sm text-muted">🎯
                    <?= e($project['niche']) ?>
                </span>
            <?php endif; ?>
        </div>
        <h1 class="page-title">
            <?= e($project['name']) ?>
        </h1>
    </div>
    <div class="flex gap-3">
        <a href="<?= url('/projects/' . $project['id'] . '/edit') ?>" class="btn btn-secondary">
            ✏️ Editar
        </a>
        <a href="<?= url('/projects') ?>" class="btn btn-ghost">
            ← Proyectos
        </a>
    </div>
</div>

<div class="content-body">
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
        <!-- Módulos futuros como cards placeholder -->
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon primary">🎯</div>
            </div>
            <div class="stat-value">0</div>
            <div class="stat-label">Leads Capturados</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon accent">✨</div>
            </div>
            <div class="stat-value">0</div>
            <div class="stat-label">Prompts Creados</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon success">📝</div>
            </div>
            <div class="stat-value">0</div>
            <div class="stat-label">Propuestas Enviadas</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon warning">⚡</div>
            </div>
            <div class="stat-value">0</div>
            <div class="stat-label">Automatizaciones</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--space-6);">
        <!-- Información del proyecto -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Información del Proyecto</h3>
            </div>
            <div class="card-body">
                <div class="mb-6">
                    <h4 class="text-sm text-muted mb-2">Descripción</h4>
                    <p>
                        <?= e($project['description'] ?: 'Sin descripción definida.') ?>
                    </p>
                </div>

                <div class="mb-6">
                    <h4 class="text-sm text-muted mb-2">Público Objetivo</h4>
                    <p>
                        <?= e($project['target_audience'] ?: 'Sin público objetivo definido.') ?>
                    </p>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4);">
                    <div>
                        <h4 class="text-sm text-muted mb-1">Creado</h4>
                        <p>
                            <?= formatDate($project['created_at']) ?>
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm text-muted mb-1">Última actualización</h4>
                        <p>
                            <?= formatDate($project['updated_at']) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actividad reciente y acciones -->
        <div>
            <!-- Quick Actions -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Acciones Rápidas</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-col gap-3">
                        <button class="btn btn-secondary w-full" disabled>
                            🎯 Buscar Leads (Próximamente)
                        </button>
                        <button class="btn btn-secondary w-full" disabled>
                            ✨ Generar Prompts (Próximamente)
                        </button>
                        <button class="btn btn-secondary w-full" disabled>
                            📝 Crear Propuesta (Próximamente)
                        </button>
                        <button class="btn btn-secondary w-full" disabled>
                            📈 Calcular ROI (Próximamente)
                        </button>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card" style="border-color: var(--error-500);">
                <div class="card-header" style="border-color: var(--error-500);">
                    <h3 class="card-title" style="color: var(--error-500);">⚠️ Zona de Peligro</h3>
                </div>
                <div class="card-body">
                    <p class="text-sm text-muted mb-4">
                        Esta acción eliminará permanentemente el proyecto y todos sus datos asociados.
                    </p>
                    <form action="<?= url('/projects/' . $project['id'] . '/delete') ?>" method="POST"
                        class="delete-form" data-confirm-message="¿Estás seguro? Esta acción no se puede deshacer.">
                        <?= csrfField() ?>
                        <button type="submit" class="btn btn-danger w-full">
                            🗑️ Eliminar Proyecto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 900px) {
        .content-body>div:last-child {
            grid-template-columns: 1fr !important;
        }
    }
</style>