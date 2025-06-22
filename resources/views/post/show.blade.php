<x-app-layout>
    <div class="py-4">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h1 class="text-2xl mb-4">{{ $post->title }}</h1>

                {{-- User Avatar --}}
                <div class="flex gap-4">
                    <x-user-avatar :user="$post->user" size="w-12 h-12" />
                    <div>
                        <x-follow-wrapper :user="$post->user" class="flex gap-2">
                            <a href="{{ route('profile.show', ['user' => $post->user]) }}" class="hover:underline">
                                <h3>{{ $post->user->name }}</h3>
                            </a>
                            @auth
                                @unless (auth()->user()->id === $post->user->id)
                                    &middot;
                                    <button @click="follow()" class="hover:underline" x-text="following?'Unfollow':'Follow'"
                                        :class="following? 'text-red-600':'text-emerald-600'">
                                    </button>
                                @else
                                @endunless
                            @endauth
                        </x-follow-wrapper>
                        <div class="flex gap-2 text-sm text-gray-500">
                            {{ $post->readTime() }} min read
                            &middot;
                            {{ $post->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>

                {{-- Emotion Section --}}
                <x-emotion-button :post="$post" />

                {{-- Content Section --}}
                <div class="mt-8 ">
                    <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" class="w-full">
                    <div class="mt-6">
                        <p>{{ $post->content }}</p>
                    </div>
                </div>

                {{-- Category Section --}}
                <div class="mt-8">
                    <a href="#" class="px-4 py-2 bg-gray-200 text-gray-900 rounded-2xl">
                        {{ $post->category->name }}
                    </a>
                </div>

                {{-- Emotion Section --}}
                <x-emotion-button :post="$post" />

            </div>
        </div>
    </div>
</x-app-layout>