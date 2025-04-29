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
