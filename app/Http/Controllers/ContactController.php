<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ContactStoreRequest;
use App\Http\Requests\ContactUpdateRequest;

class ContactController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->contactQuery($request->query('search'))
                ->orderByDesc('created_at')
                ->paginate(self::PER_PAGE)
        );
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
        $this->authorize('delete', $contact);

        $contact->delete();
        return response()->json(null, 204);
    }

    private function contactQuery(?string $search): Builder
    {
        return Contact::query()
            ->when($search, function (Builder $builder, string $term): void {
                $builder->where(function (Builder $inner) use ($term): void {
                    $inner->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%");
                });
            });
    }
}
