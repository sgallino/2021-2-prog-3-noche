<?php

namespace App\Auth;

use App\Models\Usuario;

class Auth
{
    /**
     * @var array
     */
    protected $credentials = [];

    /**
     * @var Usuario|null
     */
    protected $user;

    /**
     * Intenta autenticar un usuario en el sistema.
     *
     * @param array $credentials - Las credenciales del usuario. Debe contener las claves "email" y "password".
     * @return bool - Si la autenticación tuvo éxito o no.
     */
    public function login(array $credentials): bool
    {
        $this->credentials = $credentials;

        // Buscamos el usuario por su email.
        $user = new Usuario();
        $this->user = $user->findByEmail($this->credentials['email']);

        if($this->user === null) {
            return false;
        }

        // Verificamos la contraseña.
        if(!password_verify($this->credentials['password'], $this->user->getPassword())) {
            return false;
        }

        // La verificación fue exitosa, así que autenticamos al usuario.
        $this->authenticate($this->user);
        return true;
    }

    /**
     * Cierra la sesión del usuario en el sistema.
     */
    public function logout(): void
    {
        unset($_SESSION['id']);
    }

    /**
     * Autentica al usuario en el sistema.
     *
     * @param Usuario $user
     */
    public function authenticate(Usuario $user): void
    {
        $_SESSION['id'] = $user->getId();
    }

    /**
     * Si el usuario está autenticado o no.
     *
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return isset($_SESSION['id']);
    }

    /**
     * El usuario autenticado. De no haber una sesión iniciada, retorna null.
     *
     * @return Usuario|null
     */
    public function getUser(): ?Usuario
    {
        if(!$this->isAuthenticated()) {
            return null;
        }

        // Si el usuario no está cargado, lo cargamos a través de la clase Usuario.
        if($this->user === null) {
            $user = new Usuario();
            $this->user = $user->findByPk($_SESSION['id']);
        }

        return $this->user;
    }
}
