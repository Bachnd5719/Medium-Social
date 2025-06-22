@props(['user','size'])

@if ($user->imageUrl())
    <a href="{{ route('profile.show', ['user' => $user]) }}">
        <img src="{{ $user->imageUrl() }}" alt="{{ $user->name }}" class="{{ $size }} rounded-full"></a>
@else
    <a href="{{ route('profile.show', ['user' => $user]) }}">
        <img src="https://images.icon-icons.com/3054/PNG/512/account_profile_user_icon_190494.png"
            alt="user-image" class="{{ $size }} rounded-full" ></a>
@endif