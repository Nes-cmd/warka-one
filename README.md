# Kerone - Single Sign-On Platform

A modern authentication service that provides seamless Single Sign-On (SSO) capabilities for your applications. Kerone eliminates the need to implement multiple authentication methods by offering a unified login system with email, phone, and passwordless options.

## Table of Contents

- [Kerone - Single Sign-On Platform](#kerone---single-sign-on-platform)
  - [Table of Contents](#table-of-contents)
  - [Key Features](#key-features)
  - [Getting Started](#getting-started)
    - [1. Create an Account](#1-create-an-account)
    - [2. Register Your Application](#2-register-your-application)
  - [Integration Guide](#integration-guide)
    - [Prerequisites](#prerequisites)
    - [Step 1: Set Up Your Routes](#step-1-set-up-your-routes)
    - [Step 2: Implement the Authorization Flow](#step-2-implement-the-authorization-flow)
    - [Step 3: Handle the Callback](#step-3-handle-the-callback)
    - [Step 4: Using the User Data](#step-4-using-the-user-data)
  - [Authentication Flow](#authentication-flow)
  - [Troubleshooting](#troubleshooting)

## Key Features

- **Single Sign-On**: Allow users to access multiple applications with a single set of credentials
- **Multiple Authentication Methods**: Email, phone, and passwordless login options
- **Free SMS OTP Service**: No need to purchase separate SMS services for OTP delivery
- **Developer-Friendly**: Easy integration with comprehensive SDKs and documentation
- **OAuth 2.0 & OpenID Connect**: Industry-standard authentication protocols
- **Secure & Scalable**: Enterprise-grade security with 99.9% uptime

## Getting Started

### 1. Create an Account

1. Visit [Kerone](https://kerone.kertech.co/login) and click on "Register"
2. Complete the registration form with your details
3. Verify your email or phone number
4. Log in to your account

### 2. Register Your Application

1. Navigate to "My Applications" in your account dashboard
2. Click on "Create New Application"
3. Fill in the application details:
   - **Name**: Your application's name
   - **Description**: Brief description of your application
   - **Redirect URLs**: The URLs where users will be redirected after authentication
   - **Application Type**: Web Application, Mobile App, or Single Page App
4. Choose the authentication methods your application will use:
   - Email
   - Phone
   - Passwordless
   - Social login providers (if applicable)
5. Save your application to receive your `client_id` and `client_secret`

> **Important**: Keep your `client_secret` secure and never expose it in client-side code.

## Integration Guide

### Prerequisites

- A registered application with Kerone
- Your `client_id` and `client_secret`
- Configured redirect URLs

### Step 1: Set Up Your Routes

Add two main routes to your application:

1. **Authorization Initiation Route**: This route will redirect users to Kerone for authentication
2. **Callback Route**: The redirect URL where Kerone will send the user after authentication

### Step 2: Implement the Authorization Flow

When a user needs to log in, redirect them to the Kerone authorization endpoint. Here's an example implementation in Laravel:

```php
// Example implementation in Laravel
$query = http_build_query([
    'client_id' => env('KERONE_CLIENT_ID'),
    'redirect_uri' => url('/') . "/auth/callback",
    'response_type' => 'code',
    'scope' => '',
]);

return redirect(env('KERONE_AUTH_URL') . '/oauth/authorize?' . $query);
```

> **Important**: The `redirect_uri` in your code MUST exactly match the redirect URL you registered in the Kerone dashboard. Even slight differences (like trailing slashes or http vs https) will cause authentication to fail.

### Step 3: Handle the Callback

After the user authenticates with Kerone, they will be redirected back to your callback URL with an authorization code. You'll need to exchange this code for an access token:

```php
// Example implementation of callback in Laravel
public function callback(Request $request)
{
    if (isset($request->code) && $request->code) {
        $response = Http::post(env('KERONE_AUTH_URL') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => env('KERONE_CLIENT_ID'),
            'client_secret' => env('KERONE_CLIENT_SECRET'),
            'redirect_uri' => url('/') . "/auth/callback",
            'code' => $request->code,
        ]);
        $response = $response->json();

        // check if the response includes access_token 
        if (isset($response['access_token']) && $response['access_token']) {
            // Store the access token in session for future API requests
            $access_token = $response['access_token'];
            
            // Use the access token to get user information
            $userResponse = Http::withToken($access_token)->get(env('KERONE_AUTH_URL') . '/api/user');
            
            if ($userResponse->successful()) {
                $userData = $userResponse->json();
                
                // Login or create a user in your system based on the user data
                // ...
                
                // Redirect to your application's dashboard
                return redirect('/dashboard');
            }
        }
        
        // Handle error cases
        return redirect('/login')->withErrors('Authentication failed');
    }
}
```

### Step 4: Using the User Data

After successful authentication, you can use the returned data to:  

1. Create a new user account if it doesn't exist
2. Log in the existing user
3. Redirect the user to the intended application

## Authentication Flow

1. User initiates login
2. Kerone redirects to authorization endpoint
3. User authenticates
4. Kerone redirects to callback route with an authorization code
5. Callback route handles the response
6. User is redirected to intended application

## Troubleshooting

If you encounter issues, please check the following:

1. Ensure your `client_id` and `client_secret` are correct
2. Verify your redirect URLs are configured correctly
