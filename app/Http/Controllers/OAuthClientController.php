<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Passport\Client;
use Illuminate\Support\Str;

class OAuthClientController extends Controller
{
    /**
     * Display a listing of the user's OAuth clients.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $clients = $request->user()->clients;
        
        return view('oauth.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('oauth.clients.create');
    }

    /**
     * Store a newly created client in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'redirect' => 'required|url',
            'description' => 'nullable|string|max:500',
            'use_auth_types' => 'required|array',
            'pass_type' => 'required|in:password,otp',
            'registration_enabled' => 'boolean',
        ]);

        $useAuthTypes = $request->use_auth_types;
        
        $client = $request->user()->clients()->create([
            'name' => $request->name,
            'secret' => Str::random(40),
            'redirect' => $request->redirect,
            'personal_access_client' => false,
            'password_client' => true,
            'revoked' => false,
            'description' => $request->description,
            'use_auth_types' => $useAuthTypes,
            'pass_type' => $request->pass_type,
            'registration_enabled' => $request->registration_enabled ?? false,
        ]);

        return redirect()->route('clients.index')->with('success', 'Application created successfully!');
    }

    /**
     * Show the form for editing the specified client.
     *
     * @param  int  $clientId
     * @return \Illuminate\View\View
     */
    public function edit($clientId)
    {
        $client = Client::findOrFail($clientId);
        
        $this->authorize('update', $client);
        
        return view('oauth.clients.edit', compact('client'));
    }

    /**
     * Update the specified client in storage.
     *
     * @param  Request  $request
     * @param  int  $clientId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $clientId)
    {
        // dd($request->all());
        $client = Client::findOrFail($clientId);
        
        $this->authorize('update', $client);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'redirect' => 'required|url',
            'description' => 'nullable|string|max:500',
            'use_auth_types' => 'required|array',
            'pass_type' => 'required|in:password,otp',
            'registration_enabled' => 'boolean',
        ]);

        $useAuthTypes = $request->use_auth_types;
        
        $client->update([
            'name' => $request->name,
            'redirect' => $request->redirect,
            'description' => $request->description,
            'use_auth_types' => $useAuthTypes,
            'pass_type' => $request->pass_type,
            'registration_enabled' => $request->registration_enabled ?? false,
        ]);

        return redirect()->route('clients.index')->with('success', 'Application updated successfully!');
    }

    /**
     * Remove the specified client from storage.
     *
     * @param  int  $clientId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($clientId)
    {
        $client = Client::findOrFail($clientId);
        
        $this->authorize('delete', $client);
        
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Application deleted successfully!');
    }

    /**
     * Regenerate the client secret.
     *
     * @param  int  $clientId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function regenerateSecret($clientId)
    {
        $client = Client::findOrFail($clientId);
        
        $this->authorize('update', $client);
        
        $client->update([
            'secret' => Str::random(40),
        ]);

        return redirect()->route('clients.edit', $client->id)
            ->with('success', 'Client secret has been regenerated successfully. Make sure to update your applications.');
    }

    public function show($clientId)
    {
        return redirect()->route('clients.index');
    }
} 
