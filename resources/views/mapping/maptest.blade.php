<x-layouts.app>
    <input type="text" id="rawfile" class="form-control"><button onclick="stringify()">Stringify</button>
    <form action="{{route('saveimage')}}" method="post">
        @csrf
    <div><input type="text" name="test" id="test" class="form-control"><button type="submit" class="btn btn-primary">Submit</button> </div>
</form>

<script>
    function stringify() {
        const inputValue = document.getElementById('rawfile').value;
        const json = JSON.stringify({ image: inputValue });
        console.log(json);
        document.getElementById('test').value = inputValue; // optional: send value to form field
    }
</script>
</x-layouts.app>
