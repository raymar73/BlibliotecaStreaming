<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../models/language.php');

class LanguageController
{
    public function list()
    {
        try {
            $language = Language::getAll();
            require_once '../views/languages/list.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error al obtener las plataformas: " . $e->getMessage();
            header('Location: ../routes/router.php?path=languages/list');
            exit;
        }
    }

    public function create()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = trim($_POST['name'] ?? '');
                $isocode = strtoupper(trim($_POST['isocode'] ?? '')); // Convertir a mayúsculas

                // Validar que ambos campos estén presentes
                if (empty($name) || empty($isocode)) {
                    $_SESSION['error_message'] = "El nombre del idioma es obligatorio.";
                    header('Location: ../routes/router.php?path=languages/create');
                    exit;
                }

                // Validar que el isocode sea exactamente 2 letras
                if (!preg_match('/^[A-Z]{2}$/', $isocode)) {

                    $_SESSION['error_message'] = "El código ISO debe contener exactamente 2 letras.";
                    header('Location: ../routes/router.php?path=languages/create');
                    exit;
                }


                // Validar si ya existe el nombre
                if (Language::existsByName($name)) {
                    $_SESSION['error_message'] = "Ya existe un idioma con ese nombre.";
                    header('Location: ../routes/router.php?path=languages/create');
                    exit;
                }

                // Validar si ya existe el código ISO
                if (Language::existsByIsocode($isocode)) {
                    $_SESSION['error_message'] = "Ya existe un idioma con ese código ISO.";
                    header('Location: ../routes/router.php?path=languages/create');
                    exit;
                }

                $language = new Language(null, $name, $isocode);

                if ($language->save()) {

                    $_SESSION['success_message'] = "El idioma fue creado exitosamente.";
                    error_log("Mensaje de éxito configurado: " . $_SESSION['success_message']);

                    header('Location: ../routes/router.php?path=languages/list');
                    exit;
                } else {
                    $_SESSION['error_message'] = "Error al guardar el idioma. porfavor intentelo más tarde.";
                    $error = "Error al guardar idioma.";
                    require_once '../views/languages/create.php';
                }
            } else {
                require_once '../views/languages/create.php';
            }
        } catch (Exception $e) {
            if (strpos($e, 'Duplicate entry') !== false) {
                $_SESSION['error_message'] = "El nombre del idioma ya existe. Por favor, elige un nombre diferente.";
                header('Location: ../routes/router.php?path=languages/create');
                exit;
            } else {
                $_SESSION['error_message'] = "Error al crear idioma " . $e->getMessage();
                header('Location: ../routes/router.php?path=languages/create');
                exit;
            }
        }
    }

    public function edit($id)
    {
        try {
            if (!filter_var($id, FILTER_VALIDATE_INT)) {
                $_SESSION['error_message'] = "ID inválido.";
                header('Location: ../routes/router.php?path=languages/list');
                exit;
            }

            $language = Language::findById($id);
            if (!$language) {
                $_SESSION['error_message'] = "Idioma no encontrada.";
                header('Location: ../routes/router.php?path=languages/list');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Obtenemos los valores del POST, si no se modificaron, usamos los valores actuales
                $name = trim($_POST['name'] ?? $language->getName());
                $isocode = strtoupper(trim($_POST['isocode'] ?? $language->getIsocode()));

                // Si no hay cambios, no actualizar
                if ($name === $language->getName() && $isocode === $language->getIsocode()) {
                    $_SESSION['success_message'] = "No se realizaron cambios.";
                    header('Location: ../routes/router.php?path=languages/list');
                    exit;
                }

                // Validar nombres duplicados solo si los valores han cambiado
                if ($name !== $language->getName() && Language::existsByName($name)) {
                    $_SESSION['error_message'] = "Ya existe un idioma con ese nombre.";
                    header('Location: ../routes/router.php?path=languages/edit&id=' . $id);
                    exit;
                }
                // Validar isocode  duplicados solo si los valores han cambiado

                if ($isocode !== $language->getIsocode() && Language::existsByIsocode($isocode)) {
                    $_SESSION['error_message'] = "Ya existe un idioma con ese código ISO.";
                    header('Location: ../routes/router.php?path=languages/edit&id=' . $id);
                    exit;
                }

                // Solo actualizamos si los valores son válidos
                if (!empty($name) && !empty($isocode)) {
                    if (!preg_match('/^[A-Z]{2}$/', $isocode)) {

                        $_SESSION['error_message'] = "El código ISO debe contener exactamente 2 letras.";
                        header('Location: ../routes/router.php?path=languages/edit&id=' . $id);
                        exit;
                    }
                    $language->setName($name);
                    $language->setIsocode($isocode);
                    if ($language->update()) {
                        $_SESSION['success_message'] = "Idioma actualizada exitosamente.";
                        header('Location: ../routes/router.php?path=languages/list');
                        exit;
                    } else {
                        $_SESSION['error_message'] = "Error al actualizar la plataforma.";
                        header('Location: ../routes/router.php?path=languages/edit&id=' . $id);
                        exit;
                    }
                } else {
                    $_SESSION['error_message'] = "El nombre del idioma y el código ISO son obligatorios.";
                    require_once '../views/languages/edit.php';

                }
            }
            require_once '../views/languages/edit.php';
        } catch (Exception $e) {
            if (strpos($e, 'Duplicate entry') !== false) {
                $_SESSION['error_message'] = "El nombre del idioma ya existe. Por favor, elige un nombre diferente.";
                header('Location: ../routes/router.php?path=languages/create');
                exit;
            } else {
                $_SESSION['error_message'] = "Error al agregar idioma " . $e->getMessage();
                header('Location: ../routes/router.php?path=languages/create');
                exit;
            }
        }
    }

    public function delete($id)
    {
        try {
            // Validar que el ID sea un número entero válido
            if (!filter_var($id, FILTER_VALIDATE_INT)) {
                $_SESSION['error_message'] = "ID inválido.";
                header('Location: ../routes/router.php?path=languages/list');
                exit;
            }

            if (Language::delete($id)) {
                $_SESSION['success_message'] = "Plataforma eliminada exitosamente.";
                header('Location: ../routes/router.php?path=languages/list');
                exit;
            } else {
                $_SESSION['error_message'] = "Error al eliminar la plataforma.";
                header('Location: ../routes/router.php?path=languages/list');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error al eliminar la plataforma: " . $e->getMessage();
            header('Location: ../routes/router.php?path=languages/list');
            exit;
        }
    }
}
?>