<h1>Contacto</h1>

<form action="index.php?page=contacto&action=submit" method="post">
    <div>
        <label for="nombre">Nombre</label>
        <input type="text" class="form-control <?php echo isset($errores['nombre']) ? 'is-invalid' : ''; ?>" id="nombre" name="nombre" value="<?php echo $nombre ?? ''; ?>">
        <?php if (isset($errores['nombre'])): ?>
            <div class="invalid-feedback"><?php echo $errores['nombre']; ?></div>
        <?php endif; ?>
    </div>
    <button class="btn btn-primary" type="submit">Enviar</button>
</form>