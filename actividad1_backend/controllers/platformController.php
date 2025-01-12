<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// require_once '../models/platform.php';
require_once(__DIR__ . '/../models/platform.php');

class PlatformController {
    public function list() {
        try {
            $platforms = Platform::getAll();
            // require_once '../views/platforms/list.php';
            require_once(__DIR__ . '/../views/platforms/list.php');
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error al obtener las plataformas: " . $e->getMessage();
            header('Location: ../routes/router.php?path=platforms/list');
            exit;
        }
    }

    public function create() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = trim($_POST['name'] ?? '');
    
                if (empty($name)) {
                    $_SESSION['error_message'] = "El nombre de la plataforma es obligatorio.";
                    header('Location: ../routes/router.php?path=platforms/create');
                    exit;
                }
                if (strlen($name) > 150) {
                    $_SESSION['error_message'] = "El nombre de la plataforma no puede exceder los 150 caracteres.";
                    header('Location: ../routes/router.php?path=platforms/create');
                    exit;
                }
                if (strlen($name) < 3) {
                    $_SESSION['error_message'] = "El nombre de la plataforma debe tener al menos 3 caracteres.";
                    header('Location: ../routes/router.php?path=platforms/create');
                    exit;
                }
                if (!preg_match('/^[a-zA-Z0-9\s]+$/', $name)) {
                    $_SESSION['error_message'] = "El nombre de la plataforma contiene caracteres no válidos. Por favor, usa solo letras, números y espacios.";
                    header('Location: ../routes/router.php?path=platforms/create');
                    exit;
                }
                

    
                $platform = new Platform(null, $name);
                if ($platform->save()) {
                    $_SESSION['success_message'] = "Plataforma creada exitosamente.";
                    error_log("Mensaje de éxito configurado: " . $_SESSION['success_message']); // Registro
                    header('Location: ../routes/router.php?path=platforms/list');
                    exit;
                } else {
                    $_SESSION['error_message'] = "Error al guardar la plataforma. porfavor intentelo más tarde.";
                    header('Location: ../routes/router.php?path=platforms/create');
                    exit;
                }
            } else {
                require_once '../views/platforms/create.php';
            }
        } catch (Exception $e) {
            if (strpos($e, 'Duplicate entry') !== false) {
                $_SESSION['error_message'] = "El nombre de la plataforma ya existe. Por favor, elige un nombre diferente.";
                header('Location: ../routes/router.php?path=platforms/create');
                exit;
            } else {
                $_SESSION['error_message'] = "Error al crear la plataforma: " . $e->getMessage();
                header('Location: ../routes/router.php?path=platforms/create');
                exit;
            }
            
            
        }
    }    

    public function edit($id) {
        try {
            // Validar que el ID sea un número entero válido
            if (!filter_var($id, FILTER_VALIDATE_INT)) {
                $_SESSION['error_message'] = "ID inválido.";
                header('Location: ../routes/router.php?path=platforms/list');
                exit;
            }

            // Buscar la plataforma por ID
            $platform = Platform::findById($id);
            if (!$platform) {
                $_SESSION['error_message'] = "Plataforma no encontrada.";
                header('Location: ../routes/router.php?path=platforms/list');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = trim($_POST['name'] ?? '');

                // Validar que el nombre no esté vacío
                if (empty($name)) {
                    $_SESSION['error_message'] = "El nombre de la plataforma es obligatorio.";
                    header('Location: ../routes/router.php?path=platforms/edit&id=' . $id);
                    exit;
                }
                if (strlen($name) > 150) {
                    $_SESSION['error_message'] = "El nombre de la plataforma no puede exceder los 150 caracteres.";
                    header('Location: ../routes/router.php?path=platforms/create');
                    exit;
                }
                if (strlen($name) < 3) {
                    $_SESSION['error_message'] = "El nombre de la plataforma debe tener al menos 3 caracteres.";
                    header('Location: ../routes/router.php?path=platforms/create');
                    exit;
                }
                if (!preg_match('/^[a-zA-Z0-9\s]+$/', $name)) {
                    $_SESSION['error_message'] = "El nombre de la plataforma contiene caracteres no válidos. Por favor, usa solo letras, números y espacios.";
                    header('Location: ../routes/router.php?path=platforms/create');
                    exit;
                }

                // Actualizar la plataforma
                $platform->setName($name);
                if ($platform->update()) {
                    $_SESSION['success_message'] = "Plataforma actualizada exitosamente.";
                    header('Location: ../routes/router.php?path=platforms/list');
                    exit;
                } else {
                    $_SESSION['error_message'] = "Error al actualizar la plataforma.";
                    header('Location: ../routes/router.php?path=platforms/edit&id=' . $id);
                    exit;
                }
            } else {
                require_once '../views/platforms/edit.php';
            }
        } catch (Exception $e) {
            if (strpos($e, 'Duplicate entry') !== false) {
                $_SESSION['error_message'] = "El nombre de la plataforma ya existe. Por favor, elige un nombre diferente.";
                header('Location: ../routes/router.php?path=platforms/create');
                exit;
            } else {
                $_SESSION['error_message'] = "Error al crear la plataforma: " . $e->getMessage();
                header('Location: ../routes/router.php?path=platforms/create');
                exit;
            }
        }
    }

    public function delete($id) {
        try {
            // Validar que el ID sea un número entero válido
            if (!filter_var($id, FILTER_VALIDATE_INT)) {
                $_SESSION['error_message'] = "ID inválido.";
                header('Location: ../routes/router.php?path=platforms/list');
                exit;
            }

            // Eliminar la plataforma
            if (Platform::delete($id)) {
                $_SESSION['success_message'] = "Plataforma eliminada exitosamente.";
                header('Location: ../routes/router.php?path=platforms/list');
                exit;
            } else {
                $_SESSION['error_message'] = "Error al eliminar la plataforma.";
                header('Location: ../routes/router.php?path=platforms/list');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error al eliminar la plataforma: " . $e->getMessage();
            header('Location: ../routes/router.php?path=platforms/list');
            exit;
        }
    }
}
?>