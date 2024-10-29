<?php
require_once 'employees.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action) {
    case 'getAll':
        echo json_encode(['success' => true, 'data' => getAllEmployees()]);
        break;
        
    case 'add':
        if(addEmployee($_POST)) {
            echo json_encode(['success' => true, 'message' => 'Employee added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add employee']);
        }
        break;
        
    case 'get':
        $id = $_GET['id'] ?? 0;
        $employee = getEmployee($id);
        if($employee) {
            echo json_encode(['success' => true, 'data' => $employee]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Employee not found']);
        }
        break;
        
    case 'update':
        $id = $_POST['id'] ?? 0;
        if(updateEmployee($id, $_POST)) {
            echo json_encode(['success' => true, 'message' => 'Employee updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update employee']);
        }
        break;
        
    case 'delete':
        $id = $_POST['id'] ?? 0;
        if(deleteEmployee($id)) {
            echo json_encode(['success' => true, 'message' => 'Employee deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete employee']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>