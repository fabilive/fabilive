<?php

// Fabilive Custom Referral Feature Auto-Patcher
// Run this file via terminal: php deploy-referral.php

$base = __DIR__;

function applyPatch($file, $search, $replace, $successMsg) {
    if (!file_exists($file)) {
        echo "[ERROR] File not found: $file\n";
        return;
    }
    $content = file_get_contents($file);
    if (strpos($content, $replace) !== false) {
        echo "[SKIP] Already applied to: " . basename($file) . "\n";
        return;
    }
    if (strpos($content, $search) === false) {
        echo "[ERROR] Search string not found in: " . basename($file) . "\n";
        return;
    }
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
    echo "[SUCCESS] $successMsg\n";
}

// 1. FrontendController.php
applyPatch(
    $base . '/app/Http/Controllers/Front/FrontendController.php',
    "Session::put('affilate', \$affilate_user->id);\n                    return redirect()->route('user.register');",
    "Session::put('affilate', \$affilate_user->id);\n                    // Pass the raw code to the session so the register view can auto-fill it\n                    Session::put('affilate_code', \$affilate_user->affilate_code);\n                    return redirect()->route('user.register');",
    "FrontendController updated to save 'affilate_code' into session."
);

// 2. register.blade.php
applyPatch(
    $base . '/resources/views/frontend/register.blade.php',
    "value=\"{{ Session::has('custom_referral_name') ? Session::get('custom_referral_name') : '' }}\"",
    "value=\"{{ Session::has('affilate_code') ? Session::get('affilate_code') : (Session::has('custom_referral_name') ? Session::get('custom_referral_name') : '') }}\"",
    "Registration view updated to auto-fill the custom code."
);

// 3. web.php
applyPatch(
    $base . '/routes/web.php',
    "Route::get('/affiliate/history', 'User\UserController@affilate_history')->name('user-affilate-history');",
    "Route::get('/affiliate/history', 'User\UserController@affilate_history')->name('user-affilate-history');\n        Route::post('/affiliate/program/update', 'User\UserController@updateAffilateCode')->name('user-affilate-update');",
    "web.php routing updated with the new POST endpoint."
);

// 4. UserController.php
applyPatch(
    $base . '/app/Http/Controllers/User/UserController.php',
    "        broadcast(new MessageSent(\$message))->toOthers();\n        return response()->json(['message' => \$message]);\n    }\n}",
    "        broadcast(new MessageSent(\$message))->toOthers();\n        return response()->json(['message' => \$message]);\n    }\n\n    public function updateAffilateCode(\Illuminate\Http\Request \$request)\n    {\n        \$user = \$this->user;\n        \$request->validate([\n            'affilate_code' => 'required|string|max:255|unique:users,affilate_code,' . \$user->id\n        ], [\n            'affilate_code.unique' => __('This Referral Code is already taken. Please choose another one.')\n        ]);\n\n        \$user->affilate_code = str_replace(' ', '-', trim(\$request->affilate_code));\n        \$user->save();\n\n        return back()->with('success', __('Your custom referral code has been saved successfully.'));\n    }\n}",
    "UserController updated to securely process custom code changes."
);

// 5. affilate-program.blade.php
$searchAffiliate = "                                                <form>\n                                                    @include('alerts.admin.form-both')\n                                                    <div class=\"row mb-4\">\n                                                        <div class=\"col-lg-4 text-right pt-2 f-14\">\n                                                            <label>{{ __('Your Affilate Link *') }}";
$replaceAffiliate = "                                                @include('alerts.admin.form-both')\n                                                \n                                                <form action=\"{{ route('user-affilate-update') }}\" method=\"POST\" class=\"mb-5 border-bottom pb-4\">\n                                                    @csrf\n                                                    <div class=\"row mb-4\">\n                                                        <div class=\"col-lg-4 text-right pt-2 f-14\">\n                                                            <label>{{ __('Customize Referral Code *') }}</label>\n                                                            <br>\n                                                            <small>{{ __('Create a readable code for your link.') }}</small>\n                                                        </div>\n                                                        <div class=\"col-lg-6 pt-2\">\n                                                            <input type=\"text\" class=\"input-field form-control border\" name=\"affilate_code\" value=\"{{ \$user->affilate_code }}\" required>\n                                                        </div>\n                                                        <div class=\"col-lg-2 pt-2\">\n                                                            <button type=\"submit\" class=\"btn btn-primary w-100\" style=\"height: 50px;\">{{ __('Save Code') }}</button>\n                                                        </div>\n                                                    </div>\n                                                </form>\n\n                                                <form>\n                                                        <div class=\"col-lg-4 text-right pt-2 f-14\">\n                                                            <label>{{ __('Your Affilate Link *') }}";

applyPatch(
    $base . '/resources/views/user/affilate/affilate-program.blade.php',
    $searchAffiliate,
    $replaceAffiliate,
    "Affiliate dashboard view updated with the Custom Code HTML Form."
);

echo "\n=============================================\n";
echo "CLEARING CACHES (to ensure new routes and views are loaded)...\n";
exec('php artisan optimize:clear');
exec('php artisan view:clear');
exec('php artisan route:clear');
echo "SUCCESS! Fabilive Custom Referral Feature is deployed!\n";
echo "=============================================\n";
