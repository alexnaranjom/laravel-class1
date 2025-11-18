<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ContactWebController extends Controller
{
    public function index()
    {
        // Render the Vue Contacts page through Inertia.
        return Inertia::render('Contacts/Index');
    }
}
