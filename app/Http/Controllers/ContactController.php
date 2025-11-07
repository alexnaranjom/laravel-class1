<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ContactStoreRequest;
use App\Http\Requests\ContactUpdateRequest;

class ContactController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Contact::latest()->get());
    }

    public function store(ContactStoreRequest $request): JsonResponse
    {
        $contact = Contact::create($request->validated());
        return response()->json($contact, 201);
    }

    public function show(Contact $contact): JsonResponse
    {
        return response()->json($contact);
    }

    public function update(ContactUpdateRequest $request, Contact $contact): JsonResponse
    {
        $contact->update($request->validated());
        return response()->json($contact);
    }

    public function destroy(Contact $contact): JsonResponse
    {
        $contact->delete();
        return response()->json(null, 204);
    }
}
