<?php

namespace App\Services;

use App\Events\ContactRegistered;
use App\Mail\AnswerContact;
use App\Models\Contact;
use App\Traits\SendMail;
use Illuminate\Database\Eloquent\Collection;

class ContactService extends BaseService
{
    use SendMail;

    private $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function index(): Collection
    {
        return $this->contact->orderBy('created_at', 'desc')->get();
    }

    public function findById($request): Contact
    {
        return $this->contact->findOrFail($request->input('id'));
    }

    public function store($request): Contact
    {
        $this->validateFields($request->all());
        $contact = $this->contact->create([
            'con_name' => $request->input('name'),
            'con_email' => $request->input('email'),
            'con_subject' => $request->input('subject'),
            'con_message' => $request->input('message'),
        ]);
        event(new ContactRegistered($request->input('subject')));
        return $contact;
    }

    public function destroy($request): bool
    {
        $contact = $this->findById($request);
        return $contact->delete();
    }

    public function answer($request): void
    {
        $contact = $this->findById($request);
        $contact->con_answer = $request->input('answer');
        $contact->save();

        $this->sendMail($contact->con_email, new AnswerContact([
            'title' => 'Contato Império do MDF',
            'body' => $request->input('answer'),
            'name' => $contact->con_name,
        ]));
    }

    private function validateFields($request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:255',
        ];
        $titles = [
            'name' => 'Nome',
            'email' => 'E-mail',
            'subject' => 'Assunto',
            'message' => 'Mensagem',
        ];
        $this->_validate($request, $rules, $titles);
    }
}
