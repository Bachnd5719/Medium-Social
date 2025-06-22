<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex">
                    <div class="flex-1 pr-10">
                        <h1 class="text-5xl">{{ $user->name }}</h1>
                        <div class="mt-10">
                            <x-post-item :posts="$posts" />
                        </div>
                    </div>
                    <x-follow-wrapper :user="$user">
                        <x-user-avatar :user="$user" size="w-24 h-24" />
                        <h3>{{ $user->name }}</h3>
                        <p class="text-gray-500"><span x-text="followersCount"></span> followers</p>
                        <p>{{ $user->bio }}</p>
                        @auth
                            @unless (auth()->user()->id === $user->id)
                                <div class="mt-4">
                                    <button @click="follow()" class=" rounded-full px-4 py-2 text-white "
                                        x-text="following?'Unfollow':'Follow'"
                                        :class="following? 'bg-red-600':'bg-emerald-600'">
                                    </button>
                                </div>
                            @else
                            @endunless
                        @endauth
                    </x-follow-wrapper>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>