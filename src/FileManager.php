<?php

namespace File\repo;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FileManager
{
    public static function upload(string $ref_table_name, int $ref_id, $file = null)
    {
        $fileName = "";
        if ($file != null) {
            $fileName = Carbon::now()->getTimestamp() . "√√√" . $file->getClientOriginalName();
        } else {
            $fileName = 'def.png';
        }
        if (!Storage::disk('public')->exists($ref_table_name)) {
            Storage::disk('public')->makeDirectory($ref_table_name);
        }
        Storage::disk('public')->put($ref_table_name . "/" . $fileName, file_get_contents($file));

        DB::table('file_repos')->insert([
            'ref_id' => $ref_id,
            'ref_name' => $ref_table_name,
            'path' => $ref_table_name . "/" . $fileName,
        ]);
    }

    public static function update(string $ref_table_name, int $old_fileId, $file = null)
    {
        $oldFileRepo = DB::table('file_repos')->where("ref_id", $old_fileId)->first();
        if ($oldFileRepo) {
            if (Storage::disk('public')->exists($oldFileRepo->path)) {
                Storage::disk('public')->delete($oldFileRepo->path);
            }
            $fileName = "";
            if ($file != null) {
                $fileName = Carbon::now()->getTimestamp() . "√√√" . $file->getClientOriginalName();
            } else {
                $fileName = 'def.png';
            }
            if (!Storage::disk('public')->exists($ref_table_name)) {
                Storage::disk('public')->makeDirectory($ref_table_name);
            }
            Storage::disk('public')->put($ref_table_name . "/" . $fileName, file_get_contents($file));

            DB::table('file_repos')->where('id', $old_fileId)->update([
                'path' => $ref_table_name . "/" . $fileName,
            ]);
        } else {
            self::upload($ref_table_name, $old_fileId, $file);
        }
    }

    public static function deletefile(int $id)
    {
        $oldFileRepo = DB::table('file_repos')->find($id);
        if ($oldFileRepo) {
            if (Storage::disk('public')->exists($oldFileRepo->path)) {
                Storage::disk('public')->delete($oldFileRepo->path);
            }
            DB::table('file_repos')->where('id', $id)->delete();
        }
    }

    public static function get_path_by(string $ref_table_name, int $ref_id)
    {
        $fileRepos = DB::table('file_repos')
            ->where("ref_name", $ref_table_name)
            ->where("ref_id", $ref_id)
            ->orderBy('id', 'desc')
            ->get();

        $res = [];

        foreach ($fileRepos as $value) {
            $res[] = ["path" => asset('storage/') . '/' . $value->path, "id" => $value->id];
        }
        return $res;
    }
}
