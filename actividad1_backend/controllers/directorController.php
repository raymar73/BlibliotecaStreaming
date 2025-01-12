<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../models/director.php';

class DirectorController
{
    public function list()
    {
        try {
            $directors = Director::getAll();
            require_once '../views/directors/list.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error al obtener las plataformas: " . $e->getMessage();
            header('Location: ../routes/router.php?path=directors/list');
            exit;
        }
    }

    public function create() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = trim($_POST['name'] ?? '');
                $last_name = trim($_POST['last_name'] ?? '');
                $date_of_birth = trim($_POST['date_of_birth'] ?? '');
                $nationality = trim($_POST['nationality'] ?? '');
    
                if (empty($name) || empty($last_name)) {
                    $_SESSION['error_message'] = "El nombre y apellido son requeridos.";
                    header('Location: ../routes/router.php?path=directors/create');
                    exit;
                }
    
                // Verificar duplicados antes de crear
                if (Director::existsDirector($name, $last_name)) {
                    $_SESSION['error_message'] = "Ya existe un director con ese nombre y apellido.";
                    header('Location: ../routes/router.php?path=directors/create');
                    exit;
                }
    
                try {
                    $director = new Director(null, $name, $last_name, $date_of_birth, $nationality);
                    if ($director->save()) {
                        $_SESSION['success_message'] = "Los datos del director fueron creados exitosamente.";
                        header('Location: ../routes/router.php?path=directors/list');
                        exit;
                    }
                } catch (Exception $e) {
                    $_SESSION['error_message'] = $e->getMessage();
                    header('Location: ../routes/router.php?path=directors/create');
                    exit;
                }
            }
            require_once '../views/directors/create.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error inesperado: " . $e->getMessage();
            header('Location: ../routes/router.php?path=directors/create');
            exit;
        }
    }
    public function edit($id)
    {
        try {
            if (!filter_var($id, FILTER_VALIDATE_INT)) {
                throw new Exception("ID inválido.");
            }

            $director = Director::findById($id);
            if (!$director) {
                $_SESSION['error_message'] = "Idioma no encontrada.";
                header('Location: ../routes/router.php?path=directors/list');
                exit;
            }

            $error = null;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $name = trim($_POST['name'] ?? '');
                $last_name = trim($_POST['last_name'] ?? '');
                $date_of_birth = trim($_POST['date_of_birth'] ?? '');
                $nationality = trim($_POST['nationality'] ?? '');

                // Validar campos vacíos
                if (empty($name) || empty($last_name)) {
                    $_SESSION['error_message'] = "El nombre y apellido son obligatorios.";
                    header('Location: ../routes/router.php?path=directors/edit&id=' . $id);
                    exit;
                }

                // Verificar si existe un director con el mismo nombre y apellido (excluyendo el actual)
                if (Director::existsDirector($name, $last_name, $id)) {
                    $_SESSION['error_message'] = "Ya existe un director con ese nombre y apellido.";
                    header('Location: ../routes/router.php?path=directors/edit&id=' . $id);
                    exit;
                }

                $director->setName($name);
                $director->setlast_name($last_name);
                $director->setDateOfBirth($date_of_birth);
                $director->setnationality($nationality);

                if ($director->update()) {
                    $_SESSION['success_message'] = "Datos del Director actualizada exitosamente.";
                    header('Location: ../routes/router.php?path=directors/list');
                    exit;
                } else {
                    $_SESSION['error_message'] = "Error al actualizar datos del director.";
                    header('Location: ../routes/router.php?path=directors/edit&id=' . $id);
                    exit;
                }

            } else {
                require_once '../views/directors/edit.php';
            }
        } catch (Exception $e) {
            if (strpos($e, 'Duplicate entry') !== false) {
                $_SESSION['error_message'] = "Error: " . $e->getMessage();
                header('Location: ./router.php?path=directors/edit&id=' . $id);
                exit();
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

            if (Director::delete($id)) {
                $_SESSION['success_message'] = "Datos del director eliminada exitosamente.";
                header('Location: ../routes/router.php?path=directors/list');
                exit;
            } else {
                $_SESSION['error_message'] = "Error al eliminar Datos del director.";
                header('Location: ../routes/router.php?path=directors/list');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error al eliminar Datos del director: " . $e->getMessage();
            header('Location: ../routes/router.php?path=languages/list');
            exit;
        }
    }


}
?>