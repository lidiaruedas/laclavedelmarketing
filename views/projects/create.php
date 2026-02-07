<div class="content-header">
    <div>
        <h1 class="page-title">Nuevo Proyecto</h1>
        <p class="text-sm text-muted mt-2">Crea un nuevo proyecto para un nicho específico</p>
    </div>
    <a href="<?= url('/projects') ?>" class="btn btn-secondary">
        ← Volver
    </a>
</div>

<div class="content-body">
    <div class="card" style="max-width: 700px;">
        <div class="card-body">
            <form action="<?= url('/projects') ?>" method="POST">
                <?= csrfField() ?>

                <div class="form-group">
                    <label for="name" class="form-label">Nombre del Proyecto *</label>
                    <input type="text" id="name" name="name"
                        class="form-input <?= hasError('name') ? 'is-invalid' : '' ?>" value="<?= e(old('name')) ?>"
                        placeholder="Ej: Agencia IA para Restaurantes" required autofocus>
                    <?php if (hasError('name')): ?>
                        <div class="form-error">
                            <?= e(error('name')) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="niche" class="form-label">Nicho de Mercado</label>
                    <input type="text" id="niche" name="niche" class="form-input" value="<?= e(old('niche')) ?>"
                        placeholder="Ej: Restaurantes, Clínicas, Inmobiliarias...">
                    <div class="form-hint">Define el sector al que va dirigido este proyecto</div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea id="description" name="description" class="form-textarea"
                        placeholder="Describe brevemente el objetivo de este proyecto..."><?= e(old('description')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="target_audience" class="form-label">Público Objetivo</label>
                    <textarea id="target_audience" name="target_audience" class="form-textarea"
                        placeholder="Describe a quién va dirigido: tamaño de empresa, ubicación, necesidades..."><?= e(old('target_audience')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">Estado Inicial</label>
                    <select id="status" name="status" class="form-select">
                        <option value="draft" <?= old('status') === 'draft' ? 'selected' : '' ?>>📝 Borrador</option>
                        <option value="active" <?= old('status') === 'active' ? 'selected' : '' ?>>✅ Activo</option>
                    </select>
                </div>

                <div class="flex gap-4 mt-6">
                    <button type="submit" class="btn btn-primary btn-lg">
                        ✓ Crear Proyecto
                    </button>
                    <a href="<?= url('/projects') ?>" class="btn btn-secondary btn-lg">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>