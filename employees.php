<?php
require_once 'db_config.php';

function getAllEmployees() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM employees ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addEmployee($data) {
    global $pdo;
    $sql = "INSERT INTO employees (nip, name, position, department, email, phone, address, join_date, salary, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['nip'],
        $data['name'],
        $data['position'],
        $data['department'],
        $data['email'],
        $data['phone'],
        $data['address'],
        $data['join_date'],
        $data['salary'],
        $data['status']
    ]);
}

function getEmployee($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateEmployee($id, $data) {
    global $pdo;
    $sql = "UPDATE employees SET 
            nip = ?, name = ?, position = ?, department = ?, 
            email = ?, phone = ?, address = ?, join_date = ?, 
            salary = ?, status = ? 
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['nip'],
        $data['name'],
        $data['position'],
        $data['department'],
        $data['email'],
        $data['phone'],
        $data['address'],
        $data['join_date'],
        $data['salary'],
        $data['status'],
        $id
    ]);
}

function deleteEmployee($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
    return $stmt->execute([$id]);
}
?>