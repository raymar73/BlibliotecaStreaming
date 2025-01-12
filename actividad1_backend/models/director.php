<?php
require_once '../config/connection_database.php';
class Director
{
    private $id;
    private $name;
    private $last_name;
    private $date_of_birth;
    private $nationality;

    public function __construct($idDirector = null, $nameDirector = null, $last_nameDirector = null, $date_of_birthDirector = null, $nationalityDirector = null)
    {
        $this->id = $idDirector;
        $this->name = $nameDirector;
        $this->last_name = $last_nameDirector;
        $this->date_of_birth = $date_of_birthDirector;
        $this->nationality = $nationalityDirector;

    }
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name ?? '';
    }

    public function setName($name)
    {
        if (empty($name)) {
            throw new Exception("El nombre no puede estar vacío.");
        }
        $this->name = $name;
    }

    public function getlast_name()
    {
        return $this->last_name ?? '';
    }

    public function setlast_name($last_name)
    {
        if (empty($last_name)) {
            throw new Exception("El apellido no puede estar vacío.");
        }
        $this->last_name = $last_name;
    }

    public function getdate_of_birth()
    {
        return $this->date_of_birth ?? '';
    }

    public function setDateOfBirth($date_of_birth)
    {
        if (empty($date_of_birth)) {
            throw new Exception("la fech no puede estar vacío.");
        }
        $this->date_of_birth = $date_of_birth;
    }

    public function getnationality()
    {
        return $this->nationality ?? '';
    }

    public function setnationality($nationality)
    {
        if (empty($nationality)) {
            throw new Exception("La nacionalidad no puede estar vacío.");
        }
        $this->nationality = $nationality;
    }
    public static function getAll()
    {
        $db = initConnectionDb();
        try {
            $query = $db->query("SELECT * FROM directores");

            if (!$query) {
                throw new Exception("Error en la consulta SQL: " . $db->error);
            }

            $listData = [];
            while ($item = $query->fetch_assoc()) {
                $listData[] = new Director($item['id'], $item['nombres'], $item['apellidos'], $item['fecha_nacimiento'], $item['nacionalidad']);
            }
            return $listData;
        } finally {
            $db->close();
        }
    }

    public function save()
    {
        $db = initConnectionDb();
        if (
            empty($this->name) || empty($this->last_name) ||
            empty($this->date_of_birth) || empty($this->nationality)
        ) {
            $db->close();
            throw new Exception("Todos los campos son obligatorios.");
        }

        // Verificar si existe el nombre y apellido
        $checkStmt = $db->prepare("SELECT id FROM directores WHERE nombres = ? AND apellidos = ?");
        if (!$checkStmt) {
            $db->close();
            throw new Exception("Error al preparar la consulta de verificación");
        }

        $checkStmt->bind_param("ss", $this->name, $this->last_name);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            $checkStmt->close();
            $db->close();
            $_SESSION['error_message'] = "El nombre y apellido ya existe.";
        }
        $checkStmt->close();

        $stmt = $db->prepare("INSERT INTO directores (nombres, apellidos, fecha_nacimiento, nacionalidad) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            $db->close();
            throw new Exception("Error al preparar la consulta: " . $db->error);
        }

        try {
            $stmt->bind_param("ssss", $this->name, $this->last_name, $this->date_of_birth, $this->nationality);
            $result = $stmt->execute();
            $stmt->close();
            $db->close();
            return $result;
        } catch (Exception $e) {
            $stmt->close();
            $db->close();
            throw $e;
        }
    }
    public function update() {
        $db = initConnectionDb();
        try {
            if (empty($this->id) || empty($this->name)) {
                throw new Exception("ID y nombre son obligatorios para actualizar.");
            }
    
            // Verificar duplicados excluyendo el ID actual
            $checkStmt = $db->prepare("SELECT id FROM directores WHERE nombres = ? AND apellidos = ? AND id != ?");
            if (!$checkStmt) {
                throw new Exception("Error en la consulta de verificación");
            }
    
            $checkStmt->bind_param("ssi", $this->name, $this->last_name, $this->id);
            $checkStmt->execute();
            if ($checkStmt->get_result()->num_rows > 0) {
                throw new Exception('<div class="text-center"><p>Ya existe un director registrado con ese nombre y apellidos.</p></div>');
            }
            $checkStmt->close();
    
            $stmt = $db->prepare("UPDATE directores SET nombres = ?, apellidos = ?, fecha_nacimiento = ?, nacionalidad = ? WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }
    
            $stmt->bind_param("ssssi", $this->name, $this->last_name, $this->date_of_birth, $this->nationality, $this->id);
            return $stmt->execute();
        } finally {
            if (isset($stmt)) $stmt->close();
            $db->close();
        }
    }

    public static function delete($id)
    {
        $db = initConnectionDb();
        try {
            if (empty($id)) {
                throw new Exception("El ID es obligatorio para eliminar un director.");
            }

            $stmt = $db->prepare("DELETE FROM directores WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }

            $stmt->bind_param("i", $id);
            $result = $stmt->execute();

            if (!$result) {
                throw new Exception("Error al eliminar nombre director: " . $stmt->error);
            }

            return $result;
        } finally {
            $stmt->close();
            $db->close();
        }
    }

    public static function findById($id)
    {
        $db = initConnectionDb();
        try {
            if (empty($id)) {
                throw new Exception("El ID es obligatorio para buscar directores.");
            }

            $stmt = $db->prepare("SELECT * FROM directores WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }

            $stmt->bind_param("i", $id);
            $stmt->execute();

            $result = $stmt->get_result()->fetch_assoc();

            if (!$result) {
                return null;
            }

            return new Director(
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
    
    public static function existsDirector($name, $last_name, $excludeId = null) {
        $db = initConnectionDb();
        try {
            $sql = "SELECT COUNT(*) as count FROM directores WHERE nombres = ? AND apellidos = ?";
            $params = [$name, $last_name];
            $types = "ss";
            
            if ($excludeId !== null) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
                $types .= "i";
            }
            
            $stmt = $db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }
        
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            
            return $result['count'] > 0;
        } finally {
            $stmt->close();
            $db->close();
        }
    }

}
?>