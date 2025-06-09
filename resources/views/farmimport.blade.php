<x-layouts.app>

<form action="{{ route('importfarm') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required class="form-control">
    <button type="submit" class="form-control">Upload</button>
</form>

</x-layouts.app>