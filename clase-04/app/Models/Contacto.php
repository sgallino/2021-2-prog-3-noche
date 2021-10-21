<?php

class Contacto
{
    public $id;
    public $nombre;
    public $email;
    public $mensaje;
    public $created_at;

    public function findById($id) {
        $dsn = 'mysql:dbname=cms;host=127.0.0.1';
        $user = 'root';
        $pass = '';

        try {
            $gbd = new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            echo 'Falló la conexión: ' . $e->getMessage();
        }

        $sql = "SELECT * FROM contactos WHERE id = :id";
        $sth = $gbd->prepare($sql);
        $sth->execute(['id' => $id]);

        $contacto = $sth->fetch();

        $this->id = $contacto['id'];
        $this->nombre = $contacto['nombre'];
        $this->email = $contacto['email'];
        $this->mensaje = $contacto['mensaje'];
        $this->created_at = $contacto['created_at'];
    }
}