<?php

namespace App\Models;


use App\Database\Connection;

class Usuario
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    protected $usuario;
    protected $nombre;
    protected $apellido;
    protected $imagen;
    protected $remember_token;

    /**
     * @param string $email
     * @return Usuario|null
     */
    public function findByEmail(string $email): ?Usuario // El "?" delante indica que puede también ser null.
    {
        $db = Connection::getConnection();
        $query = "SELECT * FROM usuarios
                  WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$email]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, self::class);
        $user = $stmt->fetch();

        if(!$user) {
            return null;
        }

        return $user;
    }

    /**
     * Retorna el usuario asociado a la $pk.
     * null en caso de no existir.
     *
     * @param int $pk
     * @return Usuario|null
     */
    public function findByPk(int $pk): ?Usuario
    {
        $db = Connection::getConnection();
        $query = "SELECT * FROM usuarios
                  WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$pk]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, self::class);
        $user = $stmt->fetch();

        if(!$user) {
            return null;
        }

        return $user;
    }

    /**
     * Crea un usuario en la base de datos.
     * En caso de error, retorna null.
     *
     * @param array $data
     * @return Usuario|null
     */
    public function create(array $data): ?Usuario
    {
        $db = Connection::getConnection();
        $query = "INSERT INTO usuarios (email, password, nombre, apellido, remember_token, usuario)
                VALUES (:email, :password, '', '', '', '')";
        $stmt = $db->prepare($query);
        $success = $stmt->execute([
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        if(!$success) {
            return null;
        }

        // Creamos la instancia de Usuario con los datos creados.
        $user = new Usuario();
        $user->setId($db->lastInsertId());
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);

        return $user;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }
}
