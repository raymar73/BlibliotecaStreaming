<?php
require_once '../config/connection_database.php';
// require_once(__DIR__ . '/../config/connection_database.php');
class Language
{
    private $id;
    private $name;
    private $isocode;
    public function __construct($idlanguage = null, $namelanguage = null, $isocodelanguage = null)
    {
        $this->id = $idlanguage;
        $this->name = $namelanguage;
        $this->isocode = $isocodelanguage;

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
        return $this->name;
    }

    public function setName($name)
    {
        if (empty($name)) {
            throw new Exception("El nombre no puede estar vacío.");
        }
        $this->name = $name;
    }
    public function getIsocode()
    {
        return $this->isocode;
    }
    public function setIsocode($isocode)
    {
        if (empty($isocode)) {
            throw new Exception("El ISOCODE no puede estar vacío.");
        }
        $this->isocode = $isocode;
    }
    public static function getAll()
    {
        $db = initConnectionDb();
        try {
            $query = $db->query("SELECT * FROM idiomas");

            if (!$query) {
                throw new Exception("Error en la consulta SQL: " . $db->error);
            }

            $listData = [];
            while ($item = $query->fetch_assoc()) {
                $listData[] = new Language($item['id'], $item['nombre'], $item['iso_code']);
            }
            return $listData;
        } finally {
            $db->close();
        }
    }

    public function save()
    {
        $db = initConnectionDb();
        try {
            if (empty($this->name)) {
                throw new Exception("El idioma es obligatorio.");
            }
            // Añadir validación para isocode
            if (empty($this->isocode)) {
                throw new Exception("El código ISO es obligatorio.");
            }
            $stmt = $db->prepare("INSERT INTO idiomas (nombre,iso_code) VALUES (?,?)");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }

            $stmt->bind_param("ss", $this->name, $this->isocode);
            $result = $stmt->execute();

            // Para debug
            error_log("Name: " . $this->name . ", ISO Code: " . $this->isocode);

            if (!$result) {
                throw new Exception("Error al guardar idioma: " . $stmt->error);
            }

            return $result;
        } finally {
            $stmt->close();
            $db->close();
        }
    }

    public function update()
    {
        $db = initConnectionDb();
        try {
            if (empty($this->id)) {
                throw new Exception("ID es obligatorio para actualizar.");
            }

            // Preparamos la consulta
            $stmt = $db->prepare("UPDATE idiomas SET nombre = ?, iso_code = ? WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }

            $stmt->bind_param("ssi", $this->name, $this->isocode, $this->id);
            $result = $stmt->execute();

            if (!$result) {
                throw new Exception("Error al actualizar idioma: " . $stmt->error);
            }

            return $result;
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            $db->close();
        }
    }

    public static function delete($id)
    {
        $db = initConnectionDb();
        try {
            if (empty($id)) {
                throw new Exception("El ID es obligatorio para eliminar un idioma.");
            }

            $stmt = $db->prepare("DELETE FROM idiomas WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }

            $stmt->bind_param("i", $id);
            $result = $stmt->execute();

            if (!$result) {
                throw new Exception("Error al eliminar idioma: " . $stmt->error);
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
                throw new Exception("El ID es obligatorio para buscar un idioma.");
            }

            $stmt = $db->prepare("SELECT * FROM idiomas WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }

            $stmt->bind_param("i", $id);
            $stmt->execute();

            $result = $stmt->get_result()->fetch_assoc();

            if (!$result) {
                return null;
            }

            return new Language($result['id'], $result['nombre'], $result['iso_code']);
        } finally {
            $stmt->close();
            $db->close();
        }
    }
    public static function existsByName($name) {
        $db = initConnectionDb();
        try {
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM idiomas WHERE nombre = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }
    
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            
            return $result['count'] > 0;
        } finally {
            $stmt->close();
            $db->close();
        }
    }
    
    public static function existsByIsocode($isocode) {
        $db = initConnectionDb();
        try {
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM idiomas WHERE iso_code = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }
    
            $stmt->bind_param("s", $isocode);
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