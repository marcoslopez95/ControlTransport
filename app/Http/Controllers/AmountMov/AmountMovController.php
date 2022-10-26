<?php

namespace App\Http\Controllers\AmountMov;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AmountMov;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AmountMovController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            DB::beginTransaction();

            $amountMov = AmountMov::with([
              'accountMov','coin'
            ])->orderBy('id', 'DESC')->get();

            DB::commit();
          } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
              'data' => [
                'code'   => $e->getCode(),
                'title'  => [__('messages..AmountMov.index.internal_error')],
                'errors' => $e->getMessage()
              ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
          }

          return ["list" =>  $amountMov, "total" => count($amountMov)];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $id =  $this->createAmountMov($request);

            $response = AmountMov::where('id', $id)->first();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'data' => [
                    'code'   => $e->getCode(),
                    'title'  => [__('Erron al guardar movimiento')],
                    'errors' => $e->getMessage(),
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(array(
            'success' => true,
            'message' => 'movimiento creado',
            'value'   => $response,
        ));
    }

    protected function createAmountMov($request)
    {
        $amountMov = new AmountMov();
          $amountMov->coin_id = $request->coin_id;
          $amountMov->quantity = $request->quantity;
          $amountMov->account_mov_id = $request->account_mov_id;
          $amountMov->save();

        return   $amountMov->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            DB::beginTransaction();
            $response = AmountMov::with([
                'accountMov','coin'
              ])
                ->where('id', $id)->first();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'data' => [
                    'code'   => $e->getCode(),
                    'title'  => [__('error en show')],
                    'errors' => $e->getMessage(),
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ["list" => $response];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $lottery = AmountMov::findOrFail($id);
            $lottery->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'data' => [
                    'code'   => $e->getCode(),
                    'title'  => [__('fallo al eliminar movimiento')],
                    'errors' => $e->getMessage(),
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([
            "succes"  =>true,
            "message"       => "movimiento eliminado",
        ]);
    }
}
