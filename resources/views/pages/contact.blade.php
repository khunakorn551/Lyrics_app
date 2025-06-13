<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contact Us') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Get in Touch</h3>
                    <p class="mb-4">Have questions, feedback, or suggestions? We'd love to hear from you! Please use the methods below to reach out to us.</p>

                    <div class="mb-6">
                        <h4 class="font-semibold mb-2">Email:</h4>
                        <p class="mb-4">For general inquiries: <a href="mailto:info@[yourwebsitename].com" class="text-blue-600 hover:underline">info@[yourwebsitename].com</a></p>
                        <p>For support: <a href="mailto:support@[yourwebsitename].com" class="text-blue-600 hover:underline">support@[yourwebsitename].com</a></p>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-semibold mb-2">Mailing Address:</h4>
                        <p>[Your Company Name/Your Name]</p>
                        <p>[Your Street Address]</p>
                        <p>[Your City, State, Zip Code]</p>
                        <p>[Your Country]</p>
                    </div>

                    <div>
                        <h4 class="font-semibold mb-2">Social Media:</h4>
                        <p>Follow us on [Social Media Platform 1] and [Social Media Platform 2] for updates and news!</p>
                        <ul class="list-disc list-inside mt-2">
                            <li><a href="#" class="text-blue-600 hover:underline">Facebook</a></li>
                            <li><a href="#" class="text-blue-600 hover:underline">Twitter</a></li>
                        </ul>
                    </div>

                    <p class="mt-6 text-gray-600">We aim to respond to all inquiries within 24-48 hours.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 