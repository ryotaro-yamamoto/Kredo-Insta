<!-- Create Story Modal -->
<div class="modal fade" id="createStoryModal" tabindex="-1" aria-labelledby="createStoryLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="createStoryLabel">Create Story</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label for="image" class="form-label">Photo</label>
            <input type="file" name="image" class="form-control" id="image">
          </div>
          
          <div class="mb-3">
            <label for="text" class="form-label">Text</label>
            <textarea name="text" id="text" class="form-control" placeholder="Say something..."></textarea>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Post</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>