<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class IO
{
    public static $path;

    public static function getPathFileUpload()
    {
        return self::$path;
    }

    public static function createFolder($path_parent, $array_child_folders)
    {
        $path_parent = ends_with($path_parent, '/') ? $path_parent : $path_parent . '/';
        $path        = $path_parent;

        foreach ($array_child_folders as $key => $name) {
            $path .= $name . '/';
            // make folder
            if (!File::exists($path)) {
                File::makeDirectory($path, 777);
            }
        }
        //save path
        self::$path = $path;
        return File::exists($path);
    }

    public static function uniqueName(UploadedFile $file, $name_file = null)
    {
        $ext       = $file->getClientOriginalExtension();
        $nameOrigi = $file->getClientOriginalName();

        $name_file = empty($name_file) ? basename($nameOrigi, '.' . $ext) : $name_file;

        $name_file = str_slug($name_file, '-') . '-' . (microtime(true) * 10000 % 10000) . '.' . $ext;
        return $name_file;
    }

    public static function storage(UploadedFile $file, $path_save, $name = null)
    {
        $path_save = starts_with($path_save, '/') ? $path_save : '/' . $path_save;
        $path_save = ends_with($path_save, '/') ? $path_save : $path_save . '/';

        $ext       = $file->getClientOriginalExtension();
        $name_ext  = empty($name) ? $file->getClientOriginalName() : $name;
        $path_file = storage_path() . $path_save . $name_ext;
        // remove if file exist
        if (File::exists($path_file)) {
            File::delete($path_file);
        }
        //save path
        self::$path = $path_file;
        return $file->move(storage_path() . $path_save, $name_ext);
    }

    public static function upload(UploadedFile $file, $path_save, $name = null)
    {
        $path_save = starts_with($path_save, '/') ? $path_save : '/' . $path_save;
        $path_save = ends_with($path_save, '/') ? $path_save : $path_save . '/';

        $ext       = $file->getClientOriginalExtension();
        $name_ext  = empty($name) ? $file->getClientOriginalName() : $name;
        $path_file = public_path() . $path_save . $name_ext;
        // xoa file neu da co
        if (File::exists($path_file)) {
            File::delete($path_file);
        }
        //luu lai duong dan vua tao
        self::$path = $path_save . $name_ext;
        return $file->move(public_path() . $path_save, $name_ext);
    }

    public static function uploadImage($path_save, $arr_name_folders, UploadedFile $file, $name_file_save)
    {

        $validate = Validator::make(
            ['image' => $file],
            ['image' => 'required|mimes:jpeg,jpg,png,gif']
        );

        if ($validate->fails()) {
            return false;
        } else {
            //tao duong dan thu muc luu anh
            $mk_path_folder = self::createFolder(public_path() . $path_save, $arr_name_folders);

            //neu co thu muc roi thi upload
            if ($mk_path_folder) {
                $destinationPath = self::getPathFileUpload();

                //upload image
                $file->move($destinationPath, $name_file_save);
                self::$path = $path_save . implode('/', $arr_name_folders) . '/' . $name_file_save;
                return true;
            }
        }
        return false;
    }

    public static function deleteDir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        self::deleteDir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }

                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

}
