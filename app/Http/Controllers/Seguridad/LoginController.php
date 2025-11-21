<?php

namespace App\Http\Controllers\Seguridad;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //use Notifiable;
    use AuthenticatesUsers;



   // protected $redirectTo = 'hoursxuser';



    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function index(Request $request)
    {

           return view('seguridad.index');
    }
    
  



    protected function authenticated(Request $request, $user)
    {

        $useractivo = $user->where([
            ['usuario', '=', $request->usuario],
            ['activo', '=', 1]

        ])->count();


        $roles1 = $user->roles1()->first();





        if ($roles1->id == 1 || $roles1->id == 2 || $roles1->id == 3 && $useractivo >= 1) {
            $user->setSession();
            return redirect('paliativos-index');

        }else if ($roles1->id == 4 && $useractivo >= 1) {
           $user->setSession();
            return redirect('empleado');

        }else if ($roles1->id == 5 && $useractivo >= 1) {
           $user->setSession();
            return redirect('reporte_psicologia');

        }else if ($roles1->id == 6 && $useractivo >= 1) {
           $user->setSession();
            return redirect('consultar_evolucion');

        }{
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect('seguridad/login')->withErrors(['error'=>'Este usuario no esta activo y no tiene rol ']);
        }

    }

    public function username()
    {
        return 'usuario';
    }
    public function loginMovil(Request $request)
    {

         if(Auth::attempt($request->only('usuario','password'))){

            $user = Auth::user();

            return Response()->json([
            'user' => $user
        ], 200);



         }else{



            return response()->json(['error'=> 'Unauthorised'], 401);

         }



    }


}
