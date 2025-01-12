<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/connection_database.php';
require_once '../models/platform.php'; // Clase Platform
require_once '../models/director.php'; // Clase Director
require_once '../models/actor.php'; // Clase Actor
require_once '../models/language.php'; // Clase Language


class Series {
    private $id;
    private $title;
    private $platformId;
    private $platformName;
    private $directorId;
    private $directorName;
    private $actors; // Lista de actores
    private $audioLanguages; // Idiomas de audio
    private $subtitleLanguages; // Idiomas de subtítulos

    public function __construct($id = null, $title = null, $platformName = null, $directorName = null, $actors = null, $audioLanguages = null, $subtitleLanguages = null) {
        $this->id = $id;
        $this->title = $title;
        $this->platformName = $platformName;
        $this->directorName = $directorName;
        $this->actors = $actors;
        $this->audioLanguages = $audioLanguages;
        $this->subtitleLanguages = $subtitleLanguages;
    }

    // Getters y Setters
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        if (empty($title)) {
            throw new Exception("El título no puede estar vacío.");
        }
        if (strlen($title) > 255) {
            throw new Exception("El título no puede exceder los 255 caracteres.");
        }
        $this->title = $title;
    }

    public function getPlatformName() {
        return $this->platformName;
    }

    public function getDirectorName() {
        return $this->directorName;
    }

    public function getActors() {
        return $this->actors;
    }

    public function getAudioLanguages() {
        return $this->audioLanguages;
    }

    public function getSubtitleLanguages() {
        return $this->subtitleLanguages;
    }
    public function setPlatformId($platformId) {
        if (!filter_var($platformId, FILTER_VALIDATE_INT) && !is_null($platformId)) {
            throw new Exception("El ID de la plataforma debe ser un número entero válido o nulo.");
        }
        $this->platformId = $platformId;
    }
    
    public function setDirectorId($directorId) {
        if (!filter_var($directorId, FILTER_VALIDATE_INT) && !is_null($directorId)) {
            throw new Exception("El ID del director debe ser un número entero válido o nulo.");
        }
        $this->directorId = $directorId;
    }
    
    public function getPlatformId() {
        return $this->platformId;
    }
    
    public function getDirectorId() {
        return $this->directorId;
    }    

    // Obtener todas las series con sus datos relacionados
    public static function getAll() {
        $db = initConnectionDb();
        try {
            $query = $db->query("
                SELECT 
                    s.id, 
                    s.titulo, 
                    p.nombre AS plataforma, 
                    CONCAT(d.nombres, ' ', d.apellidos) AS director,
                    GROUP_CONCAT(DISTINCT CONCAT(a.nombres, ' ', a.apellidos) SEPARATOR ', ') AS actores,
                    GROUP_CONCAT(DISTINCT CASE WHEN si.tipo = 'audio' THEN i.nombre END SEPARATOR ', ') AS audios,
                    GROUP_CONCAT(DISTINCT CASE WHEN si.tipo = 'subtítulo' THEN i.nombre END SEPARATOR ', ') AS subtitulos
                FROM series s
                LEFT JOIN plataformas p ON s.plataforma_id = p.id
                LEFT JOIN directores d ON s.director_id = d.id
                LEFT JOIN series_actores sa ON s.id = sa.serie_id
                LEFT JOIN actores a ON sa.actor_id = a.id
                LEFT JOIN series_idiomas si ON s.id = si.serie_id
                LEFT JOIN idiomas i ON si.idioma_id = i.id
                GROUP BY s.id
            ");

            if (!$query) {
                throw new Exception("Error en la consulta SQL: " . $db->error);
            }

            $series = [];
            while ($row = $query->fetch_assoc()) {
                $serie = new Series(
                    $row['id'], 
                    $row['titulo'], 
                    $row['plataforma'], 
                    $row['director'], 
                    $row['actores'], 
                    $row['audios'], 
                    $row['subtitulos']
                );
                $series[] = $serie;
            }

            return $series;
        } finally {
            $db->close();
        }
    }

    // Buscar una serie por su ID
    public static function findById($id) {
        $db = initConnectionDb();
        try {
            if (empty($id)) {
                throw new Exception("El ID es obligatorio para buscar una serie.");
            }

            $stmt = $db->prepare("
                SELECT s.*, 
                       p.nombre AS plataforma, 
                       CONCAT(d.nombres, ' ', d.apellidos) AS director,
                       GROUP_CONCAT(DISTINCT CONCAT(a.nombres, ' ', a.apellidos) SEPARATOR ', ') AS actores, 
                       GROUP_CONCAT(DISTINCT CASE WHEN si.tipo = 'audio' THEN i.nombre END SEPARATOR ', ') AS audios,
                       GROUP_CONCAT(DISTINCT CASE WHEN si.tipo = 'subtítulo' THEN i.nombre END SEPARATOR ', ') AS subtitulos
                FROM series s
                LEFT JOIN plataformas p ON s.plataforma_id = p.id
                LEFT JOIN directores d ON s.director_id = d.id
                LEFT JOIN series_actores sa ON s.id = sa.serie_id
                LEFT JOIN actores a ON sa.actor_id = a.id
                LEFT JOIN series_idiomas si ON s.id = si.serie_id
                LEFT JOIN idiomas i ON si.idioma_id = i.id
                WHERE s.id = ?
                GROUP BY s.id
            ");

            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }

            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            if (!$result) {
                return null;
            }

            return new Series(
                $result['id'], 
                $result['titulo'], 
                $result['plataforma'], 
                $result['director'], 
                $result['actores'], 
                $result['audios'], 
                $result['subtítulos']
            );
        } finally {
            $stmt->close();
            $db->close();
        }
    }

    // Guardar una nueva serie
    public function save($actorIds = [], $languages = []) {
        $db = initConnectionDb();
        try {

            $db->begin_transaction();
    
            // Inserta la serie en la tabla series
            $stmt = $db->prepare("INSERT INTO series (titulo, plataforma_id, director_id) VALUES (?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }
    
            $stmt->bind_param("sii", $this->title, $this->platformId, $this->directorId);
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            $this->id = $stmt->insert_id; // Recupera el ID de la serie recién insertada
    
            // Inserta relaciones con actores
            if (!empty($actorIds)) {
                foreach ($actorIds as $actorId) {
                    error_log("Intentando insertar en series_actores: Serie ID = $this->id, Actor ID = $actorId");

                    $stmtActor = $db->prepare("INSERT INTO series_actores (serie_id, actor_id) VALUES (?, ?)");
                    if (!$stmtActor) {
                        throw new Exception("Error al preparar la consulta para actores: " . $db->error);
                    }
                    $stmtActor->bind_param("ii", $this->id, $actorId);
                    if (!$stmtActor->execute()) {
                        throw new Exception("Error al insertar actor_id $actorId: " . $stmtActor->error);
                    }
                    error_log("Actor ID $actorId insertado correctamente en series_actores.");
                    $stmtActor->close();

                    // Agrega un delay de 2 segundos (2,000,000 microsegundos)
                    //usleep(2000000);
                }
            }

            // Inserta relaciones con idiomas
            if (!empty($languages)) {
                foreach ($languages as $language) {
                    error_log("Intentando insertar en series_idiomas: Serie ID = $this->id, Idioma ID = {$language['idioma_id']}, Tipo = {$language['tipo']}");

                    $stmtLanguage = $db->prepare("INSERT INTO series_idiomas (serie_id, idioma_id, tipo) VALUES (?, ?, ?)");
                    if (!$stmtLanguage) {
                        throw new Exception("Error al preparar la consulta para idiomas: " . $db->error);
                    }
                    $languageId = (int) $language['idioma_id']; // Asegura que el idioma_id sea un entero
                    $languageType = $language['tipo']; // 'audio' o 'subtítulo'
                    $stmtLanguage->bind_param("iis", $this->id, $languageId, $languageType);
                    if (!$stmtLanguage->execute()) {
                        throw new Exception("Error al insertar idioma_id $languageId con tipo $languageType: " . $stmtLanguage->error);
                    }
                    error_log("Idioma ID $languageId con tipo $languageType insertado correctamente en series_idiomas.");
                    $stmtLanguage->close();

                    // Agrega un delay de 2 segundos (2,000,000 microsegundos)
                    //usleep(2000000);
                }
            }
    
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollback();
            error_log("Error al guardar la serie: " . $e->getMessage());
            throw $e;
        } finally {
            $db->close();
        }
    }       

    // Actualizar una serie existente
    public function update($actorIds = [], $languages = []) {
        $db = initConnectionDb();
        try {
            $db->begin_transaction();

            $stmt = $db->prepare("UPDATE series SET titulo = ?, plataforma_id = ?, director_id = ? WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $db->error);
            }
            $stmt->bind_param("siii", $this->title, $this->platformId, $this->directorId, $this->id);
            $stmt->execute();

            $db->query("DELETE FROM series_actores WHERE serie_id = {$this->id}");
            foreach ($actorIds as $actorId) {
                $stmtActor = $db->prepare("INSERT INTO series_actores (serie_id, actor_id) VALUES (?, ?)");
                if (!$stmtActor) {
                    throw new Exception("Error al actualizar los actores relacionados: " . $db->error);
                }
                $stmtActor->bind_param("ii", $this->id, $actorId);
                $stmtActor->execute();
            }

            $db->query("DELETE FROM series_idiomas WHERE serie_id = {$this->id}");
            foreach ($languages as $language) {
                $stmtLanguage = $db->prepare("INSERT INTO series_idiomas (serie_id, idioma_id, tipo) VALUES (?, ?, ?)");
                if (!$stmtLanguage) {
                    throw new Exception("Error al actualizar los idiomas relacionados: " . $db->error);
                }
                $stmtLanguage->bind_param("iis", $this->id, $language['idioma_id'], $language['tipo']);
                $stmtLanguage->execute();
            }

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        } finally {
            $db->close();
        }
    }

    public function setId($id) {
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            throw new Exception("El ID debe ser un número entero válido.");
        }
        $this->id = $id;
    }

    // Obtiene la informacionde series y de las tablas intermedias series-actores, series-idiomas
    public static function getDetailedInfo($id) {
        $db = initConnectionDb();
        try {
            if (empty($id)) {
                throw new Exception("El ID de la serie es obligatorio.");
            }
    
            // Consultar información básica de la serie
            $stmt = $db->prepare("
                SELECT s.id, s.titulo, s.plataforma_id, s.director_id,
                       p.nombre AS plataforma, 
                       CONCAT(d.nombres, ' ', d.apellidos) AS director
                FROM series s
                LEFT JOIN plataformas p ON s.plataforma_id = p.id
                LEFT JOIN directores d ON s.director_id = d.id
                WHERE s.id = ?
            ");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $seriesData = $stmt->get_result()->fetch_assoc();
    
            if (!$seriesData) {
                throw new Exception("No se encontró la serie con el ID proporcionado.");
            }
    
            // Consultar actores asociados
            $actorsQuery = $db->prepare("
                SELECT a.id, CONCAT(a.nombres, ' ', a.apellidos) AS nombre_completo
                FROM series_actores sa
                INNER JOIN actores a ON sa.actor_id = a.id
                WHERE sa.serie_id = ?
            ");
            $actorsQuery->bind_param("i", $id);
            $actorsQuery->execute();
            $actorsResult = $actorsQuery->get_result();
            $actors = [];
            while ($actor = $actorsResult->fetch_assoc()) {
                $actors[] = $actor;
            }
    
            // Consultar idiomas de audio asociados
            $audioQuery = $db->prepare("
                SELECT i.id, i.nombre
                FROM series_idiomas si
                INNER JOIN idiomas i ON si.idioma_id = i.id
                WHERE si.serie_id = ? AND si.tipo = 'audio'
            ");
            $audioQuery->bind_param("i", $id);
            $audioQuery->execute();
            $audioResult = $audioQuery->get_result();
            $audioLanguages = [];
            while ($audio = $audioResult->fetch_assoc()) {
                $audioLanguages[] = $audio;
            }
    
            // Consultar idiomas de subtítulos asociados
            $subtitleQuery = $db->prepare("
                SELECT i.id, i.nombre
                FROM series_idiomas si
                INNER JOIN idiomas i ON si.idioma_id = i.id
                WHERE si.serie_id = ? AND si.tipo = 'subtítulo'
            ");
            $subtitleQuery->bind_param("i", $id);
            $subtitleQuery->execute();
            $subtitleResult = $subtitleQuery->get_result();
            $subtitleLanguages = [];
            while ($subtitle = $subtitleResult->fetch_assoc()) {
                $subtitleLanguages[] = $subtitle;
            }
    
            return [
                'series' => $seriesData,
                'actors' => $actors,
                'audio_languages' => $audioLanguages,
                'subtitle_languages' => $subtitleLanguages
            ];
        } finally {
            $db->close();
        }
    }    

    // Eliminar una serie y sus relaciones
    public static function delete($id) {
        $db = initConnectionDb();
        try {
            $stmt = $db->prepare("DELETE FROM series WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta de eliminación: " . $db->error);
            }
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } finally {
            $db->close();
        }
    }
}
?>
