<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="La Clave del Marketing - Plataforma SaaS para crear agencias de servicios de IA">
    <title>
        <?= e($title ?? 'Iniciar Sesión') ?> |
        <?= APP_NAME ?>
    </title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="<?= asset('css/styles.css') ?>">
</head>

<body>
    <div class="auth-layout">
        <div class="auth-container">
            <?php if (hasFlash('success') || hasFlash('error') || hasFlash('warning')): ?>
                <?php if ($msg = flash('success')): ?>
                    <div class="alert alert-success" style="margin-bottom: var(--space-4);">✓
                        <?= e($msg) ?>
                    </div>
                <?php endif; ?>
                <?php if ($msg = flash('error')): ?>
                    <div class="alert alert-error" style="margin-bottom: var(--space-4);">✕
                        <?= e($msg) ?>
                    </div>
                <?php endif; ?>
                <?php if ($msg = flash('warning')): ?>
                    <div class="alert alert-warning" style="margin-bottom: var(--space-4);">⚠
                        <?= e($msg) ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>

    <script src="<?= asset('js/app.js') ?>"></script>
</body>

</html>