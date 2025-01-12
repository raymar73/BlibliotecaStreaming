<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../models/series.php');
require_once(__DIR__ . '/../models/platform.php');
require_once(__DIR__ . '/../models/director.php');
require_once(__DIR__ . '/../models/actor.php');
require_once(__DIR__ . '/../models/language.php');

class SeriesController {
    public function list() {
        try {
            $series = Series::getAll(); // Asegúrate de que esta función devuelve un array.
            if (!is_array($series)) {
                $series = []; // Asegurarte de que $series siempre sea un array.
            }
            require_once '../views/series/list.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error al obtener las series: " . $e->getMessage();
            header('Location: ../routes/router.php?path=series/list');
            exit;
        }
    }    

    public function create() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Log para depurar datos recibidos
                $this->writeLog("POST DATA recibido:\n" . print_r($_POST, true));
    
                // Capturar y validar datos del formulario
                $title = trim($_POST['title'] ?? '');
                $platformId = $_POST['platform_id'] ?? null;
                $directorId = $_POST['director_id'] ?? null;
                $actorIds = $_POST['actors'] ?? [];
                $languagesAudio = $_POST['languages_audio'] ?? [];
                $languagesSubtitles = $_POST['languages_subtitles'] ?? [];
    
                // Convertir JSON a arrays planos
                $actorIds = $this->processArray($actorIds);
                $languagesAudio = $this->processArray($languagesAudio);
                $languagesSubtitles = $this->processArray($languagesSubtitles);
    
                // Validar que sean arrays válidos
                /*if (empty($actorIds) || !is_array($actorIds)) {
                    throw new Exception("Error: Actores no es un array válido.");
                }
                if (empty($languagesAudio) || !is_array($languagesAudio)) {
                    throw new Exception("Error: Idiomas de audio no es un array válido.");
                }
                if (empty($languagesSubtitles) || !is_array($languagesSubtitles)) {
                    throw new Exception("Error: Idiomas de subtítulos no es un array válido.");
                }*/
                if (
                    empty($title) || 
                    empty($platformId) || 
                    empty($directorId) || 
                    empty($actorIds) || 
                    empty($languagesAudio) || 
                    empty($languagesSubtitles)
                ) {
                    $_SESSION['error_message'] = "Todos los campos deben estar llenos.";
                    header('Location: ../routes/router.php?path=series/list');
                    exit;
                }

                // Validar longitud y formato
                if (strlen($title) > 255) {
                    throw new Exception("El título de la serie no puede exceder los 255 caracteres.");
                }
                if (!filter_var($platformId, FILTER_VALIDATE_INT)) {
                    throw new Exception("El ID de la plataforma no es válido.");
                }
                if (!filter_var($directorId, FILTER_VALIDATE_INT)) {
                    throw new Exception("El ID del director no es válido.");
                }

                // Validar que cada actor sea un ID válido
                foreach ($actorIds as $actorId) {
                    if (!filter_var($actorId, FILTER_VALIDATE_INT)) {
                        throw new Exception("Uno o más actores no tienen un ID válido.");
                    }
                }

                // Validar que cada idioma sea un ID válido
                foreach (array_merge($languagesAudio, $languagesSubtitles) as $languageId) {
                    if (!filter_var($languageId, FILTER_VALIDATE_INT)) {
                        throw new Exception("Uno o más idiomas no tienen un ID válido.");
                    }
                }
    
                // Convertir idiomas en el formato esperado
                $languages = array_merge(
                    array_map(fn($id) => ['idioma_id' => (int)$id, 'tipo' => 'audio'], $languagesAudio),
                    array_map(fn($id) => ['idioma_id' => (int)$id, 'tipo' => 'subtítulo'], $languagesSubtitles)
                );
    
                // Log de datos procesados
                //$this->writeLog("Actor IDs:\n" . print_r($actorIds, true));
                //$this->writeLog("Idiomas listos:\n" . print_r($languages, true));
    
                // Instanciar el modelo de Series
                $series = new Series();
                $series->setTitle($title);
                $series->setPlatformId((int)$platformId);
                $series->setDirectorId((int)$directorId);
    
