<?php

//include "../db_connect.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Fonction pour établir une connexion à la base de données
function connectDB() {
    $host = "localhost";
    $dbname = "monpost";
    $username = "root";
    $password = "";
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit; // Terminate script on failure
    }
}

// Fonction pour créer un nouvel enregistrement
function create_record($table, $data) {
    $conn = connectDB();
    try {
        $record_data = $data['data'];
        
        $keys = implode(', ', array_keys($record_data));
        $placeholders = implode(', :', array_keys($record_data));
        
        $sql = "INSERT INTO $table ($keys) VALUES (:$placeholders)";
        $stmt = $conn->prepare($sql);
        $stmt->execute($record_data);
        
        $id = $conn->lastInsertId();
        return array("success" => true, "message" => "Created successfully", "id" => $id);
    } catch (PDOException $e) {
        return array("success" => false, "message" => "Error: " . $e->getMessage());
    }
}

// Fonction pour lire un enregistrement
function read_record($table, $id = null) {
    $conn = connectDB();
    try {
        if ($id !== null) {
            $sql = "SELECT * FROM $table WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return array("success" => true, "data" => $result);
            } else {
                return array("success" => false, "message" => "Not found");
            }
        } else {
            $sql = "SELECT * FROM $table";
            $stmt = $conn->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array("success" => true, "data" => $results);
        }
    } catch (PDOException $e) {
        return array("success" => false, "message" => "Error: " . $e->getMessage());
    }
}

function read_all_records($table) {
    $conn = connectDB();
    try {
        $sql = "SELECT * FROM $table";
        $stmt = $conn->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array("success" => true, "data" => $results);
    } catch (PDOException $e) {
        return array("success" => false, "message" => "Error: " . $e->getMessage());
    }
}


// Fonction pour mettre à jour un enregistrement
function update_record($table, $data, $id) {
    $conn = connectDB();
    try {
        $record_data = $data['data'];
        
        $updates = [];
        foreach ($record_data as $key => $value) {
            $updates[] = "$key = :$key";
        }
        
        $updates_string = implode(', ', $updates);
        $record_data['id'] = $id;
        
        $sql = "UPDATE $table SET $updates_string WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute($record_data);
        
        return array("success" => true, "message" => "Updated successfully");
    } catch (PDOException $e) {
        return array("success" => false, "message" => "Error: " . $e->getMessage());
    }
}

// Fonction pour supprimer un enregistrement
function delete_record($table, $id) {
    $conn = connectDB();
    try {
        $sql = "DELETE FROM $table WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        
        return array("success" => true, "message" => "Deleted successfully");
    } catch (PDOException $e) {
        return array("success" => false, "message" => "Error: " . $e->getMessage());
    }
}

// Main code

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['read_all']) && $data['read_all'] === 1) {
            $table = $data['table'];
            echo json_encode(read_all_records($table));
        } else if (isset($data['id'])) {
            $table = $data['table'];
            $id = $data['id'];
            echo json_encode(read_record($table, $id));
        } else {
            // Si ni "read_all" ni "id" ne sont définis, renvoyer un message d'erreur
            echo json_encode(array("success" => false, "message" => "Missing 'id' or 'read_all' in request"));
        }
        break;    
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $table = $data['table'];
        echo json_encode(create_record($table, $data));
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id']; // Récupérer l'ID à partir du corps de la requête JSON
        $table = $data['table'];
        echo json_encode(update_record($table, $data, $id));
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id']) && isset($data['table'])) {
            $id = $data['id'];
            $table = $data['table'];
            echo json_encode(delete_record($table, $id));
        } else {
            echo json_encode(array("success" => false, "message" => "Missing 'id' or 'table' in request"));
        }
        break;
        
    default:
        echo json_encode(array("success" => false, "message" => "Invalid request method"));
}

?>
