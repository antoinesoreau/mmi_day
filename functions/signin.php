<?php
function signin($email, $password) {
    global $dtb;

    $check = $dtb->prepare("SELECT id FROM user WHERE email = :email");
    $check->bindParam(':email', $email, PDO::PARAM_STR);
    $check->execute();

    if ($check->rowCount() > 0) {
        return [
            'status_code' => 'error',
            'message' => 'Cet email est déjà utilisé'
        ];
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $insert = $dtb->prepare("INSERT INTO user (email, password) VALUES (:email, :password)");
    $insert->bindParam(':email', $email, PDO::PARAM_STR);
    $insert->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

    if ($insert->execute()) {
        $userId = $dtb->lastInsertId();

        // Récupérer les données insérées (car certains champs ont des valeurs par défaut)
        $stmt = $dtb->prepare("SELECT id, email, role, nom, prenom_user FROM user WHERE id = :id");
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'status_code' => 'success',
            'data' => [
                'id' => (int)$user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'nom' => $user['nom'],
                'prenom_user' => $user['prenom_user']
            ]
        ];
    } else {
        return [
            'status_code' => 'error',
            'message' => 'Échec de l’inscription'
        ];
    }
}