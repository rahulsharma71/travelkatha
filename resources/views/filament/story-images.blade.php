<div class="flex space-x-2">
    @foreach (json_decode($getRecord()->images, true) as $image)
        <img src="{{ asset('storage/' . $image) }}" alt="Story Image" class="w-12 h-12 rounded-md">
    @endforeach
</div>