                // Guardar la serie
                $saveSuccess = $series->save($actorIds, $languages);
                if ($saveSuccess) {
                    $this->writeLog("Serie y relaciones guardadas correctamente.");
                    $_SESSION['success_message'] = "Serie creada exitosamente.";
                    header('Location: ../routes/router.php?path=series/list');
                    exit;
                } else {
                    $_SESSION['error_message'] = "Error al guardar la serie, por favor intente más tarde.";
                    header('Location: ../routes/router.php?path=series/list');
                    exit;
                } 
            } else {
                // Cargar datos para la vista
                $platforms = Platform::getAll();
                $directors = Director::getAll();
                $actors = ActorClass::consultActor();
                $languages = Language::getAll();
    
                require_once '../views/series/create.php';
            }
        } catch (Exception $e) {
            $errorDetails = [
                "Título" => $title ?? 'N/A',
                "Platform ID" => $platformId,
                "Director ID" => $directorId,
                "Actor IDs" => $actorIds,
                "Languages Audio" => $languagesAudio,
                "Languages Subtitles" => $languagesSubtitles
            ];
            $_SESSION['error_message'] = "Error al crear la serie: " . $e->getMessage() . "\nDetalles:\n" . json_encode($errorDetails, JSON_PRETTY_PRINT);
            //$this->writeLog("Error al crear la serie:\n" . json_encode($errorDetails, JSON_PRETTY_PRINT));
            header('Location: ../routes/router.php?path=series/create');
            exit;
        }
    }
    
    public function edit($id) {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Log para depurar datos recibidos
                $this->writeLog("POST DATA recibido para editar:\n" . print_r($_POST, true));
    
                // Capturar y validar datos del formulario
                $title = trim($_POST['title'] ?? '');
                $platformId = $_POST['platform_id'] ?? null;
                $directorId = $_POST['director_id'] ?? null;
                $actorIds = $_POST['actors'] ?? [];
                $languagesAudio = $_POST['languages_audio'] ?? [];
                $languagesSubtitles = $_POST['languages_subtitles'] ?? [];
    
                // Convertir JSON a arrays planos
                $actorIds = $this->processArray($actorIds);
                $languagesAudio = $this->processArray($languagesAudio);
                $languagesSubtitles = $this->processArray($languagesSubtitles);
    
                // Validar que sean arrays válidos
                /*if (!is_array($actorIds)) {
                    throw new Exception("Error: Actores no es un array válido.");
                }
                if (!is_array($languagesAudio)) {
                    throw new Exception("Error: Idiomas de audio no es un array válido.");
                }
                if (!is_array($languagesSubtitles)) {
                    throw new Exception("Error: Idiomas de subtítulos no es un array válido.");
                }*/

                if (
                    empty($title) || 
                    empty($platformId) || 
                    empty($directorId) || 
                    empty($actorIds) || 
                    empty($languagesAudio) || 
                    empty($languagesSubtitles)
                ) {
                    $_SESSION['error_message'] = "Todos los campos deben estar llenos.";
                    header('Location: ../routes/router.php?path=series/list');
                    exit;
                }

                // Validar longitud y formato
                if (strlen($title) > 255) {
                    throw new Exception("El título de la serie no puede exceder los 255 caracteres.");
                }
                if (!filter_var($platformId, FILTER_VALIDATE_INT)) {
                    throw new Exception("El ID de la plataforma no es válido.");
                }
                if (!filter_var($directorId, FILTER_VALIDATE_INT)) {
                    throw new Exception("El ID del director no es válido.");
                }

                // Validar que cada actor sea un ID válido
                foreach ($actorIds as $actorId) {
                    if (!filter_var($actorId, FILTER_VALIDATE_INT)) {
                        throw new Exception("Uno o más actores no tienen un ID válido.");
                    }
                }

                // Validar que cada idioma sea un ID válido
                foreach (array_merge($languagesAudio, $languagesSubtitles) as $languageId) {
                    if (!filter_var($languageId, FILTER_VALIDATE_INT)) {
                        throw new Exception("Uno o más idiomas no tienen un ID válido.");
                    }
                }
    
                // Convertir idiomas en el formato esperado
                $languages = array_merge(
                    array_map(fn($id) => ['idioma_id' => (int)$id, 'tipo' => 'audio'], $languagesAudio),
                    array_map(fn($id) => ['idioma_id' => (int)$id, 'tipo' => 'subtítulo'], $languagesSubtitles)
                );
    
                // Log de datos procesados
                //$this->writeLog("Actor IDs:\n" . print_r($actorIds, true));
                //$this->writeLog("Idiomas listos para actualizar:\n" . print_r($languages, true));
    
                // Instanciar el modelo de Series
                $series = new Series();
                $series->setId((int)$id);
                $series->setTitle($title);
                $series->setPlatformId((int)$platformId);
                $series->setDirectorId((int)$directorId);
    
                // Actualizar la serie
                $updateSuccess = $series->update($actorIds, $languages);
                if ($updateSuccess) {
                    //$this->writeLog("Serie y relaciones actualizadas correctamente.");
                    $_SESSION['success_message'] = "Serie actualizada exitosamente.";
                    header('Location: ../routes/router.php?path=series/list');
                    exit;
                } else {
                    $_SESSION['error_message'] = "Error al actualizar la serie, por favor intente más tarde.";
                    header('Location: ../routes/router.php?path=series/list');
                    exit;
                }
            } else {
                // Obtener la información detallada de la serie
                $seriesInfo = Series::getDetailedInfo($id);
    
                if (!$seriesInfo) {
                    $_SESSION['error_message'] = "Serie no encontrada.";
                    header('Location: ../routes/router.php?path=series/list');
                    exit;
                }
    
                // Cargar datos para la vista
                $platforms = Platform::getAll();
                $directors = Director::getAll();
                $actors = ActorClass::consultActor();
                $languages = Language::getAll();
    
                require_once '../views/series/edit.php';
            }
        } catch (Exception $e) {
            $errorDetails = [
                "Título" => $title ?? 'N/A',
                "Platform ID" => $platformId,
                "Director ID" => $directorId,
                "Actor IDs" => $actorIds ?? [],
                "Languages Audio" => $languagesAudio ?? [],
                "Languages Subtitles" => $languagesSubtitles ?? []
            ];
            $_SESSION['error_message'] = "Error al actualizar la serie: " . $e->getMessage() . "\nDetalles:\n" . json_encode($errorDetails, JSON_PRETTY_PRINT);
            //$this->writeLog("Error al actualizar la serie:\n" . json_encode($errorDetails, JSON_PRETTY_PRINT));
            header('Location: ../routes/router.php?path=series/edit&id=' . $id);
            exit;
        }
    }
    
    public function delete($id) {
        try {
            // Validar que el ID sea un número entero válido
            if (!filter_var($id, FILTER_VALIDATE_INT)) {
                $_SESSION['error_message'] = "ID inválido.";
                header('Location: ../routes/router.php?path=series/list');
                exit;
            }
    
            // Eliminar la serie
            if (Series::delete($id)) {
                $_SESSION['success_message'] = "Serie eliminada exitosamente.";
                header('Location: ../routes/router.php?path=series/list');
                exit;
            } else {
                $_SESSION['error_message'] = "Error al eliminar la serie.";
                header('Location: ../routes/router.php?path=series/list');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error al eliminar la serie: " . $e->getMessage();
            header('Location: ../routes/router.php?path=series/list');
            exit;
        }
    }    

    // Función para procesar arrays anidados o cadenas JSON
    private function processArray($input) {
        $flatArray = [];
        foreach ($input as $item) {
            if (is_string($item)) {
                // Intentar decodificar si es JSON
                $decoded = json_decode($item, true);
                if (is_array($decoded)) {
                    $flatArray = array_merge($flatArray, $decoded);
                } else {
                    $flatArray[] = $item;
                }
            } elseif (is_array($item)) {
                $flatArray = array_merge($flatArray, $this->processArray($item));
            } else {
                $flatArray[] = $item;
            }
        }
        return $flatArray;
    }

    // Función para escribir logs en un archivo .txt en la misma ruta del controlador
    private function writeLog($message) {
        $logFile = __DIR__ . '/debug.txt';

        if (!file_exists($logFile)) {
            file_put_contents($logFile, "Archivo de log creado el " . date('Y-m-d H:i:s') . PHP_EOL);
        }

        if (!is_writable($logFile)) {
            error_log("El archivo de log no es escribible: $logFile");
            return;
        }

        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
    }
}
