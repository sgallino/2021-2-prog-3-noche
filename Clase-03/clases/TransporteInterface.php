<?php

interface TransporteInterface
{
    public function detalle() : string;
    public function saludar(string $nombre) : string;
}