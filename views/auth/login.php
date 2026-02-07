<div class="auth-card animate-fade-in">
    <div class="auth-header">
        <div class="auth-logo">🚀</div>
        <h1 class="auth-title">Bienvenido de nuevo</h1>
        <p class="auth-subtitle">Accede a tu cuenta de La Clave del Marketing</p>
    </div>

    <form action="<?= url('/login') ?>" method="POST">
        <?= csrfField() ?>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-input <?= hasError('email') ? 'is-invalid' : '' ?>"
                value="<?= e(old('email')) ?>" placeholder="tu@email.com" required autofocus>
            <?php if (hasError('email')): ?>
                <div class="form-error">
                    <?= e(error('email')) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" name="password"
                class="form-input <?= hasError('password') ? 'is-invalid' : '' ?>" placeholder="••••••••" required>
            <?php if (hasError('password')): ?>
                <div class="form-error">
                    <?= e(error('password')) ?>
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-full">
            Iniciar Sesión
        </button>
    </form>

    <div class="auth-footer">
        ¿No tienes cuenta? <a href="<?= url('/register') ?>">Regístrate gratis</a>
    </div>
</div>