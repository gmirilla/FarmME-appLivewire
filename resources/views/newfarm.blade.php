<x-layouts.app>
    <div>
        
        <form method="post" action='/newfarm/Createfarm'>
            {{ csrf_field() }}
            <div class="mb-3">
            <label for="farmowner" class="form-label">Name of Farm Owner</label>
            <input type="text" placeholder="Name of Farm Owner" id="farmowner"  name="farmowner" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="community" class="form-label">Community</label>
                <input type="text" placeholder="Name of Community" id="community" name="community"  required class="form-control">
            </div>
            <div class="mb-3">
            <label for="farmcode" class="form-label">Farm Code</label>
            <input type="text" placeholder="Farm Code" id="farmcode" name="farmcode" required  class="form-control">
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Register Farm</button>
            </div>
        </form>
    </div>
</x-layouts.app>