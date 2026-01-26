<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Reservation;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ConversationController extends Controller
{
    /**
     * Afficher la liste des conversations du promoteur
     */
    public function index(Request $request)
    {
        $promoter = Auth::user();
        
        // Récupérer les conversations du promoteur
        $query = Conversation::with(['user', 'reservation.salle', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->where('promoter_id', $promoter->id)
            ->orderBy('last_message_at', 'desc');

        $conversations = $query->paginate(15);

        // Transformer les données
        $conversations->getCollection()->transform(function ($conversation) use ($promoter) {
            $lastMessage = $conversation->messages->first();
            
            return [
                'id' => $conversation->id,
                'reservation_id' => $conversation->reservation_id,
                'client_name' => $conversation->user->name,
                'client_email' => $conversation->user->email,
                'venue_name' => $conversation->reservation->salle->nom,
                'reservation_status' => $conversation->reservation->statut,
                'last_message' => $lastMessage ? [
                    'content' => $lastMessage->content,
                    'sender_id' => $lastMessage->sender_id,
                    'created_at' => $lastMessage->created_at->toISOString(),
                    'is_read' => $lastMessage->is_read,
                ] : null,
                'last_message_at' => $conversation->last_message_at?->toISOString(),
                'unread_count' => $conversation->getUnreadCountForUser($promoter->id),
                'created_at' => $conversation->created_at->toISOString(),
            ];
        });

        return Inertia::render('Promoter/Conversations', [
            'conversations' => $conversations
        ]);
    }

    /**
     * Afficher une conversation spécifique
     */
    public function show(Conversation $conversation)
    {
        // Vérifier que la conversation appartient au promoteur
        $this->authorizeConversation($conversation);
        
        // Marquer les messages comme lus
        $conversation->markMessagesAsReadForUser(Auth::id());
        
        // Charger la conversation avec tous les messages
        $conversation->load([
            'user',
            'reservation.salle',
            'messages' => function ($query) {
                $query->with('sender')->orderBy('created_at', 'asc');
            }
        ]);

        // Transformer les messages
        $messages = $conversation->messages->map(function ($message) {
            return [
                'id' => $message->id,
                'content' => $message->content,
                'sender_id' => $message->sender_id,
                'sender_name' => $message->sender->name,
                'is_from_me' => $message->sender_id === Auth::id(),
                'is_read' => $message->is_read,
                'created_at' => $message->created_at->toISOString(),
            ];
        });

        return Inertia::render('Promoter/Conversation', [
            'conversation' => [
                'id' => $conversation->id,
                'reservation_id' => $conversation->reservation_id,
                'client' => [
                    'name' => $conversation->user->name,
                    'email' => $conversation->user->email,
                    'telephone' => $conversation->user->telephone,
                ],
                'venue' => [
                    'name' => $conversation->reservation->salle->nom,
                    'id' => $conversation->reservation->salle->id,
                ],
                'reservation_status' => $conversation->reservation->statut,
                'created_at' => $conversation->created_at->toISOString(),
            ],
            'messages' => $messages
        ]);
    }

    /**
     * Envoyer un message dans une conversation
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        // Vérifier que la conversation appartient au promoteur
        $this->authorizeConversation($conversation);
        
        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $message = ConversationMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'content' => $validated['content'],
            'is_read' => false,
        ]);

        // Mettre à jour le timestamp du dernier message
        $conversation->updateLastMessageAt();

        // Notifier le client
        \App\Models\Notification::createForUser(
            $conversation->user_id,
            'Nouveau message',
            "Vous avez reçu un nouveau message concernant votre réservation pour '{$conversation->reservation->salle->nom}'",
            'info',
            'conversation',
            $conversation->id
        );

        return response()->json([
            'message' => [
                'id' => $message->id,
                'content' => $message->content,
                'sender_id' => $message->sender_id,
                'sender_name' => Auth::user()->name,
                'is_from_me' => true,
                'is_read' => $message->is_read,
                'created_at' => $message->created_at->toISOString(),
            ]
        ]);
    }

    /**
     * Obtenir les nouveaux messages (pour le polling)
     */
    public function getNewMessages(Request $request, Conversation $conversation)
    {
        // Vérifier que la conversation appartient au promoteur
        $this->authorizeConversation($conversation);
        
        $lastMessageId = $request->get('last_message_id', 0);
        
        $newMessages = ConversationMessage::where('conversation_id', $conversation->id)
            ->where('id', '>', $lastMessageId)
            ->where('sender_id', '!=', Auth::id())
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Marquer comme lus
        $newMessages->each(function ($message) {
            $message->markAsRead();
        });

        return response()->json([
            'messages' => $newMessages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->name,
                    'is_from_me' => false,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at->toISOString(),
                ];
            })
        ]);
    }

    /**
     * Vérifier que la conversation appartient au promoteur
     */
    private function authorizeConversation(Conversation $conversation)
    {
        if ($conversation->promoter_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à accéder à cette conversation');
        }
    }
}
