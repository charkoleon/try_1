<?php

namespace controllers;

use model\MainModel;

class PublicacionesController extends MainModel
{
    public function createPublication($titulo, $contenido, $file)
    {
        $foto = $file['foto-publicacion'];

        $nombreImagen = $foto['name'];
        $tmpImagen = $foto['tmp_name'];
        $extension = strtolower(pathinfo($nombreImagen, PATHINFO_EXTENSION));


        $extensionesPermitidas = ['jpg', 'jpeg', 'png'];
        if (!in_array($extension, $extensionesPermitidas)) {
            return json_encode([
                "tipo" => "error",
                "titulo" => "Formato inválido",
                "texto" => "Solo se permiten imágenes JPG, JPEG o PNG",
                "icono" => "error"
            ]);
        }

        $directory = 'images/';
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        // Ahora mueve el archivo
        $ruta = $directory . 'publicacion__' . $_SESSION['id_usuario'] . '_' . time() . '.' . $extension;


        if (move_uploaded_file($tmpImagen, $ruta)) {
            $publicacionDatos = [
                [
                    'nombre' => 'titulo',
                    'nombre_marcador' => ':titulo',
                    'valor' => $titulo
                ],
                [
                    'nombre' => 'id_usuario',
                    'nombre_marcador' => ':id_usuario',
                    'valor' => $_SESSION['id_usuario']
                ],
                [
                    'nombre' => 'contenido',
                    'nombre_marcador' => ':contenido',
                    'valor' => $contenido
                ],
                [
                    'nombre' => 'foto_portada',
                    'nombre_marcador' => ':foto_portada',
                    'valor' => $ruta
                ]
            ];
        } else {
            return json_encode([
                "tipo" => "error",
                "titulo" => "Error de subida",
                "texto" => "No se pudo mover la imagen al servidor.",
                "icono" => "error"
            ]);
        }



        $registrarPublicacion = $this->publicar('Publicaciones', $publicacionDatos);

        if ($registrarPublicacion->rowCount() == 1) {

            $alerta = [
                "tipo" => "error",
                "titulo" => "Success",
                "texto" => "La publicacion se ha guardado con exito",
                "icono" => "success"
            ];
            return json_encode($alerta);
            exit();
        } else {
            return json_encode([
                "tipo" => "error",
                "titulo" => "Error",
                "texto" => "No se pudo guardar la publicación en la base de datos.",
                "icono" => "error"
            ]);
        }
    }
}
