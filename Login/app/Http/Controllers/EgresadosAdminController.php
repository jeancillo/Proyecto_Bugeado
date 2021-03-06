<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Egresado;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
//nombre del alias de \Barryvdh\Dompdf\ServiceProvider ubicado en config ->app-> array 'aliases'
use App\Http\Requests\EgresadoEditRequest;
use App\Http\Requests\EgresadoCreateRequest;
use PDF;
//use Barryvdh\DomPDF\PDF;

class EgresadosAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $texto=$request->get('texto');

        //trae de la tabla egresa$egresados todo los campos
        $egresados=DB::table('egresado')
        ->select('matricula','ap_paterno','ap_materno','nombres','genero','fecha_nacimiento','telefono')
        ->where('ap_paterno','LIKE','%'.$texto.'%')
        ->orWhere('nombres', 'LIKE', '%'.$texto.'%')
        ->orWhere('matricula', 'LIKE', '%'.$texto.'%')
        ->orderBy('ap_paterno','asc')
        ->paginate(5);
        return view('admin.egresado.index',compact('egresados','texto'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.egresado.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EgresadoCreateRequest $request)
    {
        //

        $egresados=new Egresado;
        $egresados->matricula=$request->input('matricula');
        $egresados->ap_paterno=$request->input('ap_paterno');
        $egresados->ap_materno = $request->input('ap_materno');
        $egresados->nombres = $request->input('nombres');
        $egresados->genero = $request->input('genero');
        $egresados->fecha_nacimiento = $request->input('fecha_nacimiento');
        $egresados->telefono = $request->input('telefono');
        $egresados->save();
        return redirect()->route('egresado.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($matricula)
    {
        //

        $egresados=Egresado::findOrFail($matricula);

        //return $egresados;
        return view('admin.egresado.editar',compact('egresados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EgresadoEditRequest $request,$matricula)
    {

        $egresados=Egresado::findOrFail($matricula);
        $egresados->matricula=$request->input('matricula');
        $egresados->ap_paterno=$request->input('ap_paterno');
        $egresados->ap_materno = $request->input('ap_materno');
        $egresados->nombres = $request->input('nombres');
        $egresados->genero = $request->input('genero');
        $egresados->fecha_nacimiento = $request->input('fecha_nacimiento');
        $egresados->telefono = $request->input('telefono');
        $egresados->save();
        return redirect()->route('egresado.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($matricula)
    {
        //
        $egresados = Egresado::findOrFail($matricula);
        $egresados->delete();
        return redirect()->route('egresado.index');
    }

//funcion para exportar datos a formato PDF
public function pdf(Request $request)
{
    //$egresados=Egresado::all();
  //  $egresados=Egresado::all();
//$egresados=Egresado::select('ap_paterno','ap_materno','nombres')->get();
    //$pdf =\PDF::loadView('download',compact('egresados'));
   // return $pdf->stream('download.pdf');

/*    $egresados=Egresado::all();
   view()->share('egresado', $egresados);
   $pdf = PDF::loadView('donwload', $egresados);
   return $pdf->download('archivo-pdf.pdf'); */
   $egresados=Egresado::paginate();

   return view('download');

}

}
?>
