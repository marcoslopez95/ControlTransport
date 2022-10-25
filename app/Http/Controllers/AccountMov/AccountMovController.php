<?php

namespace App\Http\Controllers\AccountMov;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AccountMov;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AccountMovController extends Controller
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

            $account_mov = AccountMov::with([
              'vehicle','amountMovs'
            ])->orderBy('id', 'DESC')->get();

            DB::commit();
          } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
              'data' => [
                'code'   => $e->getCode(),
                'title'  => [__('messages..accountMov.index.internal_error')],
                'errors' => $e->getMessage()
              ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
          }

          return ["list" => $account_mov, "total" => count($account_mov)];
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

            $id =  $this->createAccountMov($request);

            $response = AccountMov::where('id', $id)->first();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'data' => [
                    'code'   => $e->getCode(),
                    'title'  => [__('Erron al guardar movimiento de cuenta')],
                    'errors' => $e->getMessage(),
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(array(
            'success' => true,
            'message' => 'movimiento de cuenta creado',
            'value'   => $response,
        ));
    }


    protected function createAccountMov($request)
    {
        $account_mov = new AccountMov();
         $account_mov->vehicle_id = $request->vehicle_id;
         $account_mov->date = $request->date;
         $account_mov->description = $request->description;
         $account_mov->type = $request->type;
         $account_mov->save();

        return  $account_mov->id;
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
            $response = AccountMov::with(['vehicle','amountMovs'])
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

            $lottery = AccountMov::findOrFail($id);
            $lottery->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'data' => [
                    'code'   => $e->getCode(),
                    'title'  => [__('fallo al eliminar movimiento de cuenta')],
                    'errors' => $e->getMessage(),
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([
            "message"       => "movimiento eliminado",
        ]);
    }
}
