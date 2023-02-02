<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Family;
use App\Models\File;
use App\Models\Group;
use App\Models\Countrysu;
use App\Models\Countryor;
use App\Models\Hazard;
use App\Models\King;
use App\Models\Log;
use App\Models\Tool;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ToolController extends Controller
{

    public function history(Request $request) {
        return response()->json(Log::with('tool','user')->orderBy('created_at', 'desc')->limit(1000)->get());
    }

    public function index(Request $request) {
        return response()->json(
            Tool::all()->map(static function(Tool $tool) {
                return [
                    'id' => $tool->id,
                    'item' => $tool->item,
                    'family' => $tool->family,
                    'countrysu' => $tool->countrysu,
                    'countryor' => $tool->countryor,
                    'hazard' => $tool->hazard,
                    'king' => $tool->king,
                    'date' => $tool->date,
                    'product' => $tool->product,
                    'brand' => $tool->brand
                ];
            })
        );
    }

    public function show(Request $request, Tool $tool) {
        return response()->json([
            'id' => $tool->id,
            'item' => $tool->item,
            'family' => $tool->family,
            'countrysu' => $tool->countrysu,
            'countryor' => $tool->countryor,
            'hazard' => $tool->hazard,
            'king' => $tool->king,
            'date' => $tool->date,
            'product' => $tool->product,
            'brand' => $tool->brand,
            'reference' => $tool->reference,
            'spec1' => $tool->spec1,
            'spec2' => $tool->spec2,
            'spec3' => $tool->spec3,
            'user' => $tool->user,
            'supplier1' => $tool->supplier1,
            'contact1' => $tool->contact1,
            'email1' => $tool->email1,
            'supplier2' => $tool->supplier2,
            'contact2' => $tool->contact2,
            'email2' => $tool->email2,
            'supplier3' => $tool->supplier3,
            'contact3' => $tool->contact3,
            'email3' => $tool->email3,
            'files' => $tool->files->map(static function(File $file) {
                return $file->path;
            })
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $tool = $this->createTool($request);
            foreach ($request->documents as $document) {
                $tool->files()->create([
                    'path' => $document
                ]);
            }
            $request->user()->logs()->create([
                'tool_id' => $tool->id,
                'comment' => 'Se inserto registro',
                'type'=> 'insert',
                'after' => json_encode($this->getValues($tool->toArray(), $tool))
            ]);
            DB::commit();
            return response()->json(
                $tool
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, Tool $tool) {
        DB::beginTransaction();
        try {
            $request->user()->logs()->create([
                'tool_id' => $tool->id,
                'comment' => 'Se elimino registro',
                'type'=> 'delete',
            ]);
            $tool->deleteOrFail();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
        DB::commit();
        return response()->json($tool);
    }

    public function update(Request $request, Tool $tool) {
        DB::beginTransaction();
        try {
            $family = $this->getFamily($request->family);
            $countrysu = $this->getCountrysu($request->countrysu);
            $countryor = $this->getCountryor($request->countryor);
            $hazard = $this->getHazard($request->hazard);
            $king = $this->getKing($request->king);
            $oldTool = json_encode($this->getValues($tool->toArray(), $tool));
            if ($request->main_localization !== $tool->main_localization) {
                $tool->update([ 'quantity' => $tool->quantity - $request->movingQuantity ]);
                $request->quantity = $request->movingQuantity;
                $tool = $this->createTool($request);
                $request->user()->logs()->create([
                    'tool_id' => $tool->id,
                    'comment' => 'Se traspaso registro',
                    'type'=> 'update',
                    'before' => $oldTool,
                    'after' => json_encode($this->getValues($tool->toArray(), $tool))
                ]);
            } else {
                $tool->update([
                    'family_id' => $family->id ?? null,
                    'countrysu_id' => $countrysu->id ?? null,
                    'countryor_id' => $countryor->id ?? null,
                    'hazard_id' => $hazard->id ?? null,
                    'king_id' => $king->id ?? null,
                    'date' => $request->date,
                    'product' => $request->product,
                    'brand' => $request->brand,
                    'reference' => $request->reference,
                    'spec1' => $request->spec1,
                    'spec2' => $request->spec2,
                    'spec3' => $request->spec3,
                    'supplier1' => $request->supplier1,
                    'contact1' => $request->contact1,
                    'email1' => $request->email1,
                    'supplier2' => $request->supplier2,
                    'contact2' => $request->contact2,
                    'email2' => $request->email2,
                    'supplier3' => $request->supplier3,
                    'contact3' => $request->contact3,
                    'email3' => $request->email3
                ]);
                $oldValues = $tool->getChanges();
                if (count($oldValues) > 0) {
                    $request->user()->logs()->create([
                        'tool_id' => $tool->id,
                        'comment' => 'Se edito registro',
                        'type'=> 'update',
                        'before' => $oldTool,
                        'after' => json_encode($this->getValues($oldValues, $tool->refresh()))
                    ]);
                }
            }
            DB::commit();
            return response()->json(
                $tool
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

    private function createTool(Request $request) {
        $family = $this->getFamily($request->family);
        $countrysu = $this->getCountrysu($request->countrysu);
        $countryor = $this->getCountryor($request->countryor);
        $hazard = $this->getHazard($request->hazard);
        $king = $this->getKing($request->king);
        $tool = $request->user()->tools()->create([
            'family_id' => $family->id ?? null,
            'countrysu_id' => $countrysu->id ?? null,
            'countryor_id' => $countryor->id ?? null,
            'hazard_id' => $hazard->id ?? null,
            'king_id' => $king->id ?? null,
            'date' => $request->date,
            'product' => $request->product,
            'brand' => $request->brand,
            'reference' => $request->reference,
            'spec1' => $request->spec1,
            'spec2' => $request->spec2,
            'spec3' => $request->spec3,
            'supplier1' => $request->supplier1,
            'contact1' => $request->contact1,
            'email1' => $request->email1,
            'supplier2' => $request->supplier2,
            'contact2' => $request->contact2,
            'email2' => $request->email2,
            'supplier3' => $request->supplier3,
            'contact3' => $request->contact3,
            'email3' => $request->email3
        ]);
        $tool->update([
            'item' => sprintf('AAA%04d', $tool->id)
        ]);
        return $tool->refresh();
    }

    private function getValues($values, Tool $tool) {
//        dd($values, $tool);
        $specialAttributes = ['family_id' => 'family','countrysu_id' => 'countrysu','countryor_id' => 'countryor','hazard_id' => 'hazard','king_id' => 'king'];
        $names = ['item' => 'Item','family' => 'Familia/Family','countrysu_id' => 'Pais de suministro/Country of supply','countryor_id' => 'Pais de origen/Country of origin','hazard_id' => 'Peligrosidad/Hazard','king_id' => 'Tipo peligrosidad/King of hazard',
            'date' => 'Caducidad/Due date','product' => 'Producto/Product','brand' => 'Marca/Brand','reference' => 'Referencia/Reference','spec1' => 'Caracteristica 1/Spec','spec2' => 'Caracteristica 2/Spec 2','spec3' => 'Caracteristica 3/Spec 3',
            'supplier1' => 'Suministrador 1/Supplier 1', 'contact1' => 'Persona de contacto 1/Contact 1', 'email1' => 'Email 1', 'supplier2' => 'Suministrador 2/Supplier 2', 'contact2' => 'Persona de contacto 2/Contact 2', 'email2' => 'Email 2',
            'supplier3' => 'Suministrador 3/Supplier 3', 'contact3' => 'Persona de contacto 3/Contact 3', 'email3' => 'Email 3'];
        $data = array();
        foreach (array_keys($values) as $key) {
            if (array_key_exists($key, $specialAttributes)) {
                $data[$names[$key]] = $tool[$specialAttributes[$key]]->name ?? '';
            } else if(array_key_exists($key, $names)){
                $data[$names[$key]] = $values[$key];
            }
        }
        return $data;
    }

    public function showTool(Tool $tool): array {
        return [
            'id' => $tool->id,
            'item' => $tool->item,
            'family' => $tool->family,
            'countrysu' => $tool->countrysu,
            'countryor' => $tool->countryor,
            'hazard' => $tool->hazard,
            'king' => $tool->king,
            'date' => $tool->date,
            'product' => $tool->product,
            'brand' => $tool->brand,
            'reference' => $tool->reference,
            'spec1' => $tool->spec1,
            'spec2' => $tool->spec2,
            'spec3' => $tool->spec3,
            'user' => $tool->user,
            'supplier1' => $tool->supplier1,
            'contact1' => $tool->contact1,
            'email1' => $tool->email1,
            'supplier2' => $tool->supplier2,
            'contact2' => $tool->contact2,
            'email2' => $tool->email2,
            'supplier3' => $tool->supplier3,
            'contact3' => $tool->contact3,
            'email3' => $tool->email3
        ];
    }

    public function search(Request $request) {
        $especialKeys = ['family','countrysu','countryor','hazard','king','user'];
        $filters = $request->keys();
        $query = Tool::query();
        foreach($filters as $filter) {
            if (in_array($filter, $especialKeys, true)) {
                $value = is_null($request[$filter]) ? null : $request[$filter]['id'];
                if ($value !== 0) {
                    $query = $query->where("{$filter}_id", is_null($request[$filter]) ? null : $request[$filter]['id']);
                }
            }
            else if (!in_array($filter, $especialKeys, true)){
                $query = $query->where(Str::snake($filter), 'like', "%$request[$filter]%");
            }
        }
        $data = $query->get()->map(function(Tool $tool) {
            return $this->showTool($tool);
        });
        return response()->json($data);
    }

    private function getFamily($data)
    {
        if (is_null($data)) {
            return null;
        }
        if (is_array($data)) {
            return Family::find($data['id']);
        }
        return Family::where('name', $data)->firstOrCreate([
            'name' => $data
        ]);
    }

    private function getCountrysu($data)
    {
        if (is_null($data)) {
            return null;
        }
        if (is_array($data)) {
            return Countrysu::find($data['id']);
        }
        return Countrysu::where('name', $data)->firstOrCreate([
            'name' => $data
        ]);
    }

    private function getCountryor($data)
    {
        if (is_null($data)) {
            return null;
        }
        if (is_array($data)) {
            return Countryor::find($data['id']);
        }
        return Countryor::where('name', $data)->firstOrCreate([
            'name' => $data
        ]);
    }

    private function getHazard($data)
    {
        if (is_null($data)) {
            return null;
        }
        if (is_array($data)) {
            return Hazard::find($data['id']);
        }
        return Hazard::where('name', $data)->firstOrCreate([
            'name' => $data
        ]);
    }

    private function getKing($data)
    {
        if (is_null($data)) {
            return null;
        }
        if (is_array($data)) {
            return King::find($data['id']);
        }
        return King::where('name', $data)->firstOrCreate([
            'name' => $data
        ]);
    }
}
