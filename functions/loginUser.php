<?php
function loginUser($email, $password) {
    global $dtb;
    $stmt = $dtb->prepare("SELECT id, role, prenom_user FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        return [
            'success' => true,
            'user_id' => $user['id'],
            'role'    => $user['role'],
            'prenom'  => $user['prenom_user']
        ];
    }

    return ['success' => false, 'error' => 'login_failed'];
}