<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../models/actor.php';

class actorController
{
    public function list()
    {
        try {
            $listActors = ActorClass::consultActor(); // Obtener los actores desde el modelo
            $totalActors = count($listActors); // Contar la cantidad de actores
            require_once '../views/actors/list.php';  // Incluir la vista, pasando los actores
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error al cargar la lista de actores: " . $e->getMessage();
        
            // Redirigir al listado de actores
            header('Location: ../routes/router.php?path=actors/list');
        }
    }

    public function edit($id)
    {
        try {
            // Validar que el ID sea un número entero válido
            if (!filter_var($id, FILTER_VALIDATE_INT)) {
                $_SESSION['error_message'] = "ID inválido.";
                require_once '../views/actors/list.php';
                return;
            }
    
            // Buscar al actor por ID
            $actor = ActorClass::findById($id);
            if (!$actor) {
                $_SESSION['error_message'] = "Actor no encontrado.";
                require_once '../views/actors/list.php';
                return;
            }
    
            // Si la solicitud es un POST (se ha enviado el formulario)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Obtener los datos del formulario
                $name = trim($_POST['name'] ?? '');
                $apellido = trim($_POST['apellido'] ?? '');
                $birthDate = trim($_POST['birthDate'] ?? '');
                $nationality = trim($_POST['nationality'] ?? '');
    
                // Validaciones
                if (empty($name) || empty($apellido) || empty($birthDate) || empty($nationality)) {
                    $_SESSION['error_message'] = "Todos los campos son obligatorios.";
                    require_once '../views/actors/edit.php';
                    return;
                }
    
                if (strlen($name) > 255) {
                    $_SESSION['error_message'] = "El nombre no puede exceder los 255 caracteres.";
                    require_once '../views/actors/edit.php';
                    return;
                }
    
                if (strlen($apellido) > 255) {
                    $_SESSION['error_message'] = "El apellido no puede exceder los 255 caracteres.";
                    require_once '../views/actors/edit.php';
                    return;
                }
    
                if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
                    $_SESSION['error_message'] = "El nombre solo puede contener letras y espacios.";
                    require_once '../views/actors/edit.php';
                    return;
                }
    
                // Actualizar los datos del actor
                $actor->setNombres($name);
                $actor->setApellidos($apellido);
                $actor->setFechaNacimiento($birthDate);
                $actor->setNacionalidad($nationality);
    
                // Intentar guardar los cambios
                if ($actor->update()) {
                    $_SESSION['success_message'] = "Actor actualizado exitosamente.";
                    header('Location: ../routes/router.php?path=actors/list');
                    exit;
                } else {
                    $_SESSION['error_message'] = "Error al actualizar el actor.";
                    require_once '../views/actors/edit.php';
                    return;
                }
            } else {
                // Si no es una solicitud POST, simplemente mostrar el formulario
                require_once '../views/actors/edit.php';
            }
        } catch (Exception $e) {
            // Manejo de excepciones generales
            $_SESSION['error_message'] = "Error al editar el actor: " . $e->getMessage();
            require_once '../views/actors/list.php';
            return;
        }
    }
    
    
    public function delete($id)
    {
        try {
            // Validar que el ID sea un número entero
            if (!filter_var($id, FILTER_VALIDATE_INT)) {
                throw new Exception("ID inválido.");
            }

            // Llamar al modelo para eliminar el actor
            if (ActorClass::delete($id)) {
                // Redirigir a la lista de actores si la eliminación fue exitosa
                header('Location: ../routes/router.php?path=actors/list');
                exit;
            } else {
                throw new Exception("Error al eliminar el actor.");
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function create() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Recoger los datos del formulario y limpiarlos
                $name = trim($_POST['name'] ?? '');
    
                // Validaciones
                if (empty($name)) {
                    $_SESSION['error_message'] = "El nombre del actor es obligatorio.";
                    require_once '../views/actors/create.php';
                    return;
                }
    
                if (strlen($name) < 3) {
                    $_SESSION['error_message'] = "El nombre debe tener al menos 3 caracteres.";
                    require_once '../views/actors/create.php';
                    return;
                }
    
                if (strlen($name) > 100) {
                    $_SESSION['error_message'] = "El nombre no puede exceder los 100 caracteres.";
                    require_once '../views/actors/create.php';
                    return;
                }
    
                $apellido = trim($_POST['apellido'] ?? '');
    
                if (empty($apellido)) {
                    $_SESSION['error_message'] = "El apellido del actor es obligatorio.";
                    require_once '../views/actors/create.php';
                    return;
                }
    
                if (strlen($apellido) > 100) {
                    $_SESSION['error_message'] = "El apellido no puede exceder los 100 caracteres.";
                    require_once '../views/actors/create.php';
                    return;
                }
    
                $birthDate = trim($_POST['birthDate'] ?? '');

                if (empty($birthDate)) {
                    $_SESSION['error_message'] = "La fecha de nacimiento es obligatoria.";
                    require_once '../views/actors/create.php';
                    return;
                }
                $birthDateTimestamp = strtotime($birthDate);
                $fiveYearsAgo = strtotime('-5 years');
                
                if ($birthDateTimestamp > $fiveYearsAgo) {
                    $_SESSION['error_message'] = "La fecha de nacimiento debe ser mayor a 5 años a partir de hoy.";
                    require_once '../views/actors/create.php';
                    return;
                }
    
                $nationality = trim($_POST['nationality'] ?? '');
                if (empty($nationality)) {
                    $_SESSION['error_message'] = "La nacionalidad es obligatoria.";
                    require_once '../views/actors/create.php';
                    return;
                }
    
                if (strlen($nationality) > 3) {
                    $_SESSION['error_message'] = "La nacionalidad no puede exceder los 5 caracteres.";
                    require_once '../views/actors/create.php';
                    return;
                }
    
                // Crear el actor
                $actor = new ActorClass(null, $name, $apellido, $birthDate, $nationality);
    
                // Intentar guardar el actor
                if ($actor->save()) {
                    $_SESSION['success_message'] = "Actor creado exitosamente.";
                    require_once '../views/actors/create.php';
                    return;
                } else {
                    $_SESSION['error_message'] = "Error al guardar el actor. Por favor, inténtelo más tarde.";
                    require_once '../views/actors/create.php';
                    return;
                }
    
            } else {
                require_once '../views/actors/create.php';
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error al crear el actor: " . $e->getMessage();
            require_once '../views/actors/create.php';
            return;
        }
    }
    
    
    
    }
    
    
    
    
    

