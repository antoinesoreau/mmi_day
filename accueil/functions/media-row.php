<?php
/**
 * Convertit une chaîne de médias séparés par des points-virgules
 * en un tableau structuré avec le type (img / video) déduit de l'extension.
 *
 * @param string $mediaString Chaîne comme "img1.png;vid1.mp4;..."
 * @return array Tableau de médias formatés
 */
function parseMediaAdd(string $mediaString): array {
    if (empty($mediaString)) {
        return [];
    }

    // Découpe la chaîne en fichiers
    $files = explode(';', $mediaString);
    $mediaList = [];

    // Extensions reconnues
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
    $videoExtensions = ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'];

    foreach ($files as $file) {
        $file = trim($file);
        if ($file === '') continue;

        $parts = explode('.', $file);
        $extension = strtolower(end($parts));

        if (in_array($extension, $imageExtensions)) {
            $type = 'img';
        } elseif (in_array($extension, $videoExtensions)) {
            $type = 'video';
        } else {
            // Optionnel : ignorer les formats non supportés ou les marquer comme 'unknown'
            continue; // ou $type = 'unknown';
        }

        $mediaList[] = [
            'nom' => $file,
            'type' => $type
        ];
    }

    return $mediaList;
}