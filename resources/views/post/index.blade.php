<x-app-layout>
    <div class="py-4">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
                <x-category :categories="$categories"/>
            </div>
            <div class="text-gray-900 mt-8">
                <x-post-item :posts="$posts" />
            </div>
            {{ $posts->links() }}
        </div>
    </div>
</x-app-layout>