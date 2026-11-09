<?php

class CoverUploader
{
    public function upload(array $file): string
    {
        $error = $file['error'] ?? null;

        if ($error === UPLOAD_ERR_NO_FILE) {
            throw new InvalidArgumentException(
                'Veuillez choisir une image.'
            );
        }

        if (
            $error === UPLOAD_ERR_INI_SIZE
            || $error === UPLOAD_ERR_FORM_SIZE
        ) {
            throw new InvalidArgumentException(
                'L’image ne doit pas dépasser 2 Mo.'
            );
        }

        if ($error !== UPLOAD_ERR_OK) {
            throw new InvalidArgumentException(
                'Le téléversement a échoué.'
            );
        }

        $temporaryPath = $file['tmp_name'] ?? '';

        if (
            !is_string($temporaryPath)
            || !is_uploaded_file($temporaryPath)
        ) {
            throw new InvalidArgumentException(
                'Le fichier téléversé est invalide.'
            );
        }

        $size = filesize($temporaryPath);

        if ($size === false || $size < 1 || $size > 2 * 1024 * 1024) {
            throw new InvalidArgumentException(
                'L’image doit être non vide et ne pas dépasser 2 Mo.'
            );
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file(
            $temporaryPath
        );

        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
        ];

        if (!isset($allowedTypes[$mime])) {
            throw new InvalidArgumentException(
                'Seules les images JPEG et PNG sont acceptées.'
            );
        }

        $image = @getimagesize($temporaryPath);

        if (
            $image === false
            || ($image['mime'] ?? '') !== $mime
            || $image[0] < 1
            || $image[1] < 1
            || $image[0] > 5000
            || $image[1] > 5000
        ) {
            throw new InvalidArgumentException(
                'Image invalide ou dimensions supérieures à 5000 pixels.'
            );
        }

        $filename = bin2hex(random_bytes(16))
            . '.' . $allowedTypes[$mime];

        $directory = __DIR__ . '/../../public/uploads/covers';
        $destination = $directory . '/' . $filename;

        if (!move_uploaded_file($temporaryPath, $destination)) {
            throw new RuntimeException(
                'Impossible d’enregistrer l’image.'
            );
        }

        return '/uploads/covers/' . $filename;
    }
}