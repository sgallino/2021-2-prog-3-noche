<main class="container mt-2 pb-3">
    <h1>Registrarse</h1>

    <p>Creá tu cuenta y empezá a obtener incontables beneficios. ¡Es muy fácil!</p>

    <form action="<?= \App\Router::urlTo('registro');?>" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password_confirm" class="form-label">Confirmar Password</label>
            <input type="password" id="password_confirm" name="password_confirm" class="form-control">
        </div>
        <button class="btn btn-primary" type="submit">Registrarse</button>
    </form>
</main>
