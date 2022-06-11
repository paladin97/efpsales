<?php

namespace App\Http\Controllers;

use App\Models\{Menssage, Notify, User};
use Illuminate\Http\Request;
use Auth, DB;
use Fideloper\Proxy\TrustedProxyServiceProvider;
use Pusher\Pusher;
use App\Events\ReadMessagesEvent;


class ChatsController extends Controller
{
    private $pusher;

    public function __construct()
    {
        $this->middleware('auth');

        $this->pusher =  new Pusher(
            env("PUSHER_APP_KEY"), // public key
            env("PUSHER_APP_SECRET"), // Secret
            env("PUSHER_APP_ID"), // App_id
            array(
                'cluster' => env("PUSHER_APP_CLUSTER"), // Cluster
                'encrypted' => true,
            )
        );

        
    }

    //Funcion de Enviar Mensaje
    public function sendMenssages(Request $request)
    {
        // Salvar mensaje en la tabla 

        $message = new Menssage;

        if ($request->contens) {
            //Coger el id del usuario autenticado 
            $user = Auth::user();
            $message->user_send_id = $user->id;

            if ($user->hasRole('superadmin')) {
                $message->user_receive_id = $request->user_receive_id;
            } else {
                // Si el usuario no es administrador entonces a quien va dirigido es al admin
                $message->user_receive_id = 2;
            }

            $message->status_id = 1;
            $message->contens = $request->contens;
            $message->save();

            // hacer que se le actualice el chat a la persona que tiene que recibir el mensaje y que le llegue la notificacion
            $notify = Notify::where('user_receive_id', $request->user_receive_id)
                ->where('user_send_id', $request->user_send_id)
                ->first();

            $notify->count_notify = $notify->count_notify + 1;
            $notify->last_message = date("Y-m-d H:i:s");
            $notify->save();

            $this->pusher->trigger('chat-' . $request->user_receive_id, 'send', [
                'user_send' => $user->id,
                'contens' => $request->contens,
                'name_user_send' => $user->name

            ]);

            echo json_encode(array('res' => true));
        } else {
            echo json_encode(array('res' => false));
        }
    }

    // Funcion para cargar todos los mensajes y las conversaciones en el caso del admin cuando cambie de chat
    public function loadChat(Request $request)
    {
        $menssages = Menssage::getMenssages($request['send'], $request['receive']);

        event(new ReadMessagesEvent($request['send'], $request['receive']));

        echo json_encode($menssages);
    }


    // pagina principal
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('superadmin')) {


            $list_chats = DB::select(
                "SELECT users.id ,users.name , chats_user_notify.last_message, chats_user_notify.count_notify, chats_user_notify.updated_at, chats_user_notify.user_send_id, chats_user_notify.user_receive_id
                FROM users
                JOIN   chats_user_notify
                WHERE (chats_user_notify.user_receive_id = 2 AND users.id = chats_user_notify.user_send_id ) OR (chats_user_notify.user_send_id = 2 AND users.id = chats_user_notify.user_receive_id ) AND users.id <>2  
                ORDER BY chats_user_notify.last_message desc , users.name asc"
            );


            $list_name = [];
            $new_list_chats = [];

            foreach ($list_chats as $elem) {
                if (!in_array($elem->name, $list_name)) {

                    if ($elem->user_send_id == 2) {
                        $elem->count_notify = 0;
                    }

                    array_push($list_name, $elem->name);
                    array_push($new_list_chats, $elem);

                }else{
                    $clave = array_search($elem->name, $list_name);   
                    $new_list_chats[$clave]->last_message = $elem->last_message ;
                }
            }

            $list_chats = $new_list_chats;



            $menssages = Menssage::getMenssages($list_chats[0]->id, $user->id);

            event(new ReadMessagesEvent($user->id, $list_chats[0]->id));

            //=======================================//
            //=======================================//
        }
        //=======================================//
        // Crear el chats-g del comercia 
        //=======================================//

        if ($user->hasRole('comercial')) {
            $menssages = Menssage::where('user_send_id', $user->id)
                ->orWhere('user_receive_id', $user->id)
                ->get();

            $list_chats = null;

            event(new ReadMessagesEvent($user->id, null));
        }

        return view('chat.index', compact('menssages', 'list_chats'));
    }
}
