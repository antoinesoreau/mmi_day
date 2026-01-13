<?php
function handleAuth($post_data, $referer) {
    global $dtb;
    $redirect_url = strtok($referer, '?');
    
    if (isset($post_data['register'])) {
        $nom = trim($post_data['nom'] ?? 'Non renseigné');
        $email = trim($post_data['email']);
        $password = $post_data['password'];
        $password_confirm = $post_data['password_confirm'];

        if ($password !== $password_confirm) {
            return ['redirect' => "$redirect_url?error=password_mismatch"];
        }

        $result = registerUser($email, $password, $nom);
        if (!$result['success']) {
            return ['redirect' => "$redirect_url?error=" . $result['error']];
        }

        // Succès : connexion automatique
        return [
            'session' => [
                'user_id' => $result['user_id'],
                'role' => 'visiteur',
                'prenom' => 'A renseigner'
            ],
            'redirect' => '../index.php?success=auto_connected'
        ];

    } elseif (isset($post_data['login'])) {
        $email = trim($post_data['email']);
        $password = $post_data['password'];

        $result = loginUser($email, $password);
        if (!$result['success']) {
            return ['redirect' => "$redirect_url?error=" . $result['error']];
        }

        return [
            'session' => [
                'user_id' => $result['user_id'],
                'role' => $result['role'],
                'prenom' => $result['prenom']
            ],
            'redirect' => '../index.php?success=login_ok'
        ];
    }

    // Cas par défaut (ne devrait pas arriver si bien appelé)
    return ['redirect' => '../index.php'];
}