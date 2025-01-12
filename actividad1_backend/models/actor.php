<?php
require_once('../config/connection_database.php');

class ActorClass
{
    private $id;
    private $nombres;
    private $apellidos;
    private $fecha_nacimiento;
    private $nacionalidad;

    public function __construct($id = null, $nombres = null, $apellidos = null, $fecha_nacimiento = null, $nacionalidad = null)
    {
        $this->id = $id;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->nacionalidad = $nacionalidad;
    }

    // Métodos getters y setters
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNombres()
    {
        return $this->nombres;
    }

    public function setNombres($nombres)
    {
        $this->nombres = $nombres;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    public function getFechaNacimiento()
    {
        return $this->fecha_nacimiento;
    }

    public function setFechaNacimiento($fecha_nacimiento)
    {
        $this->fecha_nacimiento = $fecha_nacimiento;
    }

    public function getNacionalidad()
    {
        return $this->nacionalidad;
    }

    public function setNacionalidad($nacionalidad)
    {
        $this->nacionalidad = $nacionalidad;
    }



    public static function consultActor()
    {
        $mysqli = initConnectionDb();
        try {
            // Realizar la consulta
            $query = $mysqli->query("SELECT * FROM actores");

            if (!$query) {
                throw new Exception("Error en la consulta SQL: " . $mysqli->error);
            }

            // Almacenar los resultados en un arreglo
            $listActors = [];
            while ($itemListActors = $query->fetch_assoc()) {
                $listActors[] = new ActorClass(
                    $itemListActors['id'],
                    $itemListActors['nombres'],
                    $itemListActors['apellidos'],
                    $itemListActors['fecha_nacimiento'],
                    $itemListActors['nacionalidad']
                );
            }
            return $listActors;
        } finally {
            $mysqli->close();
        }
    }

    public function update()
    {
        $db = initConnectionDb();
        try {
            // Verificar que el ID y los datos estén presentes
            if (empty($this->id) || empty($this->nombres) || empty($this->apellidos) || empty($this->fecha_nacimiento) || empty($this->nacionalidad)) {
                throw new Exception("ID, nombres, apellidos, fecha de nacimiento y nacionalidad son obligatorios para actualizar.");
            }

            // Preparar la consulta SQL
            $stmt = $db->prepare("UPDATE actores SET nombres = ?, apellidos = ?, fecha_nacimiento = ?, nacionalidad = ? WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }

            // Enlazar los parámetros
            $stmt->bind_param("ssssi", $this->nombres, $this->apellidos, $this->fecha_nacimiento, $this->nacionalidad, $this->id);

            // Ejecutar la consulta
            $result = $stmt->execute();

            // Verificar si hubo un error en la ejecución
            if (!$result) {
                throw new Exception("Error al actualizar el actor: " . $stmt->error);
            }

            return $result;
        } finally {
            // Cerrar el statement y la conexión
            $stmt->close();
            $db->close();
        }
    }





    public static function findById($id)
    {
        $db = initConnectionDb();
        try {
            if (empty($id)) {
                throw new Exception("El ID es obligatorio para buscar un actor.");
            }

            $stmt = $db->prepare("SELECT * FROM actores WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }

            $stmt->bind_param("i", $id);
            $stmt->execute();

            $result = $stmt->get_result()->fetch_assoc();

            if (!$result) {
                return null;
            }

            // Asegúrate de que todos los campos relevantes se pasen al constructor
            return new ActorClass(
                $result['id'],
                $result['nombres'],
                $result['apellidos'],
                $result['fecha_nacimiento'],
                $result['nacionalidad']
            );
        } finally {
            $stmt->close();
            $db->close();
        }
    }

    public static function delete($id)
    {
        $db = initConnectionDb();
        try {
            // Validar si el ID está presente
            if (empty($id)) {
                throw new Exception("El ID es obligatorio para eliminar un actor.");
            }

            // Preparar la consulta SQL para eliminar el actor
            $stmt = $db->prepare("DELETE FROM actores WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }

            // Vincular el parámetro ID a la consulta
            $stmt->bind_param("i", $id);

            // Ejecutar la consulta
            $result = $stmt->execute();

            if (!$result) {
                throw new Exception("Error al eliminar el actor: " . $stmt->error);
            }

            return true; // Retornar true si la eliminación fue exitosa
        } finally {
            $stmt->close();
            $db->close();
        }
    }

    public function save()
    {
        $db = initConnectionDb();
        try {
            $stmt = $db->prepare("INSERT INTO actores (nombres, apellidos, fecha_nacimiento, nacionalidad) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }

            $stmt->bind_param("ssss", $this->nombres, $this->apellidos, $this->fecha_nacimiento, $this->nacionalidad);

            return $stmt->execute();
        } finally {
            $stmt->close();
            $db->close();
        }
    }
}
