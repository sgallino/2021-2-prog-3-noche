<?php
namespace App\Filesystem;

use App\Utilities\Str;

class FileUpload
{
    /**
     * @var array - El array obtenido de $_FILES con los datos del archivo.
     */
    protected $uploadedFile = [];

    /**
     * @var string
     */
    protected $filename;

    /**
     * @param array $file
     */
    public function __construct(array $file)
    {
        $this->uploadedFile = $file;
    }

    /**
     * @param $path - La ruta donde guardar el archivo.
     * @return string - El nombre del archivo guardado.
     */
    public function save($path): string
    {
        $path = Str::suffixIfMissing($path, DIRECTORY_SEPARATOR);
        $this->filename = $this->generateFilename();
        move_uploaded_file($this->uploadedFile['tmp_name'], $path . $this->filename);
        return $this->filename;
    }

    /**
     * Genera un nombre para el archivo.
     */
    protected function generateFilename(): string
    {
        // v1: Retornamos el nombre original con la fecha delante.
        return Str::sluggify(date('YmdHis_') . $this->uploadedFile['name']);
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }
}
