<?php

namespace App\Services;

class CheckerService
{

    private $patternCheck = [
        'sass' => ['scss'],
        'js'   => ['js'],
        'css'  => ['css'],
        'img'  => ['jpeg', 'png', 'jpg', 'gif'],
    ];

    public function __construct()
    {
        # code...
    }

    public function checkFileProject($dir, &$fileList = array(), &$errors = array())
    {
        if (file_exists($dir)) {

            $files = scandir($dir);

            foreach ($files as $key => $value) {
                $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
                if (!is_dir($path)) {

                    $fileName = basename($path);

                    $exts = explode('.', $fileName);
                    $ext  = $exts[count($exts) - 1];

                    $parent = basename(dirname($path));

                    $fileList[] = array(
                        'file_name'    => $fileName,
                        'file_size'    => filesize($path),
                        'file_content' => !in_array($ext, $this->patternCheck['img']) ? file_get_contents($path) : '',
                        'file_type'    => $ext,
                    );

                    foreach ($this->patternCheck as $folderName => $extension) {
                        if (in_array($ext, $extension)) {
                            if ($parent != $folderName) {

                                $errors[] = array(
                                    'type'            => 'error',
                                    'folder_name'     => $parent,
                                    'file_error_name' => $fileName,
                                );
                            }
                        }
                    }

                } else if ($value != "." && $value != "..") {
                    $this->checkFileProject($path, $fileList, $errors);
                }
            }

            return array('files' => $fileList, 'errors' => $errors);

        }
    }
}
