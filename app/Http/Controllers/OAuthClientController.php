<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Passport\Client;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Passport\ClientRepository;

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
     * @param  ClientRepository  $clients
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, ClientRepository $clients)
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
        
        // Use Laravel Passport's ClientRepository to create the client properly
        // Parameters: userId, name, redirect, provider, personalAccessClient, passwordClient, confidential
        $client = $clients->create(
            $request->user()->id,
            $request->name,
            $request->redirect,
            null, // provider (null for default)
            false, // personal_access_client
            true,  // password_client
            true   // confidential (true for password clients - they need to securely store the secret)
        );

        // Update with custom fields
        $client->update([
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
     * @param  ClientRepository  $clients
     * @return \Illuminate\Http\RedirectResponse
     */
    public function regenerateSecret($clientId, ClientRepository $clients)
    {
        $client = Client::findOrFail($clientId);
        
        $this->authorize('update', $client);
        
        // Use Passport's ClientRepository to regenerate secret properly
        $clients->regenerateSecret($client);

        return redirect()->route('clients.edit', $client->id)
            ->with('success', 'Client secret has been regenerated successfully. Make sure to update your applications.');
    }

    public function show($clientId)
    {
        return redirect()->route('clients.index');
    }

    /**
     * Display a listing of the user's OAuth clients for React/Inertia.
     *
     * @param  Request  $request
     * @return \Inertia\Response
     */
    public function indexReact(Request $request)
    {
        $clients = $request->user()->clients->map(function ($client) {
            return [
                'id' => $client->id,
                'name' => $client->name,
                'secret' => $client->secret,
                'redirect' => $client->redirect,
                'description' => $client->description,
                'use_auth_types' => $client->use_auth_types,
                'pass_type' => $client->pass_type,
                'registration_enabled' => $client->registration_enabled,
                'created_at' => $client->created_at?->toDateTimeString(),
            ];
        });
        
        return Inertia::render('MyApps', [
            'clients' => $clients,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new client for React/Inertia.
     *
     * @return \Inertia\Response
     */
    public function createReact()
    {
        return Inertia::render('CreateApp');
    }

    /**
     * Store a newly created client in storage for React/Inertia.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeReact(Request $request, ClientRepository $clients)
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
        
        // Use Laravel Passport's ClientRepository to create the client properly
        // Parameters: userId, name, redirect, provider, personalAccessClient, passwordClient, confidential
        $client = $clients->create(
            $request->user()->id,
            $request->name,
            $request->redirect,
            null, // provider (null for default)
            false, // personal_access_client
            true,  // password_client
            true   // confidential (true for password clients - they need to securely store the secret)
        );

        // Update with custom fields
        $client->update([
            'description' => $request->description,
            'use_auth_types' => $useAuthTypes,
            'pass_type' => $request->pass_type,
            'registration_enabled' => $request->registration_enabled ?? false,
        ]);

        return redirect()->route('v2.clients.index')->with('success', 'Application created successfully!');
    }

    /**
     * Show the form for editing the specified client for React/Inertia.
     *
     * @param  int  $clientId
     * @return \Inertia\Response
     */
    public function editReact($clientId)
    {
        $client = Client::findOrFail($clientId);
        
        $this->authorize('update', $client);
        
        // Format auth types for frontend
        $authTypes = $client->use_auth_types;
        if (is_string($authTypes)) {
            try {
                $authTypes = json_decode($authTypes, true);
            } catch (\Exception $e) {
                $authTypes = [];
            }
        }
        if (!is_array($authTypes)) {
            $authTypes = [];
        }

        $clientData = [
            'id' => $client->id,
            'name' => $client->name,
            'secret' => $client->secret,
            'redirect' => $client->redirect,
            'description' => $client->description,
            'use_auth_types' => $authTypes,
            'pass_type' => $client->pass_type,
            'registration_enabled' => $client->registration_enabled,
        ];
        
        return Inertia::render('EditApp', [
            'client' => $clientData,
            'success' => session('success'),
        ]);
    }

    /**
     * Update the specified client in storage for React/Inertia.
     *
     * @param  Request  $request
     * @param  int  $clientId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateReact(Request $request, $clientId)
    {
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

        return redirect()->route('v2.clients.index')->with('success', 'Application updated successfully!');
    }

    /**
     * Remove the specified client from storage for React/Inertia.
     *
     * @param  int  $clientId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyReact($clientId)
    {
        $client = Client::findOrFail($clientId);
        
        $this->authorize('delete', $client);
        
        $client->delete();

        return redirect()->route('v2.clients.index')
            ->with('success', 'Application deleted successfully!');
    }

    /**
     * Regenerate the client secret for React/Inertia.
     *
     * @param  int  $clientId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function regenerateSecretReact($clientId, ClientRepository $clients)
    {
        $client = Client::findOrFail($clientId);
        
        $this->authorize('update', $client);
        
        // Use Passport's ClientRepository to regenerate secret properly
        $clients->regenerateSecret($client);

        return redirect()->route('v2.clients.edit', $client->id)
            ->with('success', 'Client secret has been regenerated successfully. Make sure to update your applications.');
    }
} 
