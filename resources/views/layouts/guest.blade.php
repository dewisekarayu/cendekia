<body class="font-sans antialiased">

<div
class="min-h-screen flex items-center justify-center relative overflow-hidden"
style="
background:
radial-gradient(circle at 10% 20%, rgb(235, 244, 255), transparent 32%),
radial-gradient(circle at 90% 80%, rgb(219, 234, 254), transparent 35%),
linear-gradient(135deg,#F8FBFF 0%,#F2F7FF 35%,#EDF4FF 70%,#F9FBFF 100%);
">

    <div class="absolute -top-44 -left-44 w-[450px] h-[450px] rounded-full bg-blue-500/20 blur-[130px]"></div>

    <div class="absolute -bottom-44 -right-44 w-[420px] h-[420px] rounded-full bg-indigo-600/20 blur-[130px]"></div>

    <div class="relative z-10 w-full max-w-md px-6">

        {{ $slot }}



    </div>

</div>

</body>