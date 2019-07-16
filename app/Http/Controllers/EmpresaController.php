<?php

namespace App\Http\Controllers;

//peticiones http
use Illuminate\Http\Request;
//validacion del request para crear empresa
use App\Http\Requests\CreateEmpresaRequest;
//validacion del request para actualizar empresa
use App\Http\Requests\UpdateEmpresaRequest;
//subir imagenes
use Illuminate\Support\Facades\Storage; //guardar en el disco
use Illuminate\Support\Facades\File; //traer el archivo
//para crear un objeto json
use Illuminate\Support\Collection as Collection;
//para try y catch
Use Exception;
//modelo de empresa
use App\User;
use App\Encargado;
use App\Empresa;

class EmpresaController extends Controller {

    //=======================================
    //Crea un empresa mediante el post
    //=======================================
    public function crearEmpresa(CreateEmpresaRequest $request) {
        //instancio la empresa
        $empresa = new Empresa;

        //declaro estas variables en el modelo de la empresa
        $empresa->nombre = strtoupper(trim(strip_tags($request->input('nombre')))); //limpio los espacios, limpio xss, lo paso a mayusculas
        $empresa->apellido = strtoupper(trim(strip_tags($request->input('apellido'))));
        $empresa->documento = trim($request->input('documento'));
        $empresa->nombreEmpresa = trim(strip_tags($request->input('nombreEmpresa')));
        $empresa->password = trim($request->input('password'));
        $empresa->ubicacion = trim($request->input('ubicacion'));
        $empresa->provincia = trim($request->input('provincia'));
        $empresa->pais = trim($request->input('pais'));
        $empresa->role = "empresa";
        $empresa->estado = "true";


        //compruebo si esta duplicado y mando el response
        $comprovacionEmpresa = Empresa::where('documento', $empresa->documento)->first();
        
        if ($comprovacionEmpresa) {
            return $this->Error('Campo documento duplicado', 409);
        }
        
        //si la peticion tiene una imagen tomo el archivo lo guardo con otro nombre
        //en el disco imagen y paso el nombre de esta al usuario si no dejo la imagen null
        if ($request->hasFile('img')) {
            $image_path = $request->file('img');

            $imagen_guardar = time() . $image_path->getClientOriginalName();
            Storage::disk('images')->put($imagen_guardar, File::get($image_path));
            $empresa->img = $imagen_guardar;
        } else {
            $empresa->img = NULL;
        }

        //si no esta duplicado lo guardo, si pasa algo lo aviso
        try {
            $empresa->save();
        } catch (Exception $e) {
            return $this->Error('Error del servidor', 500);
        }
        //si todo sale bien mando la respuesta
        $requestObj = new Request(array('ok' => true, "respuesta" => $empresa));
        return response($requestObj, 201);
    }

    //=======================================
    //Mando empresa con pedido,imagen y usuario perteneciente y encargado desarrolando la misma
    //=======================================
    public function empresa($id) {

        //busca el encargado si no lo encuentra avisa 
        try {

            $empresaDB = Empresa::where('id', $id)->where('estado', 'true')->with('pedidos')->with('encargados')->first();
        } catch (Exception $e) {
            return $this->Error('Error del servidor', 500);
        }
        //si da una respuesta vacia es un error del servidor sino todo ok
        if (!$empresaDB) {
            return $this->Error('Id no encontrado en la base de datos', 404);
        } else {
            $requestObj = new Request(array('ok' => true, "respuesta" => $empresaDB));
            return response($requestObj, 200);
        }
    }

