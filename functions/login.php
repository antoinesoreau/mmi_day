<?php
/**
 * Fonction de connexion
 */
function login($email, $password) {
    global $dtb;

    $stmt = $dtb->prepare("SELECT * FROM user WHERE email = :email AND statut_actif = 1");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['password'])) {
            return [
                'status_code' => 'success',
                'data' =>[
                    'id' => (int)$user['id'],
                    'nom' => $user['nom'],
                    'prenom' => $user['prenom_user'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]
            ];
        }
    }

    return [
        'status_code' => 'error',
        'message' => 'Identifiants invalides ou compte désactivé'
    ];
}