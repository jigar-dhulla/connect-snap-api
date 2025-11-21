<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ConnectSnap - Network Smarter at Events</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'tech-blue': '#005A9C',
                        'tech-blue-light': '#0073C4',
                        'tech-blue-dark': '#004578',
                    },
                    fontFamily: {
                        'montserrat': ['Montserrat', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white min-h-screen font-montserrat">
    <!-- Hero Section -->
    <header class="bg-tech-blue text-white">
        <div class="max-w-4xl mx-auto px-6 py-12 text-center">
            <div class="flex items-center justify-center gap-4 mb-6">
                <img src="/logo.jpeg" alt="ConnectSnap Logo" class="h-20 md:h-24 rounded-lg">
            </div>
            <p class="text-xl md:text-2xl opacity-90 mb-4">Network Smarter at Events</p>
            <p class="text-base opacity-80 max-w-2xl mx-auto">
                Scan QR codes, save connections, and never forget a conversation again.
            </p>
        </div>
    </header>

    <!-- Quick Start Guide -->
    <main class="max-w-4xl mx-auto px-6 py-12">
        <section class="mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Attendee Guide</h2>

            <!-- Step 1 -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-tech-blue/10 rounded-full flex items-center justify-center">
                        <span class="text-tech-blue font-bold text-xl">1</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Create Your Account</h3>
                        <p class="text-gray-600 mb-3">
                            Register with your email and password. Add your profile details:
                        </p>
                        <ul class="text-gray-600 space-y-1 ml-4">
                            <li>&#8226; Name (required)</li>
                            <li>&#8226; Company & Job Title</li>
                            <li>&#8226; Profile Photo (2MB max)</li>
                            <li>&#8226; Bio (up to 250 characters)</li>
                            <li>&#8226; Social URL (Twitter, Pinkary, etc.)</li>
                            <li>&#8226; Phone Number</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-tech-blue/10 rounded-full flex items-center justify-center">
                        <span class="text-tech-blue font-bold text-xl">2</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Get Your QR Code</h3>
                        <p class="text-gray-600 mb-3">
                            Your unique QR code is generated automatically when you register.
                            Find it on your Home screen, ready to be scanned by other attendees.
                        </p>
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <div class="inline-block bg-white p-4 rounded-lg border-2 border-dashed border-tech-blue/30">
                                <svg class="w-24 h-24 mx-auto text-tech-blue" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 3h6v6H3V3zm2 2v2h2V5H5zm8-2h6v6h-6V3zm2 2v2h2V5h-2zM3 13h6v6H3v-6zm2 2v2h2v-2H5zm13-2h1v1h-1v-1zm-3 0h1v1h-1v-1zm-2 0h1v1h-1v-1zm5 2h1v1h-1v-1zm-3 0h1v1h-1v-1zm-2 0h1v1h-1v-1zm5 2h1v1h-1v-1zm-3 0h1v1h-1v-1zm-2 0h1v1h-1v-1z"/>
                                </svg>
                                <p class="text-sm text-gray-500 mt-2">Your QR Code</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-3 text-center">
                            Format: <code class="bg-gray-100 px-2 py-1 rounded text-tech-blue">connectsnap://u/{your_hash}</code>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-tech-blue/10 rounded-full flex items-center justify-center">
                        <span class="text-tech-blue font-bold text-xl">3</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Scan & Connect</h3>
                        <p class="text-gray-600 mb-3">
                            Met someone interesting? Tap the <strong>Scan</strong> button, point your camera
                            at their QR code, and instantly view their profile. The connection is saved automatically!
                        </p>
                        <div class="flex flex-wrap items-center gap-3 text-gray-600">
                            <span class="bg-tech-blue/10 text-tech-blue px-3 py-1 rounded-full text-sm font-medium">
                                Real-time scanning
                            </span>
                            <span class="bg-tech-blue/10 text-tech-blue px-3 py-1 rounded-full text-sm font-medium">
                                Auto-save connections
                            </span>
                            <span class="bg-tech-blue/10 text-tech-blue px-3 py-1 rounded-full text-sm font-medium">
                                Haptic feedback
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-tech-blue/10 rounded-full flex items-center justify-center">
                        <span class="text-tech-blue font-bold text-xl">4</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Add Private Notes</h3>
                        <p class="text-gray-600 mb-3">
                            After scanning, add notes about your conversation (up to 500 characters).
                            These notes are <strong>completely private</strong> - only you can see them.
                        </p>
                        <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-lg">
                            <p class="text-amber-800 text-sm">
                                <strong>Tip:</strong> Jot down what you discussed, follow-up items,
                                or anything that will help you remember the conversation later.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-tech-blue/10 rounded-full flex items-center justify-center">
                        <span class="text-tech-blue font-bold text-xl">5</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Review Your Connections</h3>
                        <p class="text-gray-600 mb-3">
                            Open the <strong>Connections</strong> tab to see everyone you've met.
                            Search by name, view profiles, and edit your notes anytime.
                        </p>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <svg class="w-8 h-8 mx-auto text-tech-blue mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <p class="text-sm text-gray-600">Search contacts</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <svg class="w-8 h-8 mx-auto text-tech-blue mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <p class="text-sm text-gray-600">Edit notes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- App Navigation -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">App Navigation</h2>
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <div class="grid grid-cols-3 divide-x divide-gray-200">
                    <div class="p-6 text-center">
                        <div class="w-14 h-14 bg-tech-blue/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-tech-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800">Home</h3>
                        <p class="text-sm text-gray-500 mt-1">Your QR Code</p>
                    </div>
                    <div class="p-6 text-center">
                        <div class="w-14 h-14 bg-tech-blue/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-tech-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800">Scan</h3>
                        <p class="text-sm text-gray-500 mt-1">Scan QR Codes</p>
                    </div>
                    <div class="p-6 text-center">
                        <div class="w-14 h-14 bg-tech-blue/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-tech-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800">Connections</h3>
                        <p class="text-sm text-gray-500 mt-1">Your Network</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Privacy Note -->
        <section class="mb-16">
            <div class="bg-tech-blue/5 rounded-xl p-6 border border-tech-blue/20">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-tech-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Your Privacy Matters</h3>
                        <ul class="text-gray-600 space-y-2">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-tech-blue flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Your notes are private and encrypted - only you can see them
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-tech-blue flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                You control what information appears on your profile
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-tech-blue flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Delete connections anytime
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section>
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Frequently Asked Questions</h2>
            <div class="space-y-4">
                <details class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden group">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:bg-gray-50 flex items-center justify-between">
                        How do I share my QR code?
                        <svg class="w-5 h-5 text-tech-blue group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        Simply show your QR code from the Home screen to other attendees. They can scan it with their camera to view your profile and save you as a connection. You can also download the QR code as an image.
                    </div>
                </details>
                <details class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden group">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:bg-gray-50 flex items-center justify-between">
                        Can I edit my notes after saving?
                        <svg class="w-5 h-5 text-tech-blue group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        Yes! You can edit your notes anytime from the Connections tab. Tap on any connection to view their profile and update your notes.
                    </div>
                </details>
                <details class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden group">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:bg-gray-50 flex items-center justify-between">
                        Can the person I scanned see my notes?
                        <svg class="w-5 h-5 text-tech-blue group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        No, your notes are completely private. Only you can see the notes you write about your connections. Notes are encrypted at rest for extra security.
                    </div>
                </details>
                <details class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden group">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:bg-gray-50 flex items-center justify-between">
                        What if someone scans my QR code?
                        <svg class="w-5 h-5 text-tech-blue group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        They'll see your public profile information (name, company, job title, bio, and social link). They can save you as a connection on their side.
                    </div>
                </details>
                <details class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden group">
                    <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:bg-gray-50 flex items-center justify-between">
                        How do I delete a connection?
                        <svg class="w-5 h-5 text-tech-blue group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-4 text-gray-600">
                        Go to your Connections tab, tap on the connection you want to remove, and use the delete option. This only removes them from your list - it doesn't affect their connections.
                    </div>
                </details>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-tech-blue text-white/80 py-8 mt-16">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <p class="text-lg font-semibold text-white mb-2">
                <span class="font-bold">Connect</span><span class="font-light">Snap</span>
            </p>
            <p class="text-sm">Network smarter. Remember everyone.</p>
            <p class="text-xs mt-4 opacity-70">&copy; {{ date('Y') }} ConnectSnap. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>