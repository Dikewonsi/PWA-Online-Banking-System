<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Email</title>
    <style>
        /* This is optional - include Tailwind CSS CDN or build it into your project */
        @import url('https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
    </style>
</head>
<body class="bg-gray-100 p-4">

<div class="max-w-md mx-auto bg-white p-6 rounded shadow-lg">
    <!-- Logo and Header -->
    <div class="text-center mb-6" >
        <img  src="https://capitalvista.net/app/assets/images/logo/logo.png" alt="CapitalVista Logo" class="mx-auto h-12">
        <h2 class="text-2xl text-gray-800 mt-2">Your OTP for PIN Change</h2>
    </div>

    <!-- OTP Section -->
    <div class="text-center">
        <p class="text-lg text-gray-600">Hello,</p>
        <p class="text-lg text-gray-600">Your One-Time Password (OTP) for CapitalVista is:</p>
        <h2 class="text-4xl text-purple-600 font-bold my-4"><?php echo $otp; ?></h2>
        <p class="text-base text-gray-600">This OTP is valid for a limited time. Do not share it with anyone for security reasons.</p>
        <p class="text-base text-gray-600 mt-4">If you didn't request this OTP, please ignore this email.</p>
        <p class="text-base text-gray-600 mt-4">Best regards,<br>CapitalVista Team</p>
    </div>
</div>

</body>
</html>
