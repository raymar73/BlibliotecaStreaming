<?php
require_once '../config/connection_database.php';

class Platform {
    private $id;
    private $name;

    public function __construct($idPlatform = null, $namePlatform = null) {
        $this->id = $idPlatform;
        $this->name = $namePlatform;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        if (empty($name)) {
            throw new Exception("El nombre no puede estar vacío.");
        }
        $this->name = $name;
    }

    public static function getAll() {
        $db = initConnectionDb();
        try {
            $query = $db->query("SELECT * FROM plataformas");

            if (!$query) {
                throw new Exception("Error en la consulta SQL: " . $db->error);
            }

            $listData = [];
            while ($item = $query->fetch_assoc()) {
                $listData[] = new Platform($item['id'], $item['nombre']);
            }
            return $listData;
        } finally {
            $db->close();
        }
    }

    public function save() {
        $db = initConnectionDb();
        try {
            if (empty($this->name)) {
                throw new Exception("El nombre de la plataforma es obligatorio.");
            }

            $stmt = $db->prepare("INSERT INTO plataformas (nombre) VALUES (?)");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }

            $stmt->bind_param("s", $this->name);
            $result = $stmt->execute();

            if (!$result) {
                throw new Exception("Error al guardar la plataforma: " . $stmt->error);
            }

            return $result;
        } finally {
            $stmt->close();
            $db->close();
        }
    }

    public function update() {
        $db = initConnectionDb();
        try {
            if (empty($this->id) || empty($this->name)) {
                throw new Exception("ID y nombre son obligatorios para actualizar.");
            }

            $stmt = $db->prepare("UPDATE plataformas SET nombre = ? WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }

            $stmt->bind_param("si", $this->name, $this->id);
            $result = $stmt->execute();

            if (!$result) {
                throw new Exception("Error al actualizar la plataforma: " . $stmt->error);
            }

            return $result;
        } finally {
            $stmt->close();
            $db->close();
        }
    }

    public static function delete($id) {
        $db = initConnectionDb();
        try {
            if (empty($id)) {
                throw new Exception("El ID es obligatorio para eliminar una plataforma.");
            }

            $stmt = $db->prepare("DELETE FROM plataformas WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }

            $stmt->bind_param("i", $id);
            $result = $stmt->execute();

            if (!$result) {
                throw new Exception("Error al eliminar la plataforma: " . $stmt->error);
            }

            return $result;
        } finally {
            $stmt->close();
            $db->close();
        }
    }

    public static function findById($id) {
        $db = initConnectionDb();
        try {
            if (empty($id)) {
                throw new Exception("El ID es obligatorio para buscar una plataforma.");
            }

            $stmt = $db->prepare("SELECT * FROM plataformas WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }

            $stmt->bind_param("i", $id);
            $stmt->execute();

            $result = $stmt->get_result()->fetch_assoc();

            if (!$result) {
                return null;
            }

            return new Platform($result['id'], $result['nombre']);
        } finally {
            $stmt->close();
            $db->close();
        }
    }
}
?>
