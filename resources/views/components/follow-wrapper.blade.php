@props(['user'])

<div {{ $attributes }} x-data="{ 
        @auth
            following: {{ $user->isFollowedBy(auth()->user()) ? 'true' : 'false' }},
        @endauth
        followersCount: {{ $user->followers()->count() }},
        follow(){
            this.following = !this.following
            axios.get('/follow/{{ $user->id }}')
                .then(res => {
                    console.log(res.data)
                    this.followersCount= res.data.followersCount
                })
                .catch(err => {
                    console.log(err)
                })
        }
    }" class="w-[340px] border-l px-8">
    {{ $slot }}
</div>