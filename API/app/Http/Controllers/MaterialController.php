<?php

namespace App\Http\Controllers;

use App\Models\Materials;
use http\Env\Response;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function createMaterial(Request $request){
        $material_exists = Materials::where('name', '=', $request->name)->get();
        if ($material_exists != '[]'){
            return response()->json([
                "message" => "Material with this name already exists!",
                "error" => "Duplicate"
            ], '201');
        } else {
            $material = new Materials();
            $material->name = $request->name;
            $material->author = $request->author;
            $material->category = $request->category;
            $material->subject_FK = $request->subject;
            $material->document = $request->path;
            $material->save();
            return response()->json([
                "messsage" => "Material created successfully!"
            ], '201');
        }
    }

    public function removeMaterial(Request $request){
        $material = Materials::where('name', '=', $request->name)->delete();
        return response()->json([
            "message" => "Material has been removed."
        ], '201');
    }

    public function returnMaterial(Request $request)
    {
        $subject = Materials::where('name', '=', $request->name)->get();
        return response()->json($subject);
    }

    public function updateMaterialName(Request $request){
        $material_exists = Materials::where('name', '=', $request->name)->get();
        if ($material_exists != '[]') {
            return response()->json([
                "message" => "Subject already exists!",
                "error" => "Duplicate"
            ], '406');
        } else {
            $material = Materials::where('name', '=', $request->name)->update(array('name' => $request->new_name));
            return response()->json([
                "message" => "New material name has been set successfully"
            ], '201');
        }
    }
    public function updateMaterialCategory(Request $request){
        $material = Materials::where('name', '=', $request->name)->update(array('category' => $request->new_category));
        return response()->json([
            "message" => "New material category has been set successfully"
        ], '201');
    }

    public function updateMaterialDocument(Request $request){
        $material = Materials::where('name', '=', $request->name)->update(array('document' => $request->new_path));
        return response()->json([
            "message" => "New material document path has been set successfully"
        ], '201');
    }
}
