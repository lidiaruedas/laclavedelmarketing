<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="La Clave del Marketing - Plataforma SaaS para crear agencias de servicios de IA">
    <title>
        <?= e($title ?? 'Dashboard') ?> |
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
    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="<?= url('/dashboard') ?>" class="sidebar-logo">
                    <span class="sidebar-logo-icon">🚀</span>
                    <span>LCDM</span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Principal</div>
                    <a href="<?= url('/dashboard') ?>"
                        class="nav-link <?= ($_SERVER['REQUEST_URI'] === '/dashboard') ? 'active' : '' ?>">
                        <span class="nav-link-icon">📊</span>
                        Dashboard
                    </a>
                    <a href="<?= url('/projects') ?>"
                        class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/projects') === 0) ? 'active' : '' ?>">
                        <span class="nav-link-icon">📁</span>
                        Proyectos
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Próximamente</div>
                    <span class="nav-link" style="opacity: 0.5; cursor: not-allowed;">
                        <span class="nav-link-icon">🎯</span>
                        Leads
                        <span class="nav-link-badge">Soon</span>
                    </span>
                    <span class="nav-link" style="opacity: 0.5; cursor: not-allowed;">
                        <span class="nav-link-icon">✨</span>
                        Prompts IA
                        <span class="nav-link-badge">Soon</span>
                    </span>
                    <span class="nav-link" style="opacity: 0.5; cursor: not-allowed;">
                        <span class="nav-link-icon">📝</span>
                        Propuestas
                        <span class="nav-link-badge">Soon</span>
                    </span>
                    <span class="nav-link" style="opacity: 0.5; cursor: not-allowed;">
                        <span class="nav-link-icon">📈</span>
                        Calculadora ROI
                        <span class="nav-link-badge">Soon</span>
                    </span>
                    <span class="nav-link" style="opacity: 0.5; cursor: not-allowed;">
                        <span class="nav-link-icon">⚡</span>
                        Automatizaciones
                        <span class="nav-link-badge">Soon</span>
                    </span>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="user-menu">
                    <div class="user-avatar">
                        <?= strtoupper(substr(auth()['name'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="user-info">
                        <div class="user-name">
                            <?= e(auth()['name'] ?? 'Usuario') ?>
                        </div>
                        <div class="user-role">
                            <?= e(auth()['role'] ?? 'user') ?>
                        </div>
                    </div>
                    <a href="<?= url('/logout') ?>" class="btn btn-ghost btn-icon" title="Cerrar sesión">
                        🚪
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Mobile Menu Toggle -->
            <button id="mobile-menu-toggle" class="btn btn-ghost btn-icon"
                style="display: none; position: fixed; top: 1rem; left: 1rem; z-index: 101;">
                ☰
            </button>

            <?php if (hasFlash('success') || hasFlash('error') || hasFlash('warning') || hasFlash('info')): ?>
                <div style="padding: var(--space-4) var(--space-8) 0;">
                    <?php if ($msg = flash('success')): ?>
                        <div class="alert alert-success" data-auto-dismiss="5000">✓
                            <?= e($msg) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($msg = flash('error')): ?>
                        <div class="alert alert-error">✕
                            <?= e($msg) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($msg = flash('warning')): ?>
                        <div class="alert alert-warning">⚠
                            <?= e($msg) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($msg = flash('info')): ?>
                        <div class="alert alert-info">ℹ
                            <?= e($msg) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Page Content -->
            <?= $content ?>
        </main>
    </div>

    <script src="<?= asset('js/app.js') ?>"></script>

    <style>
        @media (max-width: 1024px) {
            #mobile-menu-toggle {
                display: block !important;
            }
        }
    </style>
</body>

</html>