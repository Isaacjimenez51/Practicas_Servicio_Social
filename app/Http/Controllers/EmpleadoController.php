<?php

namespace App\Http\Controllers;

use App\Models\Empleado;

use Illuminate\Contracts\Cache\Store;

use Illuminate\Http\Request;

use PharIo\Manifest\Email;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Model;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use Illuminate\Support\Facades\Storage;

class EmpleadoController extends Controller

{

    /**

    * Display a listing of the resource.

    *

    * @return \Illuminate\Http\Response

    */

    public function index()

    {

        //

        //$datos['empleados']=Empleado::paginate(1);

        //return view('empleado.index',$datos);

        /*

        $datosEmpleado = DB::table('empleados')->paginate(3);

        return view('empleado.index',['empleados'=>$datosEmpleado]);

        */

        $datos = DB::select('select * from empleados');

         //return view('empleado.index',['empleados'=>$resultados]);

          return view('empleado.index',['empleados'=>$datos])  ;

    }



    /**

    * Show the form for creating a new resource.

    *


    * @return \Illuminate\Http\Response

    */

    public function create()

    {

        //

        return view('empleado.create');

    }

    /**

    * Store a newly created resource in storage.

    *

    * @param  \Illuminate\Http\Request  $request

    * @return \Illuminate\Http\Response

    */

    public function store(Request $request)

    {


        $campos=[

            'Nombre'=>'required|string|max:18',

            'ApellidoPaterno'=>'required|string|max:18',

            'ApellidoMaterno'=>'required|string|max:18',

            'Correo'=>'required|string|max:64',

            'Foto'=>'required|max:100000|mimes:jpeg,png,jpg',

        ];

        $mensaje=[

            'required'=>'El :attribute es requerido',

            'Foto.required'=>'La foto rquerida'

        ];

        $this->validate($request,$campos,$mensaje);

        $datosEmpleado = request()->except('_token');

        //Empleado::insert($datosEmpleado);

        //return response()->json($datosEmpleado);

        /*

        DB::table('empleados')->insert([

            'Nombre' => $request ->Nombre,

            'ApellidoPaterno' => $request ->ApellidoPaterno,

            'ApellidoMaterno' => $request ->ApellidoMaterno,

            'Correo' => $request ->Correo,

            'Foto' => $request ->file('Foto')->store('upload','public'),

        ]);

        */

        /*


        if($request->hasFile('Foto')){

            $datosEmpleado['Foto']=$request->file('Foto')->store('uploads','public');

        }

        */

        DB::insert("INSERT INTO empleados(Nombre,ApellidoPaterno,ApellidoMaterno,Correo,Foto)VALUES(?,?,?,?,?)",

        [$request->Nombre,$request->ApellidoPaterno,$request->ApellidoMaterno,$request->Correo,$request ->file('Foto')->store('upload','public')]);

        return redirect('empleado')->with('mensaje','Empleado agregado con éxito');

    }


    /**

    * Display the specified resource.

    *

    * @param  \App\Models\Empleado  $empleado

    * @return \Illuminate\Http\Response

    */

    public function show(Empleado $empleado)

    {

        //

    }


    /**

    * Show the form for editing the specified resource.

    *


    * @param  \App\Models\Empleado  $empleado

    * @return \Illuminate\Http\Response

    */

    public function edit($id)

    {

        //

        $empleado=Empleado::findOrFail($id);

        return view('empleado.edit',compact('empleado'));


    }

    /**
     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\Models\Empleado  $empleado

     * @return \Illuminate\Http\Response

     */

     public function update(Request $request, $id)

     {

        $campos=[

            'Nombre'=>'required|string|max:18',

            'ApellidoPaterno'=>'required|string|max:18',

            'ApellidoMaterno'=>'required|string|max:18',

            'Correo'=>'required|string|max:64',

        ];

        $mensaje=[

            'required'=>'El :attribute es requerido',

        ];

        if($request->hasFile('Foto')){

            $campos=['Foto'=>'required|max:100000|mimes:jpeg,png,jpg',];

            $mensaje=['Foto.required'=>'La foto rquerida'];

        }

        $this->validate($request,$campos,$mensaje);

        //

        $datosEmpleado = request()->except(['_token','_method']);

        /*

        DB::table('empleados')->where('id',$id)->update([

            'Nombre' => $request ->Nombre,

            'ApellidoPaterno' => $request ->ApellidoPaterno,

            'ApellidoMaterno' => $request ->ApellidoMaterno,

            'Correo' => $request ->Correo,

            'Foto' => $request ->file('Foto')->store('upload','public'),

        ]);

        */

        /*

        if($request->hasFile('Foto')){

            $empleado=Empleado::findOrFail($id);

            Storage::delete('public/'.$empleado->Foto);

            $datosEmpleado['Foto']=$request->file('Foto')->store('uploads','public');
        }

        Empleado::where('id','=',$id)->update($datosEmpleado);

        $empleado=Empleado::findOrFail($id);

        */

        //return view('empleado.edit',compact('empleado'));

        DB::update('UPDATE empleados SET Nombre = ? , ApellidoPaterno = ? , ApellidoMaterno = ?, Correo=?, Foto=? WHERE id = ? ',

        [$request->Nombre,$request->ApellidoPaterno,$request->ApellidoMaterno,$request->Correo,$request ->file('Foto')->store('upload','public'),$id]);

        return redirect('empleado')->with('mensaje','Empleado Modificado');

    }

    /**

    * Remove the specified resource from storage.

    *

    * @param  \App\Models\Empleado  $empleado

    * @return \Illuminate\Http\Response

    */

    public function destroy($id)

    {

        /*

        $empleado=Empleado::findOrFail($id);


        if(Storage::delete('public/'.$empleado->Foto)){

             Empleado::destroy($id);

        }

        */

        /*

        DB::table('empleados')->whereId($id)->delete();

        */

        DB::delete("DELETE FROM empleados WHERE id=$id");

        return redirect('empleado')->with('mensaje','Empleado Borrado');

    }

}
