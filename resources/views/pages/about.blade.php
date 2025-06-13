<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('About Us') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Our Mission</h3>
                    <p class="mb-4">Welcome to [Your Website Name]! We are dedicated to providing [describe your core service, e.g., accurate song lyrics, a platform for music lovers]. Our mission is to [state your mission, e.g., connect fans with their favorite songs, foster a community around music].</p>

                    <h3 class="text-lg font-semibold mb-4">Our Story</h3>
                    <p class="mb-4">[Share a brief history of your website/project. When did you start? What inspired you?]. We believe that music is a universal language, and lyrics often tell powerful stories. We aim to be your go-to resource for discovering and understanding the words behind the melodies.</p>

                    <h3 class="text-lg font-semibold mb-4">Meet the Team</h3>
                    <p>We are a small team of passionate music enthusiasts and developers committed to making your experience on our website enjoyable and informative.</p>

                    <p class="mt-6 text-gray-600">Thank you for visiting us!</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 