    //=======================================
    //modificar una empresa mediante el post
    //=======================================
    public function editar(UpdateEmpresaRequest $request) {
        
        try {// busco la empresa y corroboro que sea true
            $empresaDB = Empresa::where('id', $request->input('id'))->where('estado', 'true')->first();
        } catch (Exception $e) {
            return $this->Error('Algo sucedio', 500);
        }

        if (!$empresaDB) {
            return $this->Error('Id no encontrado en la base de datos', 404);
        } else {
            //si quiere cambiar el documento corroborro que este no este en la base de datos
            if ($request->input('documento')) {
                $empresaDB->documento = trim($request->input('documento'));

                //compruebo si esta duplicado y mando el response
                $comprovacionEmpresa = Empresa::where('documento', $empresaDB->documento)->first();
        
                if ($comprovacionEmpresa) {
                    return $this->Error('Campo documento se encuentra duplicado', 409);
                }
            }

            $empresaDB->nombre = strtoupper(trim(strip_tags($request->input('nombre')))); //limpio los espacios, limpio xss, lo paso a mayusculas
            $empresaDB->apellido = strtoupper(trim(strip_tags($request->input('apellido'))));
            $empresaDB->nombreEmpresa = trim(strip_tags($request->input('nombreEmpresa')));
            $empresaDB->password = trim($request->input('password'));
            $empresaDB->ubicacion = trim($request->input('ubicacion'));
            $empresaDB->provincia = trim($request->input('provincia'));
            $empresaDB->pais = trim($request->input('pais'));

            //compruebo si hay archivo
            if ($request->hasFile('img')) {

                if ($empresaDB->img) { //si la empresa tiene archivo lo borro
                    $file = Storage::disk('images')->delete($empresaDB->img);
                };

                $image_path = $request->file('img');

                $imagen_guardar = time() . $image_path->getClientOriginalName();
                Storage::disk('images')->put($imagen_guardar, File::get($image_path));
                $empresaDB->img = $imagen_guardar;
            }

            try {
                $empresaDB->save();
            } catch (Exception $e) {
                return $this->Error('Error del servidor', 500);
            }

            $requestObj = new Request(array('ok' => true, "respuesta" => $empresaDB));
            return response($requestObj, 201);
        }
    }

    //=======================================
    //borrar una empresa mediante el id
    //=======================================
    public function borrar($id) {
        try {
            $empresaDB = Empresa::where('id', $id)->where('estado', 'true')->first();
        } catch (Exception $e) {
            return $this->Error('Error del servidor', 500);
        }
        if (!$empresaDB) {
            return $this->Error('Id no encontrado en la base de datos', 404);
        } else {
            $empresaDB->estado = "false";
            $empresaDB->save();

            $requestObj = new Request(array('ok' => true, "respuesta" => "borrado"));
            return response($requestObj, 201);
        }
    }

    //=======================================
    //Encargados de esta empresa
    //=======================================
    public function encargados($id) {

        //busca el encargado si no lo encuentra avisa 
        try {
            $empresaDB = Empresa::where('id', $id)->where('estado', 'true')->with('encargadoSinPedido')->first();
        } catch (Exception $e) {
            return $this->Error('Error del servidor', 500);
        }
        //si da una respuesta vacia es un error del servidor sino todo ok
        if (!$empresaDB) {
            return $this->Error('Id no encontrado en la base de datos', 404);
        } else {
            $requestObj = new Request(array('ok' => true, "respuesta" => $empresaDB));
            return response($requestObj, 200);
        }
    }

    //=======================================
    //Todos los pedidos
    //=======================================
    public function historial($id) {

        //busca el encargado si no lo encuentra avisa 
        try {
            
            $empresaDB = Empresa::where('id', $id)->where('estado', 'true')->with('encargados')->first();
            $pedidos = array();
            //de los encargados saca todos los pedidos
            foreach ($empresaDB->encargados as $encargado) {
                if (!empty($encargado->pedidos)) {
                    array_push($pedidos, $encargado->pedidos);
                }
            }
        } catch (Exception $e) {
            return $this->Error('Error del servidor', 500);
        }
        //si da una respuesta vacia es un error del servidor sino todo ok
        if (!$empresaDB) {
            return $this->Error('Id no encontrado en la base de datos', 404);
        } else {
            $requestObj = new Request(array('ok' => true, "respuesta" => $pedidos));
            return response($requestObj, 200);
        }
    }

    //===============================================
    //funciones
    //===============================================
    
    private function Error($mensajeMandar, $error) {
        $mensaje = ['mensaje' => $mensajeMandar];
        $mensajeJson = Collection::make($mensaje);
        $mensajeJson->toJson();

        $requestObj = new Request(array('ok' => false, "error" => $mensajeJson));
        return response($requestObj, $error);
    }
}
