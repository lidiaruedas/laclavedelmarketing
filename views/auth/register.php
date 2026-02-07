<div class="auth-card animate-fade-in">
    <div class="auth-header">
        <div class="auth-logo">🚀</div>
        <h1 class="auth-title">Crear Cuenta</h1>
        <p class="auth-subtitle">Únete a La Clave del Marketing y comienza a crecer</p>
    </div>

    <form action="<?= url('/register') ?>" method="POST">
        <?= csrfField() ?>

        <div class="form-group">
            <label for="name" class="form-label">Nombre completo</label>
            <input type="text" id="name" name="name" class="form-input <?= hasError('name') ? 'is-invalid' : '' ?>"
                value="<?= e(old('name')) ?>" placeholder="Tu nombre" required autofocus>
            <?php if (hasError('name')): ?>
                <div class="form-error">
                    <?= e(error('name')) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-input <?= hasError('email') ? 'is-invalid' : '' ?>"
                value="<?= e(old('email')) ?>" placeholder="tu@email.com" required>
            <?php if (hasError('email')): ?>
                <div class="form-error">
                    <?= e(error('email')) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" name="password"
                class="form-input <?= hasError('password') ? 'is-invalid' : '' ?>" placeholder="Mínimo 8 caracteres"
                required>
            <?php if (hasError('password')): ?>
                <div class="form-error">
                    <?= e(error('password')) ?>
                </div>
            <?php endif; ?>
            <div class="form-hint">Usa al menos 8 caracteres para mayor seguridad</div>
        </div>

        <div class="form-group">
            <label for="password_confirm" class="form-label">Confirmar contraseña</label>
            <input type="password" id="password_confirm" name="password_confirm"
                class="form-input <?= hasError('password_confirm') ? 'is-invalid' : '' ?>"
                placeholder="Repite la contraseña" required>
            <?php if (hasError('password_confirm')): ?>
                <div class="form-error">
                    <?= e(error('password_confirm')) ?>
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-full">
            Crear Cuenta
        </button>
    </form>

    <div class="auth-footer">
        ¿Ya tienes cuenta? <a href="<?= url('/login') ?>">Inicia sesión</a>
    </div>
</div>