import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { route } from 'ziggy-js';
import HomeLayout from '../Layouts/HomeLayout';

export default function Documentation() {
    return (
        <HomeLayout title="Developer Documentation">
            <main className="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 relative">
                {/* Decorative gradient elements */}
                <div className="absolute top-20 left-10 w-32 md:w-52 h-[350px] rotate-45 bg-primary-500/10 dark:bg-primary/40 -z-10 blur-3xl rounded-full"></div>
                <div className="absolute bottom-20 right-10 w-32 md:w-52 h-[300px] rotate-45 bg-secondary-500/10 dark:bg-secondary-400/20 -z-10 blur-3xl rounded-full"></div>

                <h1 className="text-slate-900 font-extrabold text-4xl sm:text-5xl lg:text-6xl tracking-tight text-center dark:text-white mb-10">
                    Developer Documentation
                </h1>

                <div className="flex flex-col lg:flex-row gap-8">
                    {/* Documentation Sidebar */}
                    <div className="lg:w-1/4 mb-8 lg:mb-0">
                        <div className="lg:sticky lg:top-10 bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                            <h2 className="text-xl font-bold text-slate-900 dark:text-white mb-6">Contents</h2>
                            <nav className="space-y-1">
                                <a href="#features" className="block py-2 px-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/30 rounded-md">Key Features</a>
                                <a href="#getting-started" className="block py-2 px-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/30 rounded-md">Getting Started</a>
                                <a href="#integration-guide" className="block py-2 px-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/30 rounded-md">Integration Guide</a>
                                <a href="#auth-flow" className="block py-2 px-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/30 rounded-md">Authentication Flow</a>
                                <a href="#troubleshooting" className="block py-2 px-3 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/30 rounded-md">Troubleshooting</a>
                            </nav>
                        </div>
                    </div>

                    {/* Documentation Content */}
                    <div className="lg:w-3/4">
                        <div className="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-8">
                            {/* Key Features Section */}
                            <section id="features" className="mb-16">
                                <div className="flex items-center mb-6">
                                    <div className="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                        </svg>
                                    </div>
                                    <h2 className="text-3xl font-bold text-slate-900 dark:text-white">Key Features</h2>
                                </div>

                                <ul className="list-disc pl-8 text-lg text-slate-600 dark:text-slate-400 space-y-2">
                                    <li><strong>Single Sign-On</strong>: Allow users to access multiple applications with a single set of credentials</li>
                                    <li><strong>Multiple Authentication Methods</strong>: Email, phone, and passwordless login options</li>
                                    <li><strong>Free SMS OTP Service</strong>: No need to purchase separate SMS services for OTP delivery</li>
                                    <li><strong>Developer-Friendly</strong>: Easy integration with comprehensive SDKs and documentation</li>
                                    <li><strong>OAuth 2.0 & OpenID Connect</strong>: Industry-standard authentication protocols</li>
                                    <li><strong>Secure & Scalable</strong>: Enterprise-grade security with 99.9% uptime</li>
                                </ul>
                            </section>

                            {/* Getting Started Section */}
                            <section id="getting-started" className="mb-16">
                                <div className="flex items-center mb-6">
                                    <div className="w-12 h-12 rounded-full bg-secondary-100 dark:bg-secondary-900/30 flex items-center justify-center text-secondary-600 dark:text-secondary-400 mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <h2 className="text-3xl font-bold text-slate-900 dark:text-white">Getting Started</h2>
                                </div>

                                <div className="space-y-8">
                                    {/* Create an Account */}
                                    <div>
                                        <h3 className="text-2xl font-semibold text-slate-900 dark:text-white mb-4">1. Create an Account</h3>
                                        <ol className="list-decimal pl-8 text-lg text-slate-600 dark:text-slate-400 space-y-2">
                                            <li>Visit <Link href="/login" className="text-primary-600 dark:text-primary-400 hover:underline">Kerone</Link> and click on "Register"</li>
                                            <li>Complete the registration form with your details</li>
                                            <li>Verify your email or phone number</li>
                                            <li>Log in to your account</li>
                                        </ol>

                                        <div className="mt-4">
                                            <Link href="/authflow/get-otp?for=register" className="inline-flex items-center px-4 py-2 bg-primary-600 dark:bg-primary-700 text-white rounded-lg hover:bg-primary-700 dark:hover:bg-primary-800 transition">
                                                Create Your Account
                                            </Link>
                                        </div>
                                    </div>

                                    {/* Register Your Application */}
                                    <div>
                                        <h3 className="text-2xl font-semibold text-slate-900 dark:text-white mb-4">2. Register Your Application</h3>
                                        <p className="text-lg text-slate-600 dark:text-slate-400 mb-4">
                                            Once you have created an account, you need to register your application to get the necessary credentials for integration:
                                        </p>
                                        <ol className="list-decimal pl-8 text-lg text-slate-600 dark:text-slate-400 space-y-2">
                                            <li>Navigate to "My Applications" in your dashboard</li>
                                            <li>Click "Create New Application"</li>
                                            <li>Fill in the application details (name, redirect URL, etc.)</li>
                                            <li>Choose authentication methods (email, phone, passwordless)</li>
                                            <li>Save your client_id and client_secret securely</li>
                                        </ol>

                                        <div className="mt-4">
                                            <Link href="/oauth/clients" className="inline-flex items-center px-4 py-2 border border-primary-600 dark:border-primary-400 text-primary-600 dark:text-primary-400 bg-transparent rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20 transition">
                                                Manage Your Applications
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            {/* Integration Guide Section */}
                            <section id="integration-guide" className="mb-16">
                                <div className="flex items-center mb-6">
                                    <div className="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                        </svg>
                                    </div>
                                    <h2 className="text-3xl font-bold text-slate-900 dark:text-white">Integration Guide</h2>
                                </div>

                                <div className="space-y-8">
                                    {/* Prerequisites */}
                                    <div>
                                        <h3 className="text-2xl font-semibold text-slate-900 dark:text-white mb-4">Prerequisites</h3>
                                        <ul className="list-disc pl-8 text-lg text-slate-600 dark:text-slate-400 space-y-2">
                                            <li>A registered Kerone account</li>
                                            <li>An application registered on the Kerone platform</li>
                                            <li>Your client_id and client_secret</li>
                                            <li>A web application capable of making HTTP requests</li>
                                        </ul>
                                    </div>

                                    {/* Step 1: Set Up Your Routes */}
                                    <div>
                                        <h3 className="text-2xl font-semibold text-slate-900 dark:text-white mb-4">Step 1: Set Up Your Routes</h3>
                                        <p className="text-lg text-slate-600 dark:text-slate-400 mb-4">
                                            Create the necessary routes in your application to handle the OAuth flow:
                                        </p>

                                        <div className="bg-slate-800 rounded-lg p-4 overflow-x-auto dark:bg-slate-700">
                                            <pre className="text-green-500"><code>{`// Example routes setup in Laravel
use App\\Http\\Controllers\\AuthController;

Route::get('/login', [AuthController::class, 'redirectToKerone'])->name('login');
Route::get('/callback', [AuthController::class, 'handleKeroneCallback'])->name('auth.callback');`}</code></pre>
                                        </div>
                                    </div>

                                    {/* Step 2: Implement the Authorization Flow */}
                                    <div>
                                        <h3 className="text-2xl font-semibold text-slate-900 dark:text-white mb-4">Step 2: Implement the Authorization Flow</h3>
                                        <p className="text-lg text-slate-600 dark:text-slate-400 mb-4">
                                            Create a controller method to redirect users to the Kerone authorization page:
                                        </p>

                                        <div className="bg-slate-800 rounded-lg p-4 overflow-x-auto dark:bg-slate-700">
                                            <pre className="text-green-500"><code>{`/**
 * Redirect the user to the Kerone authorization page.
 */
public function redirectToKerone()
{
    $query = http_build_query([
        'client_id' => env('KERONE_CLIENT_ID'),
        'redirect_uri' => route('auth.callback'),
        'response_type' => 'code',
        'scope' => 'profile email phone',
    ]);

    return redirect('https://kerone.kertech.co/oauth/authorize?' . $query);
}`}</code></pre>
                                        </div>
                                    </div>

                                    {/* Step 3: Handle the Callback */}
                                    <div>
                                        <h3 className="text-2xl font-semibold text-slate-900 dark:text-white mb-4">Step 3: Handle the Callback</h3>
                                        <p className="text-lg text-slate-600 dark:text-slate-400 mb-4">
                                            Create a method to handle the callback from Kerone and exchange the authorization code for an access token:
                                        </p>

                                        <div className="bg-slate-800 rounded-lg p-4 overflow-x-auto dark:bg-slate-700">
                                            <pre className="text-green-500"><code>{`/**
 * Handle the callback from Kerone.
 */
public function handleKeroneCallback(Request $request)
{
    // Check for errors
    if ($request->has('error')) {
        return redirect('/login')
            ->withErrors('Error from Kerone: ' . $request->error);
    }

    // Get the authorization code
    $code = $request->code;
    
    if ($code) {
        // Exchange the authorization code for an access token
        $response = Http::post('https://kerone.kertech.co/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => env('KERONE_CLIENT_ID'),
            'client_secret' => env('KERONE_CLIENT_SECRET'),
            'redirect_uri' => route('auth.callback'),
            'code' => $code,
        ]);
        
        if ($response->successful()) {
            $accessToken = $response->json()['access_token'];
            
            // Get the user data
            $userResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken
            ])->get('https://kerone.kertech.co/api/user');
            
            if ($userResponse->successful()) {
                $userData = $userResponse->json();
                
                // Login or create a user in your system based on the user data
                // ...
                
                // Redirect to your application's dashboard
                return redirect('/dashboard');
            }
        }
    }
    
    // Handle error cases
    return redirect('/login')->withErrors('Authentication failed');
}`}</code></pre>
                                        </div>
                                    </div>

                                    {/* Step 4: Using the User Data */}
                                    <div>
                                        <h3 className="text-2xl font-semibold text-slate-900 dark:text-white mb-4">Step 4: Using the User Data</h3>
                                        <p className="text-lg text-slate-600 dark:text-slate-400 mb-4">
                                            After successful authentication, you can use the returned data to:
                                        </p>
                                        <ol className="list-decimal pl-8 text-lg text-slate-600 dark:text-slate-400 space-y-2">
                                            <li>Create a new user account if it doesn't exist</li>
                                            <li>Log in the existing user</li>
                                            <li>Redirect the user to the intended application</li>
                                        </ol>
                                    </div>
                                </div>
                            </section>

                            {/* Authentication Flow Section */}
                            <section id="auth-flow" className="mb-16">
                                <div className="flex items-center mb-6">
                                    <div className="w-12 h-12 rounded-full bg-secondary-100 dark:bg-secondary-900/30 flex items-center justify-center text-secondary-600 dark:text-secondary-400 mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <h2 className="text-3xl font-bold text-slate-900 dark:text-white">Authentication Flow</h2>
                                </div>

                                <div className="flex justify-center mb-8">
                                    <div className="bg-slate-100 dark:bg-slate-700/30 rounded-lg p-6 max-w-2xl">
                                        <ol className="relative border-l border-primary-200 dark:border-primary-700">
                                            <li className="mb-6 ml-6">
                                                <span className="absolute flex items-center justify-center w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-full -left-4 ring-4 ring-white dark:ring-slate-800 text-primary-600 dark:text-primary-400">1</span>
                                                <h3 className="flex items-center mb-1 text-lg font-semibold text-slate-900 dark:text-white">User Initiates Login</h3>
                                                <p className="text-base text-slate-600 dark:text-slate-400">The user clicks on a login button in your application</p>
                                            </li>
                                            <li className="mb-6 ml-6">
                                                <span className="absolute flex items-center justify-center w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-full -left-4 ring-4 ring-white dark:ring-slate-800 text-primary-600 dark:text-primary-400">2</span>
                                                <h3 className="flex items-center mb-1 text-lg font-semibold text-slate-900 dark:text-white">Redirect to Authorization</h3>
                                                <p className="text-base text-slate-600 dark:text-slate-400">Your application redirects the user to Kerone's authorization endpoint</p>
                                            </li>
                                            <li className="mb-6 ml-6">
                                                <span className="absolute flex items-center justify-center w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-full -left-4 ring-4 ring-white dark:ring-slate-800 text-primary-600 dark:text-primary-400">3</span>
                                                <h3 className="flex items-center mb-1 text-lg font-semibold text-slate-900 dark:text-white">User Authentication</h3>
                                                <p className="text-base text-slate-600 dark:text-slate-400">User logs in with their Kerone credentials (email, phone, or passwordless)</p>
                                            </li>
                                            <li className="mb-6 ml-6">
                                                <span className="absolute flex items-center justify-center w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-full -left-4 ring-4 ring-white dark:ring-slate-800 text-primary-600 dark:text-primary-400">4</span>
                                                <h3 className="flex items-center mb-1 text-lg font-semibold text-slate-900 dark:text-white">Authorization Code</h3>
                                                <p className="text-base text-slate-600 dark:text-slate-400">Kerone redirects back to your callback URL with an authorization code</p>
                                            </li>
                                            <li className="mb-6 ml-6">
                                                <span className="absolute flex items-center justify-center w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-full -left-4 ring-4 ring-white dark:ring-slate-800 text-primary-600 dark:text-primary-400">5</span>
                                                <h3 className="flex items-center mb-1 text-lg font-semibold text-slate-900 dark:text-white">Token Exchange</h3>
                                                <p className="text-base text-slate-600 dark:text-slate-400">Your server exchanges the authorization code for an access token</p>
                                            </li>
                                            <li className="ml-6">
                                                <span className="absolute flex items-center justify-center w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-full -left-4 ring-4 ring-white dark:ring-slate-800 text-primary-600 dark:text-primary-400">6</span>
                                                <h3 className="flex items-center mb-1 text-lg font-semibold text-slate-900 dark:text-white">Authentication Complete</h3>
                                                <p className="text-base text-slate-600 dark:text-slate-400">User is authenticated in your application and redirected as needed</p>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </section>

                            {/* Troubleshooting Section */}
                            <section id="troubleshooting">
                                <div className="flex items-center mb-6">
                                    <div className="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400 mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <h2 className="text-3xl font-bold text-slate-900 dark:text-white">Troubleshooting</h2>
                                </div>

                                <div className="space-y-6">
                                    <div className="bg-slate-100 dark:bg-slate-700/30 rounded-lg p-6">
                                        <h3 className="text-xl font-semibold text-slate-900 dark:text-white mb-3">Invalid Client Credentials</h3>
                                        <p className="text-lg text-slate-600 dark:text-slate-400 mb-2"><strong>Problem:</strong> Authentication fails with "invalid_client" error.</p>
                                        <p className="text-lg text-slate-600 dark:text-slate-400 mb-2"><strong>Solution:</strong> Double-check your client_id and client_secret. Ensure they are correctly configured in your environment variables and that you're using the correct values from your Kerone dashboard.</p>
                                    </div>

                                    <div className="bg-slate-100 dark:bg-slate-700/30 rounded-lg p-6">
                                        <h3 className="text-xl font-semibold text-slate-900 dark:text-white mb-3">Redirect URI Mismatch</h3>
                                        <p className="text-lg text-slate-600 dark:text-slate-400 mb-2"><strong>Problem:</strong> Authentication fails with "redirect_uri_mismatch" error.</p>
                                        <p className="text-lg text-slate-600 dark:text-slate-400 mb-2"><strong>Solution:</strong> The redirect URI in your authorization request must exactly match one of the URIs you've registered for your application in the Kerone dashboard. Check for any discrepancies, including HTTP vs HTTPS and trailing slashes.</p>
                                    </div>

                                    <div className="bg-slate-100 dark:bg-slate-700/30 rounded-lg p-6">
                                        <h3 className="text-xl font-semibold text-slate-900 dark:text-white mb-3">Invalid or Expired Authorization Code</h3>
                                        <p className="text-lg text-slate-600 dark:text-slate-400 mb-2"><strong>Problem:</strong> Token exchange fails with "invalid_grant" error.</p>
                                        <p className="text-lg text-slate-600 dark:text-slate-400 mb-2"><strong>Solution:</strong> Authorization codes expire quickly (typically within minutes). Ensure you're exchanging the code for an access token promptly after receiving it. Also, each code can only be used once.</p>
                                    </div>
                                </div>

                                <div className="mt-8 flex justify-center">
                                    <Link href={route('v2.contact')} className="inline-flex items-center px-6 py-3 bg-primary-600 dark:bg-primary-700 text-white font-medium rounded-lg hover:bg-primary-700 dark:hover:bg-primary-800 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Get Technical Support
                                    </Link>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </main>
        </HomeLayout>
    );
}

