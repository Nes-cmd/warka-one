<?php

// v1 (Blade) auth routes have been fully retired in favor of the React/Inertia routes in
// routes/web.php. The controllers that used to back them (ConfirmablePasswordController,
// PasswordController, EmailVerificationPromptController, EmailVerificationNotificationController,
// VerifyEmailController — Laravel's stock scaffolding, never wired to MustVerifyEmail on the
// User model) have been deleted; this app verifies email/phone through its own OTP system instead.
