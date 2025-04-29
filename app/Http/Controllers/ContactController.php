<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class ContactController extends Controller
{
    /**
     * Display the contact form
     */
    public function index()
    {
        // Generate simple math CAPTCHA
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        
        Session::put('captcha_num1', $num1);
        Session::put('captcha_num2', $num2);
        
        return view('contact', [
            'captcha_num1' => $num1,
            'captcha_num2' => $num2
        ]);
    }

    /**
     * Process the contact form submission
     */
    public function submit(Request $request)
    {
        // Get CAPTCHA values from session
        $captcha_num1 = Session::get('captcha_num1');
        $captcha_num2 = Session::get('captcha_num2');
        $captcha_result = $captcha_num1 + $captcha_num2;
        // dump($captcha_result);
        // dd(session()->all());
        
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'contact' => 'required|string|max:255',
            'title' => 'required|string|max:100',
            'message' => 'required|string|min:10',
            'captcha' => 'required|numeric|in:' . $captcha_result,
        ], [
            'contact.required' => 'Please provide your email or phone number.',
            'title.required' => 'Please provide a title for your request.',
            'message.required' => 'Please provide details about your request.',
            'message.min' => 'Your message should be at least 10 characters long.',
            'captcha.required' => 'Please solve the math problem.',
            'captcha.in' => 'The answer to the math problem is incorrect. Please try again.',
        ]);

        if ($validator->fails()) {
            // Generate new CAPTCHA for failed attempts
            $num1 = rand(1, 10);
            $num2 = rand(1, 10);
            
            Session::put('captcha_num1', $num1);
            Session::put('captcha_num2', $num2);
            
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('captcha_num1', $num1)
                ->with('captcha_num2', $num2);
        }

        // Process the contact form (either store in DB, send email, etc.)
        try {
            // Store in database
            \App\Models\ContactRequest::create([
                'contact' => $request->contact,
                'title' => $request->title,
                'message' => $request->message,
                'status' => 'new',
            ]);

            // Return with success message
            return back()->with('success', 'Thank you for your message. We will get back to you soon!');
        } catch (\Exception $e) {
            // Log the error
            info('Contact form error: ' . $e->getMessage());
            
            // Return with error message
            return back()
                ->with('error', 'Sorry, there was a problem processing your request. Please try again later.')
                ->withInput();
        }
    }
} 