<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Lock;
use App\User;
use App\Http\Requests\EditLockRequest;
use App\Http\Requests\CreateLockRequest;
use App\Notification;
class LockController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $locks=Lock::where('user_id', Auth::user()->id)->get();

    return view('pages/lock/userLocks',['locks'=>$locks]);
  }

  public function register()
  {
    $locks = Lock::where('user_id', Auth::user()->id)->count();

    return view('pages/lock/userRegisterLock', ['locks' => $locks]);
  }

  public function create(CreateLockRequest $request)
  {
    $validated = $request->validated();
    if (Lock::onlyTrashed()->where(['serial_n' => $request->input('lockSerial')])->first() != null) {
      $lock = Lock::onlyTrashed()->where(['serial_n' => $request->input('lockSerial')])->first();
      $lock->name = $request->input('lockName');
      $lock->serial_n = $request->input('lockSerial');
      if ($request->input('checkaddress') == 'SI') {
        $lock->latitude = $request->input('latitude');
        $lock->longitude = $request->input('longitude');
      }
      $lock->user_id = Auth::user()->id;
      $lock->save();
      $notification = new Notification;
      $notification->title = "Se ha creado la cerradura ".$lock->name;
      $notification->message = "Has creado la cerradura ".$lock->name." el ".date("Y-m-d H:i:s");
      $notification->marker = 3;
      $notification->notificable = 1;
      $notification->user_id = Auth::user()->id;
      $notification->lock_id = $lock->id;
      $notification->save();
    }else {
      $lock = new Lock;
      $lock->name = $request->input('lockName');
      $lock->serial_n = $request->input('lockSerial');
      if ($request->input('checkAddress') == 'checked') {
        $lock->latitude = $request->input('latitude');
        $lock->longitude = $request->input('longitude');
      }
      $lock->user_id = Auth::user()->id;
      $lock->save();
      $notification = new Notification;
      $notification->title = "Se ha creado la cerradura ".$lock->name;
      $notification->message = "Has creado la cerradura ".$lock->name." el ".date("Y-m-d H:i:s");
      $notification->marker = 3;
      $notification->notificable = 1;
      $notification->user_id = Auth::user()->id;
      $notification->lock_id = $lock->id;
      $notification->save();

      $newLock = Lock::where('serial_n',$request->input('lockSerial'))->first();

      //añadir a la tabla privileges

      return redirect()->action('LockController@show', ['id' => $newLock]);
    }



    //return view('pages/lock/lock',['lock'=>$lock]);
  }

  public function show($id)
  {

    if (Lock::where('id',$id)->exists()) {
      $lock= Lock::find($id);
      if (Auth::user()->id == $lock->user_id) {
        $notifications  = Notification::where(['lock_id' => $lock->id, 'notificable' => 1])->orderBy('id', 'desc')->get();
        return view('pages/lock/userLock',['lock'=>$lock, 'notifications' => $notifications]);
      }else{
        foreach (Auth::user()->privileges as $privilege) {
          if ($privilege->id == $lock->id) {
            $privileged = $privilege->pivot->privilege;
            if ($privileged == 1) {
              $notifications  = Notification::where(['lock_id' => $privilege->id, 'notificable' => 1])->orderBy('id', 'desc')->get();
              return view('pages/lock/userLock',['lock'=>$lock, 'privileged' => $privileged, 'notifications' => $notifications]);
            }
            return view('pages/lock/userLock',['lock'=>$lock, 'privileged' => $privileged]);
          }
        }
        abort(404);
      }

    }else{
      abort(404);
    }


  }

  public function updateLocation($id, $lat, $lng){
    $lock=Lock::find($id);
    $lock->latitude = $lat;
    $lock->longitude = $lng;
    /*    $notification = new Notification;
    $notification->title = "Se ha modificado la ubicacion de la cerradura ".$lock->name;
    $notification->message = "Has modificado la ubicacion de la cerradura ".$lock->name." el ".date("Y-m-d H:i:s");
    $notification->marker = 3;
    $notification->notificable = 1;
    $notification->user_id = Auth::user()->id;
    $notification->lock_id = $lock->id;
    $notification->save(); */
    $lock->save();

    return "Ubicacion actualizada";
  }
  public function deleteLocation($id){
    $lock=Lock::find($id);
    $lock->latitude = null;
    $lock->longitude = null;
    /*  $notification = new Notification;
    $notification->title = "Se ha eliminado la ubicacion de la cerradura ".$lock->name;
    $notification->message = "Has eliminado la ubicacion de la cerradura ".$lock->name." el ".date("Y-m-d H:i:s");
    $notification->marker = 3;
    $notification->notificable = 1;
    $notification->user_id = Auth::user()->id;
    $notification->lock_id = $lock->id;
    $notification->save();  */
    $lock->save();

    return "Ubicacion eliminada";
  }
  public function update(EditLockRequest $request, $id)
  {

    $validated = $request->validated();
    $lock=Lock::find($id);

    $lock->name = $request->input('newLockName');

    $notification = new Notification;
    $notification->title = "Se ha actualizado la cerradura ".$lock->name;
    $notification->message = "Has actualizado el nombre de la cerradura ".$lock->name." el ".date("Y-m-d H:i:s");
    $notification->marker = 3;
    $notification->notificable = 1;
    $notification->user_id = Auth::user()->id;
    $notification->lock_id = $lock->id;
    $notification->save();
    $lock->save();


    return redirect()->action('LockController@show', ['id' => $lock]);

  }

  public function destroy($id)
  {
    $lock = Lock::findOrFail($id);
    if (Auth::user()->id == $lock->user_id){
      $notification = new Notification;
      $notification->title = "Se ha eliminado la cerradura ".$lock->name;
      $notification->message = "Has eliminado la cerradura ".$lock->name." el ".date("Y-m-d H:i:s");
      $notification->marker = 0;
      $notification->notificable = 1;
      $notification->user_id = Auth::user()->id;
      $notification->lock_id = $lock->id;
      $notification->save();
      foreach ($lock->privileges as $privilege) {
        $lock->privileges()->detach($privilege->id);
      }
      $lock->delete();
      return redirect()->action('LockController@index');
    }else{
      abort(404);
    }

  }
  public function insertPrivilege(Request $request, $id)
  {
    $lock = Lock::find($id);
    $email = $request->input('email');
    $mod = $request->input('role');


    $user = User::where('email', $email)->first();
    if (isset($user)) {
      $request->session()->flash('privilegeOk', 'Permiso otorgado con exito');
      $lock->privileges()->detach($user);
      $lock->privileges()->attach($user,['privilege' => $mod]);
      $nomMod = "basico";
      $notification = new Notification;
      $notification->title = "Se ha añadido permisos en la cerradura ".$lock->name;
      $notification->message = "Has dado permiso a".$email." con permiso ".$nomMod." en la cerradura ".$lock->name." el ".date("Y-m-d H:i:s");
      $notification->marker = 4;
      $notification->notificable = 1;
      $notification->user_id = Auth::user()->id;
      $notification->lock_id = $lock->id;
      $notification->save();
      return redirect()->action('LockController@show',['lock'=>$lock]);
    }else{
      $request->session()->flash('privilegeFail', 'El permiso no ha podido ser otorgado, compruebe que el email existe');
      return back();
    }
  }

  public function deletePrivilege($lock, $user)
  {
    $lockd = Lock::find($lock);
    $lockd->privileges()->detach($user);
    $notification = new Notification;
    $notification->title = "Se ha quitado permisos en la cerradura ".$lockd->name;
    $notification->message = "Has quitado permiso a".$lockd->email." en la cerradura ".$lockd->name." el ".date("Y-m-d H:i:s");
    $notification->marker = 0;
    $notification->notificable = 1;
    $notification->user_id = Auth::user()->id;
    $notification->lock_id = $lockd->id;
    $notification->save();
    return redirect()->action('LockController@show',['lock'=>$lockd]);
  }

}
