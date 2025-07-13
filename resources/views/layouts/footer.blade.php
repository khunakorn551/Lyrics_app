<footer class="bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-center md:text-left mb-4 md:mb-0">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} Karen Song Lyrics. All rights reserved.
                </p>
            </div>
            <div class="flex space-x-6">
                <a href="{{ route('privacy') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    Privacy Policy
                </a>
                <a href="{{ route('terms') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    Terms of Service
                </a>
                <a href="{{ route('contact') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</footer>
