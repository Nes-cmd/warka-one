<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Authorization</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
        
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">
    <!-- Background decorative elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-secondary-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute top-40 left-40 w-80 h-80 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-full mb-4">
                    <i class="fas fa-shield-alt text-2xl text-primary-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Authorization Request</h1>
                <p class="text-gray-600">Review and approve access permissions</p>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <!-- App Info Section -->
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-robot text-white text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-xl font-semibold text-gray-900">{{ $client->name }}</h2>
                            <p class="text-sm text-gray-500">wants to access your account</p>
                        </div>
                    </div>
                </div>

                <!-- Permissions Section -->
                @if (count($scopes) > 0)
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-key text-primary-500 mr-2"></i>
                        This application will be able to:
                    </h3>
                    <div class="space-y-3">
                        @foreach ($scopes as $scope)
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-2 h-2 bg-primary-500 rounded-full mt-2 flex-shrink-0"></div>
                            <p class="text-gray-700 text-sm">{{ $scope->description }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="p-6 bg-gray-50">
                    <div class="flex space-x-3">
                        <!-- Authorize Button -->
                        <form method="post" action="{{ route('passport.authorizations.approve') }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="state" value="{{ $request->state }}">
                            <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                            <input type="hidden" name="auth_token" value="{{ $authToken }}">
                            <button type="submit" class="w-full bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 flex items-center justify-center space-x-2">
                                <i class="fas fa-check"></i>
                                <span>Authorize</span>
                            </button>
                        </form>

                        <!-- Cancel Button -->
                        <form method="post" action="{{ route('passport.authorizations.deny') }}" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="state" value="{{ $request->state }}">
                            <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                            <input type="hidden" name="auth_token" value="{{ $authToken }}">
                            <button class="w-full bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 px-6 rounded-xl border border-gray-300 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 flex items-center justify-center space-x-2">
                                <i class="fas fa-times"></i>
                                <span>Cancel</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-500">
                    <i class="fas fa-lock mr-1"></i>
                    Your data is protected and encrypted
                </p>
            </div>
        </div>
    </div>

    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</body>
</html>
